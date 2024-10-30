<?php
define("MPQ_VIDEO_GALLERY_FOLDERNAME",     "clean-video-gallery");

/**
 * Returns array('success'=>true) or array('error'=>'error message')
 */
function convert2flv($uploadDirectory, $f_name){
	$f_fullname = $uploadDirectory . "/". $f_name;
	if (!is_writable($uploadDirectory)){
		return false;
	}

	if (!file_exists($f_fullname)){
		return false;
	}

	$size = filesize($f_fullname);

	if ($size == 0) {
		return flase;
	}

	$pathinfo = pathinfo($f_fullname);
	$ext = $pathinfo['extension'];
	$filename = str_replace(".".$ext, "", $pathinfo['basename']);
	//$filename = md5(uniqid());

	$filename_path = $uploadDirectory . "/". $filename . '.' . $ext;
	$v_filename = $filename.'.'.$ext;

	if($ext == '3gp' || $ext == '3gp2' || $ext == 'mp4')
	{
		$f_3gp = $filename_path;
		$f_flv = $filename_path.".flv";
		$f_wmv =  $filename_path.".flv";
		if (strpos(php_uname(),"Windows")===false)
		{
			exec('ffmpeg -i '.$f_3gp.'  -vcodec flv -f flv -r 24 -b 384k -cmp dct -level 21 -subcmp dct  -mbd 2 -flags +aic+cbp+mv0+mv4 -trellis 1 -ac 1 -ar 44100 -ab 48k '.$f_wmv);
		}
		else
		{
			exec('g:/ffmpeg -i '.$f_3gp.'  -vcodec flv -f flv -r 24 -b 384k -cmp dct -level 21 -subcmp dct  -mbd 2 -flags +aic+cbp+mv0+mv4 -trellis 1 -ac 1 -ar 44100 -ab 48k '.$f_wmv);
			/*if($ext == '3gp' || $ext == '3gp2')
			{
			exec('g:/ffmpeg/ffmpeg -i '.$f_3gp.'  -vcodec flv -f flv -r 24  -b 500k -cmp dct  -subcmp dct  -mbd 2 -flags +aic+cbp+mv0+mv4 -trellis 1 -ac 1 -ar 44100 -ab 48k '.$f_flv);
			exec('g:/ffmpeg/ffmpeg -i '.$f_flv.'  -acodec copy -vcodec libx264 -s 640x360 -vpre hq -vpre main -level 21 -refs 2 -b 384k -threads 0 '.$f_wmv);
		
			} else {
			exec('g:/ffmpeg/ffmpeg -i '.$f_3gp.'  -acodec copy -vcodec libvpx  -s 640x360 -vpre main -level 21 -refs 2 -b 384k -threads 0 '.$f_wmv);
			}*/
		}
		//exec('g:/ffmpeg -i '.$f_3gp.'  -vcodec flv -f flv -r 24  -b 500k -cmp dct  -subcmp dct  -mbd 2 -flags +aic+cbp+mv0+mv4 -trellis 1 -ac 1 -ar 44100 -ab 48k '.$f_wmv);
		if (file_exists($f_wmv))
		{
			$filename_path = $f_wmv;
			$v_filename = $v_filename.".flv";
			unlink($f_3gp);
		}
	}
	return $filename_path;
}

function file_is_displayable_video($file)
{
	$ffmpegInstance = new ffmpeg_movie($file);
	$height = $ffmpegInstance->getFrameHeight();
	
	
	if ( $height > 0 )
		$result = true;
	else
		$result = false;
	
	return apply_filters('file_is_displayable_video', $result, $path);
	
}

