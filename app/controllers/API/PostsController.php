<?php

class PostsController extends Controller {

	public function getPost($id)
	{
		$validator = Validator::make(
			array('id' => $id),
			array('id' => array('required', 'integer'))
		);
		if ($validator->fails())
		{
			return 500;
		}
		
		$post = Post::with(
			array(
				'user'=>function($query){
					$query->select('id', 'first_name', 'last_name', 'city', 'state', 'role_title', 'organization', 'photo');
				},
				'post_type', 'likelist')
			)->find($id);

		if (!is_null($post))
		{
			$ignoreSuggestedTags = true;
			if (Auth::check())
			{
				if (Auth::user()->is_moderator || Auth::user()->id == $post->user_id)
				{
					$ignoreSuggestedTags = false;
				}
			}

			$post->favorited = false;
			if (Auth::check())
			{
				$find = Favorite::where('post_id', '=', $id)->where('user_id', '=', Auth::user()->id)->first();
				if (!is_null($find))
				{
					$post->favorited = true;
				}

				//check if user already liked this post, or if they're already logged in, don't allow access to liking
				foreach ($post->likelist as $like)
				{
					if ($like->user_id == Auth::user()->id)
					{
						$post->liked = true;
					}
				}
			}
			else
			{
				$post->liked = true;
			}

			$tagsList = array();
			$tagsArray = array();

			$post_tags = TagPost::with('tag.tag_category')->where('post_id', '=', $post->id)->get();

			foreach($post_tags as $post_tag)
			{
				$tagsArray[$post_tag->tag[0]->tag_category->name][] = array(
						'slug' => $post_tag->tag[0]->slug,
						'name' => $post_tag->tag[0]->name,
						'id' => $post_tag->tag[0]->id
					);
				$tagsList[] = $post_tag->tag[0]->id;
			}

			$post->tags = $tagsArray;

			$post->relatedContent = array();
			if ($tagsList)
			{
				$postIds = TagPost::whereIn('tag_id', $tagsList)
								->whereNotIn('post_id', [$id])
								->selectRaw('post_id, count(*) as count')
								->groupBy('post_id')
								->orderBy('count', 'desc')
								->limit(3)
								->get(array('post_id'));

				$thePostIds = array();
				foreach($postIds as $pid)
				{
					$thePostIds[] = $pid->post_id;
				}
				$post->relatedContent = Post::with('post_type')
						->whereIn('id', $thePostIds)
						->orderBy('likes', 'updated_at')
						->get();
			}
		}

		return $post;
	}

	public function postGetImageFromLink()
	{
		$url = Input::get('url');

		$image_id = uniqid().'.jpg';
		$image_path = '../public/uploads/';

		if ($this->download_image($url, $image_path.$image_id))
		{
			$thumbs = Image::makeThumbs($image_path.$image_id, '../public/uploads/', $image_id);

			$paths = array(
				'path' => $image_id,
				'thumbnail' => $thumbs['big'],
				'thumbnail_sm' => $thumbs['sm']
			);

			return json_encode($paths);
		}
		else
		{
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
	}

	private function download_image($file_location, $new_file_name)
	{
		$ch = curl_init($file_location);
		curl_setopt ($ch, CURLOPT_BINARYTRANSFER, true);  
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);  
	    curl_setopt ($ch, CURLOPT_HEADER, false);  
	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0); 
	    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($ch);
		$errno = curl_errno($ch);

		if (!$errno || $errno == '60')
		{
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);

