<?php

class ExportController extends Controller {

	public function getExport($ids = '')
	{
		$idsArray = explode(',', $ids);

		$this->doExport($idsArray);
	}

	private function doExport($ids = null)
	{
		$query = Post::with('post_type', 'user')->where('approved', '=', true);

		if ($ids){
			$query->whereIn('id', $ids);
		}

		$data = $query->get();

		$output_data = array();
		foreach($data as $index=>$row){
			$output_data[$index]['id'] = $row['id'];
			$output_data[$index]['type'] = $row['post_type']['name'];
			$output_data[$index]['title'] = $row['title'];
			$output_data[$index]['location'] = $row['location'];
			$output_data[$index]['address'] = $row['address'];
			$output_data[$index]['city'] = $row['city'];
			$output_data[$index]['state'] = $row['state'];
			$output_data[$index]['zip'] = $row['zip'];
			$output_data[$index]['date'] = ($row['post_type_id'] == 3 || $row['post_type_id'] == 5 ? $row['expiration_date'] : '');
			$output_data[$index]['contact_name'] = $row['contact_name'];
			$output_data[$index]['contact_email'] = $row['contact_email'];
			$output_data[$index]['contact_phone'] = $row['contact_phone'];
			$output_data[$index]['contact_website'] = $row['contact_website'];
			$output_data[$index]['description'] = strip_tags($row['description']);
			$output_data[$index]['user'] = $row['user']['first_name'] . ' ' . $row['user']['last_name'];
		}
		foreach($output_data as $index=>$row){
			$post_tags = TagPost::with('tag.tag_category')->where('post_id', '=', $row['id'])->get();

			$tagsArray = array(
				0 => array(),
				1 => array(),
				2 => array(),
				3 => array(),
				4 => array(),
				5 => array()
			);
			foreach($post_tags as $post_tag){
				$tagsArray[$post_tag->tag[0]->tag_category->order][] = $post_tag->tag[0]->name;
			}
			$output_data[$index]['tags'] = $tagsArray;
		}

		Excel::create('NetworkEd Data '.date('m-d-Y'), function($excel) use($output_data){

			$excel->sheet('Sheet1', function($sheet) use($output_data){

				$sheet->setColumnFormat(array(
					'H' => 'm/d/yy h:mm'
				));

				$sheet->loadView('export.general')->with('output_data', $output_data);

			})->export('xls');

		});
	}

	//function for testing export in a browser
	public function getTest()
	{
		$data = Post::with('post_type', 'user')->where('approved', '=', true)->get();
		$output_data = array();
		foreach($data as $index=>$row){
			$output_data[$index]['id'] = $row['id'];
			$output_data[$index]['type'] = $row['post_type']['name'];
			$output_data[$index]['title'] = $row['title'];
			$output_data[$index]['location'] = $row['location'];
			$output_data[$index]['address'] = $row['address'];
			$output_data[$index]['city'] = $row['city'];
			$output_data[$index]['state'] = $row['state'];
			$output_data[$index]['zip'] = $row['zip'];
			$output_data[$index]['date'] = ($row['post_type_id'] == 3 || $row['post_type_id'] == 5 ? $row['expiration_date'] : '');
			$output_data[$index]['contact_name'] = $row['contact_name'];
			$output_data[$index]['contact_email'] = $row['contact_email'];
			$output_data[$index]['contact_phone'] = $row['contact_phone'];
			$output_data[$index]['contact_website'] = $row['contact_website'];
			$output_data[$index]['description'] = strip_tags($row['description']);
			$output_data[$index]['user'] = $row['user']['first_name'] . ' ' . $row['user']['last_name'];
		}
		foreach($output_data as $index=>$row){
			$post_tags = TagPost::with('tag.tag_category')->where('post_id', '=', $row['id'])->get();

			$tagsArray = array(
				0 => array(),
				1 => array(),
				2 => array(),
				3 => array(),
				4 => array(),
				5 => array()
			);
			foreach($post_tags as $post_tag){
				$tagsArray[$post_tag->tag[0]->tag_category->order][] = $post_tag->tag[0]->name;
			}
			$output_data[$index]['tags'] = $tagsArray;
		}

		return View::make('export.general')->with('output_data', $output_data);
	}

}