function gllr_video_read_metadata( $file ) {
	if ( ! file_exists( $file ) )
		return false;

	$vid = new ffmpeg_movie($file);

	// exif contains a bunch of data we'll probably never need formatted in ways
	// that are difficult to use. We'll normalize it and just extract the fields
	// that are likely to be useful.  Fractions and numbers are converted to
	// floats, dates to unix timestamps, and everything else to strings.
	$meta = array(
			'duration' => $vid->getDuration(),
			'framecount' => $vid->getFrameCount(),
			'framerate' => $vid->getFrameRate(),
			'comment' => $vid->getComment(),
			'author' => $vid->getAuthor(),
			'copyright' => $vid->getCopyright(),
			'genre' => $vid->getGenre(),
			'tracknumber' => $vid->getTrackNumber(),
			'year' => $vid->getYear(),
			'pixelformat' => $vid->getPixelFormat(),
			'bitrate' => $vid->getBitRate(),
			'videobitrate()' => $vid->getVideoBitRate(),
			'audiobitrate()' => $vid->getAudioBitRate(),
			'audiosampleRate()' => $vid->getAudioSampleRate(),
			'videocodec()' => $vid->getVideoCodec(),
			'audiocodec()' => $vid->getAudioCodec(),
			'audiochannels()' => $vid->getAudioChannels(),
			'hasaudio()' => $vid->hasAudio() ? "yes" : "no",
			'hasvideo()' => $vid->hasVideo() ? "yes" : "no",
			'title' => $vid->getTitle(),
	);

	return $meta;

}
function gllr_video_create_thumb($file)
{
	//Dont' timeout
	set_time_limit(0);

	
	$filename = basename($file);
	$filepath = dirname($file);
	if (!is_dir($filepath."/videothumbs/"))
		if (!mkdir($filepath."/videothumbs/")) return false;
	$thumbfilename = $filepath."/videothumbs/".$filename.".jpeg";
	if (strpos(php_uname(),"Windows")===false)
	{
		exec('ffmpeg  -itsoffset -2  -i '.$file.' -vcodec mjpeg -vframes 1 -an -f rawvideo  '.$thumbfilename);
	} else {
		exec('g:/ffmpeg  -itsoffset -2  -i '.$file.' -vcodec mjpeg -vframes 1 -an -f rawvideo  '.$thumbfilename);
	}
	if (file_exists($thumbfilename))
	{
		return $thumbfilename;
	} else return false;
}

function gllr_video_create_thumb2($file)
{
	//Dont' timeout
	set_time_limit(0);
	
	//Load the file (This can be any file - still takes ages)
	$mov = new ffmpeg_movie($file);
	
	//Get the total frames within the movie
	$total_frames = $mov->getFrameCount();
	
	$frame = mt_rand($total_frames/4,$total_frames/2);
	
	$getframe = $mov->getFrame($frame);
	// Check if the frame exists within the movie
	// If it does, place the frame number inside an array and break the current loop
	//For each frame found generate a thumbnail
	$gd_image = $getframe->toGDImage();
	$filename = basename($file);
	$filepath = dirname($file);
	if (!is_dir($filepath."/videothumbs/"))
		if (!mkdir($filepath."/videothumbs/")) return false;
	$thumbfilename = $filepath."/videothumbs/".$filename.".jpeg";
	imagejpeg($gd_image, $thumbfilename);
	imagedestroy($gd_image);
	return $thumbfilename;
}




/**
 * Retrieve an image to represent an attachment.
 *
 * A mime icon for files, thumbnail or intermediate size for images.
 *
 * @since 2.5.0
 *
 * @param int $attachment_id Image attachment ID.
 * @param string $size Optional, default is 'thumbnail'.
 * @param bool $icon Optional, default is false. Whether it is an icon.
 * @return bool|array Returns an array (url, width, height), or false, if no image is available.
 */
function gllr_video_get_thumbimage_src($attachment_id, $size='thumbnail', $icon = false) {

	// get a thumbnail or intermediate image if there is one
	if ( $image = gllr_videothumb_downsize($attachment_id, $size) )
		return $image;

	$src = false;

	if ( $icon && $src = wp_mime_type_icon($attachment_id) ) {
		$icon_dir = apply_filters( 'icon_dir', ABSPATH . WPINC . '/images/crystal' );
		$src_file = $icon_dir . '/' . wp_basename($src);
		@list($width, $height) = getimagesize($src_file);
	}
	if ( $src && $width && $height )
		return array( $src, $width, $height );
	return false;
}



