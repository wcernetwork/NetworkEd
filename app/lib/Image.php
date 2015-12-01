<?php

class Image {

	public static function makeThumbs($file, $path, $image_name)
	{
		list($width, $height) = getimagesize($file);
		$theimage = imagecreatefromjpeg($file);

		if ($width > $height) {
			$y = 0;
			$x = ($width - $height) / 2;
			$smallestSide = $height;
			$biggestSide = $width;
		} else {
			$x = 0;
			$y = ($height - $width) / 2;
			$smallestSide = $width;
			$biggestSide = $height;
		}

		//making a square image to resize to small thumbnail
		$square_image;
		if ($width == $height)
		{
			$square_image = $theimage;
		}
		else
		{
			$square_image = imagecreatetruecolor($biggestSide, $biggestSide);
			// get the color white
			$color = imagecolorallocate($square_image, 255, 255, 255);
			// fill entire image
			imagefill($square_image, 0, 0, $color);
			imagecopyresampled($square_image, $theimage, $y, $x, 0, 0, $width, $height, $width, $height);
		}
		//making the small thumbnail
		$thumbSizeSmall = 200;
		$thumbnailSmall = imagecreatetruecolor($thumbSizeSmall, $thumbSizeSmall);
		imagecopyresampled($thumbnailSmall, $square_image, 0, 0, 0, 0, $thumbSizeSmall, $thumbSizeSmall, $biggestSide, $biggestSide);

		//making the big thumbnail
		$newWidth = $width;
		$newHeight = $height;

		$thumbSizeWidth = 600;
		$thumbSizeHeight = 350;

		if ($height > $thumbSizeHeight)
		{
			$newHeight = $thumbSizeHeight;
			$newWidth = $width * ($newHeight/$height);
		}
		else if ($width > $thumbSizeWidth)
		{
			$newWidth = $thumbSizeWidth;
			$newHeight = $height * ($newWidth/$width);
		}

		$thumbnailBig = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($thumbnailBig, $theimage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		//final output of both thumbnails
		header('Content-type: image/jpeg');
		imagejpeg($thumbnailSmall, $path.$image_name.'_thumb_sm.jpg');
		imagejpeg($thumbnailBig, $path.$image_name.'_thumb.jpg');

		$paths = array(
			'big' => $image_name.'_thumb.jpg',
			'sm' => $image_name.'_thumb_sm.jpg'
		);

		return $paths;
	}
}