			if ($http_code != '200')
			{
				return false;
			}
			else
			{
				$downloaded_image = imagecreatefromstring($response);
				header('Content-Type: image/jpeg');
				imagejpeg($downloaded_image, $new_file_name);

				//now do another copy, so we'll get it on a white background
				list($width, $height) = getimagesize($new_file_name);

				// Create destination image width standard sizes
	            $destination = imagecreatetruecolor($width, $height);

	            // get the color white
	            $color = imagecolorallocate($destination, 255, 255, 255);
	            // fill entire image
	            imagefill($destination, 0, 0, $color);

	            // Create strem data of uploaded file
	            $stream = imagecreatefromstring($response);
	            // Copy and save
	            imagecopy($destination, $stream, 0, 0, 0, 0, $width, $height);
	            header('Content-Type: image/jpeg');
	            imagejpeg($destination, $new_file_name, 100);

				return true;
			}
		}
		else
		{
			return false;
		}
	}

	public function postValidateVideo()
	{
		if (Auth::check())
		{
			$input = Input::all();

			$url = $input['video_link'];

			if (strpos(strtolower($url), 'http://') === false && strpos(strtolower($url), 'https://') === false)
			{
				$url = 'http://'.$url;
			}

			$parse = parse_url($url);

			$thumb_id = uniqid();
		    $thumb_path = '../public/uploads/video_thumbs/';

		    $video_id = null;
		    $video_host = null;

		    if (!isset($parse['host']))
		    {
		    	return '400';
		    }

			if ($parse['host'] == 'youtube.com' || $parse['host'] == 'www.youtube.com')
			{
				$video_host = 'youtube';

				$params = parse_url($url, PHP_URL_QUERY);

				/**split the query string into an array**/
			    if($params == null) $arr['v'] = $params;
			    else  parse_str($params, $arr);
			    /** end split the query string into an array**/
			    if(! isset($arr['v'])) return '400'; //fast fail for links with no v attrib - youtube only

			    $video_id = $arr['v'];

			    //check if the video exists
			    //  Initiate curl
				$ch = curl_init();
				// Disable SSL verification
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				// Will return the response, if false it print the response
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// Set the url
				curl_setopt($ch, CURLOPT_URL,'https://www.googleapis.com/youtube/v3/videos?key=AIzaSyAdsDLxlAbejl5VAZe8ya_JZWPrLnpRB48&part=snippet&id=' . $video_id);
				// Execute
				$result = curl_exec($ch);

			    if (!curl_errno($ch))
			    {
			    	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			    	if ($http_code != '200')
			    	{
			    		return '400';
			    	}
			    	curl_close($ch);
			    }
			    else
			    {
			    	curl_close($ch);
			    	return '400';
			    }

			    $response_data = json_decode($result, true);
			    $thumb_url = $response_data['items'][0]['snippet']['thumbnails']['high']['url'];

			    $ch = curl_init($thumb_url);
			    $fp = fopen($thumb_path.$thumb_id.'.jpg', 'wb');
			    curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);
			}
			else if ($parse['host'] == 'vimeo.com' || $parse['host'] == 'www.vimeo.com')
			{
				$video_host = 'vimeo';

				$id = $parse['path'];
				$id = str_replace('/', '', $id);
				$id = intval($id);
				if ($id == 0 || $id == null || $id == '')
				{
					return '400';
				}

				$video_id = $id;

				//get the video info
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, 'http://vimeo.com/api/v2/video/'.$id.'.json');
				$data = curl_exec($ch);
				curl_close($ch);

				$data = json_decode($data);
				
				//save the thumbnail
				$ch = curl_init($data[0]->thumbnail_large);
			    $fp = fopen($thumb_path.$thumb_id.'.jpg', 'wb');
			    curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);

			}
			else
			{
				return '400';
			}

			$thumbs = Image::makeThumbs($thumb_path.$thumb_id.'.jpg', $thumb_path, $thumb_id);

		    $paths = array(
				'thumbnail' => 'video_thumbs/'.$thumb_id.'.jpg',
				'thumbnail_sm' => 'video_thumbs/'.$thumbs['sm'],
				'video_id' => $video_id,
				'video_host' => $video_host
			);

		    return json_encode($paths);
		}
		else
		{
			return '400';
		}
	}

	//upload an image
	public function postUploadMedia()
	{
		if (Auth::check())
		{
			try {
			    // Trying upload
			    $result = Bookcase::upload(array(
		            'input' => 'file',
		            'libraryPath' => 'public/uploads/'
		        ));

		        $uploaded_image_path = str_replace('../public/uploads/', '', $result->path);
				$substr_image_path = str_replace('.jpg', '', $uploaded_image_path);

				$thumbs = Image::makeThumbs($result->path, '../public/uploads/', $substr_image_path);

				$paths = array(
					'path' => $uploaded_image_path,
					'thumbnail' => $thumbs['big'],
					'thumbnail_sm' => $thumbs['sm']
				);

				return json_encode($paths);

			} catch (Exception $e) {
			    return 500;
			}     
		}
	}

	//upload a supplementary image media file (don't need thumbnails)
	public function postUploadMediaSupplementary()
	{
		if (Auth::check())
		{
			try {
			    // Trying upload
			    $result = Bookcase::upload(array(
		            'input' => 'file',
		            'libraryPath' => 'public/uploads/'
		        ));

		        $uploaded_image_path = str_replace('../public/uploads/', '', $result->path);

				$paths = array(
					'path' => $uploaded_image_path
				);

				return json_encode($paths);

			} catch (Exception $e) {
			    return 500;
			}     
		}
	}

	//new or updating entry
	public function postSave()
	{
		if (Auth::check())
		{
			$input = Input::all();

			$date = NULL;
			if (isset($input['expiration_date']))
			{
				$date = new DateTime($input['expiration_date']);	
			}

			$thumbnail_file = $input['thumbnail'];
			$thumbnail_sm_file = $input['thumbnail_sm'];
			$primary_media = $input['primary_media'];

			$video_id = null;
			$video_host = null;

			if ($input['primary_media_type'] == 'image')
			{
				$primary_media = $input['primary_media'];
				$thumbnail_file = $input['thumbnail'];
				$thumbnail_sm_file = $input['thumbnail_sm'];
			}
			else if ($input['primary_media_type'] == 'video')
			{
				$primary_media = $input['primary_media'];
				if (strpos(strtolower($primary_media), 'http://') === false && strpos(strtolower($primary_media), 'https://') === false)
				{
					$primary_media = 'http://'.$primary_media;
				}

				$video_id = $input['video_id'];
				$video_host = $input['video_host'];
			}
			else if ($input['primary_media_type'] == 'document')
			{
				$primary_media = $input['primary_media'];
				if (strpos(strtolower($primary_media), 'http://') === false && strpos(strtolower($primary_media), 'https://') === false)
				{
					$primary_media = 'http://'.$primary_media;
				}
			}

			$media_2 = null;
			if (!isset($input['media_2_type']))
			{
				$input['media_2_type'] = null;
			}
			else if ($input['media_2_type'] == 'image')
			{
				$media_2 = $input['media_2'];
			}
			else if ($input['media_2_type'] == 'video')
			{
				$media_2 = $input['media_2'];
				if (strpos(strtolower($media_2), 'http://') === false && strpos(strtolower($media_2), 'https://') === false)
				{
					$media_2 = 'http://'.$media_2;
				}
			}
			else if ($input['media_2_type'] == 'document')
			{
				$media_2 = $input['media_2'];
				if (strpos(strtolower($media_2), 'http://') === false && strpos(strtolower($media_2), 'https://') === false)
				{
					$media_2 = 'http://'.$media_2;
				}
			}
			else
			{
				$input['media_2_type'] = null;
			}

			$media_3 = null;
			if (!isset($input['media_3_type']))
			{
				$input['media_3_type'] = null;
			}
			else if ($input['media_3_type'] == 'image')
			{
				$media_3 = $input['media_3'];
			}
			else if ($input['media_3_type'] == 'video')
			{
				$media_3 = $input['media_3'];
				if (strpos(strtolower($media_3), 'http://') === false && strpos(strtolower($media_3), 'https://') === false)
				{
					$media_3 = 'http://'.$media_3;
				}
			}
			else if ($input['media_3_type'] == 'document')
			{
				$media_3 = $input['media_3'];
				if (strpos(strtolower($media_3), 'http://') === false && strpos(strtolower($media_3), 'https://') === false)
				{
					$media_3 = 'http://'.$media_3;
				}
			}
			else
			{
				$input['media_3_type'] = null;
			}

			$contact_name = '';
			$contact_email = '';
			$contact_phone = '';
			$contact_website = '';
			if (isset($input['contact_name']))
			{
				$contact_name = $input['contact_name'];
			}
			if (isset($input['contact_email']))
			{
				$contact_email = $input['contact_email'];
			}
			if (isset($input['contact_phone']))
			{
				$contact_phone = $input['contact_phone'];
			}
			if (isset($input['contact_website']))
			{
				$contact_website = $input['contact_website'];

				if ($contact_website !== '' && strpos(strtolower($contact_website), 'http') === false && strpos(strtolower($contact_website), 'http') === false)
				{
					$contact_website = 'http://'.$contact_website;
				}
			}

			$post = null;

			if ($input['id'] == -1)
			{
				$approved = false;
				if (Auth::user()->is_moderator)
				{
					$approved = true; //moderator/admin posts are automatically added
				}

				$post = Post::create(
					array(
						'title' => $input['title'],
						'description' => $input['description'],
						'post_type_id' => $input['post_type_id'],
						'location' => $input['location'],
						'address' => $input['address'],
						'city' => $input['city'],
						'state' => $input['state'],
						'zip' => $input['zip'],
						'user_id' => $input['user']['id'],
						'contact_name' => $contact_name,
						'contact_email' => $contact_email,
						'contact_phone' => $contact_phone,
						'contact_website' => $contact_website,
						'likes' => 0,
						'approved' => $approved,
						'latitude' => $input['coords']['latitude'],
						'longitude' => $input['coords']['longitude'],
						'thumbnail' => $thumbnail_file,
						'thumbnail_sm' => $thumbnail_sm_file,
						'primary_media' => $primary_media,
						'primary_media_type' => $input['primary_media_type'],
						'media_2' => $media_2,
						'media_2_type' => $input['media_2_type'],
						'media_3' => $media_3,
						'media_3_type' => $input['media_3_type'],
						'video_id' => $video_id,
						'video_host' => $video_host,
						'expiration_date' => $date,
						'summary' => $input['summary']
					));

				if (Auth::user()->is_moderator)
				{
					$post->approved_at = date("Y-m-d H:i:s");
					$post->save();
				}

				if (isset($input['tags']))
				{
					//if this user is not an admin or moderator, check whether this is a new tag when adding it
					$checkSuggest = true;
					if (Auth::user()->is_moderator)
					{
						$checkSuggest = false;
					}
					
					foreach ($input['tags'] as $tagCategory => $tagArray)
					{
						foreach ($tagArray as $tag)
						{
							TagUtil::tag($post->id, $tag['name'], $tagCategory, $checkSuggest);
						}
					}
				}

				//tag for format, but don't double tag if multiple media of same type
				if (isset($input['primary_media_type']))
				{
					TagUtil::tag($post->id, ucfirst($input['primary_media_type']), 'Format', false);
				}
				if (isset($input['media_2_type']) && $input['media_2_type'] != $input['primary_media_type'])
				{
					TagUtil::tag($post->id, ucfirst($input['media_2_type']), 'Format', false);
				}
				if (isset($input['media_3_type']) && $input['media_3_type'] != $input['primary_media_type']
						&& (!isset($input['media_2_type']) || $input['media_3_type'] != $input['media_2_type']))
				{
					TagUtil::tag($post->id, ucfirst($input['media_3_type']), 'Format', false);
				}
			}
			else
			{
				$post = Post::find($input['id']);

				if ($post && (Auth::user()->is_moderator || Auth::id() == $post->user_id))
				{
					$post->title = $input['title'];
					$post->description = $input['description'];
					$post->post_type_id = $input['post_type_id'];
					$post->location = $input['location'];
					$post->address = $input['address'];
					$post->city = $input['city'];
					$post->state = $input['state'];
					$post->zip = $input['zip'];
					$post->contact_name = $contact_name;
					$post->contact_email = $contact_email;
					$post->contact_phone = $contact_phone;
					$post->contact_website = $contact_website;
					$post->latitude = $input['coords']['latitude'];
					$post->longitude = $input['coords']['longitude'];
					$post->thumbnail = $thumbnail_file;
					$post->thumbnail_sm = $thumbnail_sm_file;
					$post->primary_media = $primary_media;
					$post->primary_media_type = $input['primary_media_type'];
					$post->media_2 = $media_2;
					$post->media_2_type = $input['media_2_type'];
					$post->media_3 = $media_3;
					$post->media_3_type = $input['media_3_type'];
					$post->video_id = $video_id;
					$post->video_host = $video_host;
					$post->expiration_date = $date;
					$post->summary = $input['summary'];

					if (!Auth::user()->is_moderator)
					{
						//if this is not an admin/moderator making the edit, the post must go back to the approval queue. Approvals are made through postApproval(), which also facilitates sending emails to notify users the post is approved
						//admins need to actually click the Approve button for approvals to happen, so if they're making edits to an existing unapproved post, they still need to go through the noraml approval process
						$post->approved = false;
					}

					$post->save();

					TagUtil::untag($post->id);

					if (isset($input['tags']))
					{
						foreach ($input['tags'] as $tagCategory => $tagArray)
						{
							//format tags are handled differently
							if ($tagCategory != 'Format')
							{
								foreach ($tagArray as $tag)
								{
									//this flag will check if the tag exists when adding it. If it doesn't exist, must be either approved through the admin panel, or approved by an admin in the approval queue
									$checkSuggest = true;
									if (Auth::user()->is_moderator)
									{
										$checkSuggest = false;
									}

									TagUtil::tag($post->id, $tag['name'], $tagCategory, $checkSuggest);
								}
							}
						}
					}

					//tag for format, but don't double tag if multiple media of same type
					if (isset($input['primary_media_type']))
					{
						TagUtil::tag($post->id, ucfirst($input['primary_media_type']), 'Format', false);
					}
					if (isset($input['media_2_type']) && $input['media_2_type'] != $input['primary_media_type'])
					{
						TagUtil::tag($post->id, ucfirst($input['media_2_type']), 'Format', false);
					}
					if (isset($input['media_3_type']) && $input['media_3_type'] != $input['primary_media_type']
							&& (!isset($input['media_2_type']) || $input['media_3_type'] != $input['media_2_type']))
					{
						TagUtil::tag($post->id, ucfirst($input['media_3_type']), 'Format', false);
					}
				}
				else
				{
					return 'incorrect permissions';
				}
			}

			$ignoreSuggestedTags = true;
			if (Auth::user()->is_moderator || Auth::user()->id == $post->user_id)
			{
				$ignoreSuggestedTags = false;
			}
			
			$tagsArray = array();
			$post_tags = TagPost::with('tag.tag_category')->where('post_id', '=', $post->id)->get();
			foreach($post_tags as $post_tag)
			{
				if (!$post_tag->tag[0]->pending){
					$tagsArray[$post_tag->tag[0]->tag_category->name][] = array(
							'slug' => $post_tag->tag[0]->slug,
							'name' => $post_tag->tag[0]->name
						);
				}
			}
			$post->tags = $tagsArray;

			return $post;
		}
		else
		{
			return 'error';
		}
	}

	public function postCheckTagExistence()
	{
		$tag_name = trim(Input::get('name'));
		$slug = TagUtil::slug($tag_name);

		$tag_search = Tag::with('tag_category')->where('slug', '=', $slug)->first();
		if($tag_search){
			return array(
					'response' => 'error',
					'tag' => $tag_search
				);
		}
		else{
			return array(
					'response' => 'success',
					'tag_slug' => $slug,
					'tag_name' => $tag_name
				);
		}
	}

	public function postUpdateViews()
	{
		$id = Input::get('id');

		$post = Post::find($id);
		if($post){
			$post->timestamps = false;

			$post->increment('num_views');
			$post->last_viewed_at = date("Y-m-d H:i:s");

			$post->save();
		}
	}

	public function postApprove()
	{
		$id = Input::get('id');

		if (Auth::check() && Auth::user()->is_moderator)
		{
			$post = Post::with('user')->find($id);

			if (!is_null($post))
			{
				$post->timestamps = false;

				$post->approved = true;
				$post->approved_at = date("Y-m-d H:i:s");

				$post->save();

				//also need to approve any tags that were previously unapproved 
				$post_tags = TagPost::with('tag.tag_category')->where('post_id', '=', $post->id)->get();
				foreach($post_tags as $post_tag)
				{
					if ($post_tag->tag[0]->pending)
					{
						TagUtil::approvetag($post_tag->tag_id);
					}
				}

				Mail::queue('emails.posts.approved', array('postid'=>$post->id, 'title'=>$post->title, 'firstname'=>$post->user->first_name), function($message) use($post) {
			        $message->to($post->user->email, $post->user->first_name.' '.$post->user->last_name)->subject('NetworkEd Post Approved');
			    });

				return '200';
			}
			else
			{
				return '400';
			}
		}
		else
		{
			return '401';
		}
	}

	public function postReject()
	{
		$id = Input::get('id');

		if (Auth::check() && Auth::user()->is_moderator)
		{
			$post = Post::find($id);

			if (!is_null($post))
			{
				Mail::queue('emails.posts.rejected', array('postid'=>$post->id, 'title'=>$post->title, 'firstname'=>$post->user->first_name), function($message) use($post) {
			        $message->to($post->user->email, $post->user->first_name.' '.$post->user->last_name)->subject('NetworkEd Post Not Approved');
			    });

				$post->delete();

				return '200';
			}
			else
			{
				return '400';
			}
		}
		else
		{
			return '401';
		}
	}

	public function postDelete()
	{
		$id = Input::get('id');

		if (Auth::check())
		{
			$post = Post::find($id);

			if (!is_null($post) && (Auth::user()->is_admin || Auth::id() == $post->user_id))
			{
				TagUtil::untag($post->id);

				$post->delete();
				return '200';
			}
			else
			{
				return '401';
			}
		}
		else
		{
			return '401';
		}
	}

	public function postLike()
	{
		if (Auth::check())
		{
			$post_id = Input::get('id');

			$post = Post::find($post_id);
			$checkLikes = Like::where('post_id', '=', $post_id)->where('user_id', '=', Auth::user()->id)->first();

			if (isset($post) && is_null($checkLikes))
			{
				$post->timestamps = false;
				$post->increment('likes');

				$newLike = Like::create(
					array(
						'user_id' => Auth::user()->id,
						'post_id' => $post_id
					));

				return '200';
				
			}
		}
		else
		{
			return '401';
		}
	}

	public function postFavorite()
	{
		$post_id = Input::get('id');

		if (Auth::check())
		{
			//first check to make sure it's not already a favorite
			$find = Favorite::where('post_id', '=', $post_id)->where('user_id', '=', Auth::user()->id)->first();
			if (is_null($find))
			{
				$favorite = Favorite::create(
					array(
						'user_id' => Auth::user()->id,
						'post_id' => $post_id,
					));
				return '200';
			}
			else
			{
				return '201';
			}
		}
		else
		{
			return '401';
		}
	}

	public function postRemoveFavorite()
	{
		$post_id = Input::get('id');

		if (Auth::check())
		{
			$delete = Favorite::where('post_id', '=', $post_id)
								->where('user_id', '=', Auth::user()->id)
								->delete();
			return '200';
		}
		else
		{
			return '401';
		}
	}

	public function postCheckDuplicate()
	{
		$id = Input::get('id');
		$title = Input::get('title');

		if (Auth::check())
		{
			return Post::with('post_type')
							->where('title', 'LIKE', "%$title%")
							->where('approved', '=', true)
							->whereNotIn('id', [$id])
							->get();
		}
		else
		{
			return '401';
		}
	}
}