/**
 * Scale an image to fit a particular size (such as 'thumb' or 'medium').
 *
 * Array with image url, width, height, and whether is intermediate size, in
 * that order is returned on success is returned. $is_intermediate is true if
 * $url is a resized image, false if it is the original.
 *
 * The URL might be the original image, or it might be a resized version. This
 * function won't create a new resized copy, it will just return an already
 * resized one if it exists.
 *
 * A plugin may use the 'image_downsize' filter to hook into and offer image
 * resizing services for images. The hook must return an array with the same
 * elements that are returned in the function. The first element being the URL
 * to the new image that was resized.
 *
 * @since 2.5.0
 * @uses apply_filters() Calls 'image_downsize' on $id and $size to provide
 *		resize services.
 *
 * @param int $id Attachment ID for image.
 * @param array|string $size Optional, default is 'medium'. Size of image, either array or string.
 * @return bool|array False on failure, array on success.
 */
function gllr_videothumb_downsize($id, $size = 'medium') {

	
	$img_url = wp_get_videothumb_url($id);
	$meta = wp_get_attachment_metadata($id);
	$width = $height = 0;
	$is_intermediate = false;
	$img_url_basename = wp_basename($img_url);

	// plugins can use this to provide resize services
	if ( $out = apply_filters('image_downsize', false, $id, $size) )
		return $out;

	// try for a new style intermediate size
	if ( $intermediate = image_get_intermediate_size($id, $size) ) {
		$img_url = str_replace($img_url_basename, $intermediate['file'], $img_url);
		$width = $intermediate['width'];
		$height = $intermediate['height'];
		$is_intermediate = true;
	}
	elseif ( $size == 'thumbnail' ) {
		// fall back to the old thumbnail
		if ( ($thumb_file = wp_get_attachment_thumb_file($id)) && $info = getimagesize($thumb_file) ) {
			$img_url = str_replace($img_url_basename, wp_basename($thumb_file), $img_url);
			$width = $info[0];
			$height = $info[1];
			$is_intermediate = true;
		}
	}
	if ( !$width && !$height && isset($meta['width'], $meta['height']) ) {
		// any other type: use the real image
		$width = $meta['width'];
		$height = $meta['height'];
	}

	if ( $img_url) {
		// we have the actual image size, but might need to further constrain it if content_width is narrower
		list( $width, $height ) = image_constrain_size_for_editor( $width, $height, $size );

		return array( $img_url, $width, $height, $is_intermediate );
	}
	return false;

}



/**
 * Retrieve the thumb URL for an video attachment.
 *
 * @since 2.1.0
 *
 * @param int $post_id Attachment ID.
 * @return string
 */
function wp_get_videothumb_url( $post_id = 0 ) {
	$post_id = (int) $post_id;
	if ( !$post =& get_post( $post_id ) )
		return false;

	if ( 'attachment' != $post->post_type )
		return false;

	$url = '';
	$pmeta = get_post_meta( $post->ID, '_wp_attachment_metadata', true);
	$pmetas = maybe_unserialize( $pmeta );
	if ( $file =  $pmetas['file']) { //Get attached file
		if ( ($uploads = wp_upload_dir()) && false === $uploads['error'] ) { //Get upload directory
			if ( 0 === strpos($file, $uploads['basedir']) ) //Check that the upload base exists in the file location
				$url = str_replace($uploads['basedir'], $uploads['baseurl'], $file); //replace file location with url location
			elseif ( false !== strpos($file, 'wp-content/uploads') )
			$url = $uploads['baseurl'] . substr( $file, strpos($file, 'wp-content/uploads') + 18 );
			else
				$url = $uploads['baseurl'] . "/$file"; //Its a newly uploaded file, therefor $file is relative to the basedir.
		}
	}

	if ( empty($url) ) //If any of the above options failed, Fallback on the GUID as used pre-2.7, not recommended to rely upon this.
		$url = get_the_guid( $post->ID );

	$url = apply_filters( 'wp_get_attachment_url', $url, $post->ID );

	if ( empty( $url ) )
		return false;

	return $url;
}

?>