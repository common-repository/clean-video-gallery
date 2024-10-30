<?php
/*
Plugin Name: MPQ Clean Video Gallery
Plugin URI:  
Description: This plugin allows you to implement video gallery page into web site.
Author: Montreal Prot QA
Version: 0.3
Author URI: bigtester.com
License: GPLv2 or later
*/

/* Copyright 2013 Montreal Prot QA 
   Copyright 2012 BestWebSoft  

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once ("lib/gllr_video_processor.php");

if( ! function_exists( 'gllr_video_plugin_install' ) ) {
	function gllr_video_plugin_install() {
		$filename_1 = WP_PLUGIN_DIR .'/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/template/gallery-video-template.php';
		$filename_2 = WP_PLUGIN_DIR .'/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/template/gallery-video-single-template.php';

		$filename_theme_1 = get_stylesheet_directory() .'/gallery-video-template.php';
		$filename_theme_2 = get_stylesheet_directory() .'/gallery-video-single-template.php';

		if ( ! file_exists( $filename_theme_1 ) ) {
			$handle = @fopen( $filename_1, "r" );
			$contents = @fread( $handle, filesize( $filename_1 ) );
			@fclose( $handle );
			if ( ! ( $handle = @fopen( $filename_theme_1, 'w' ) ) )
				return false;
			@fwrite( $handle, $contents );
			@fclose( $handle );
			chmod( $filename_theme_1, octdec(755) );
		}
		else {
			$handle = @fopen( $filename_theme_1, "r" );
			$contents = @fread( $handle, filesize( $filename_theme_1 ) );
			@fclose( $handle );
			if ( ! ( $handle = @fopen( $filename_theme_1.'.bak', 'w' ) ) )
				return false;
			@fwrite( $handle, $contents );
			@fclose( $handle );
			
			$handle = @fopen( $filename_1, "r" );
			$contents = @fread( $handle, filesize( $filename_1 ) );
			@fclose( $handle );
			if ( ! ( $handle = @fopen( $filename_theme_1, 'w' ) ) )
				return false;
			@fwrite( $handle, $contents );
			@fclose( $handle );
			chmod( $filename_theme_1, octdec(755) );
		}
		if ( ! file_exists( $filename_theme_2 ) ) {
			$handle = @fopen( $filename_2, "r" );
			$contents = @fread( $handle, filesize( $filename_2 ) );
			@fclose( $handle );
			if ( ! ( $handle = @fopen( $filename_theme_2, 'w' ) ) )
				return false;
			@fwrite( $handle, $contents );
			@fclose( $handle );
			chmod( $filename_theme_2, octdec(755) );
		}
		else {
			$handle = @fopen( $filename_theme_2, "r" );
			$contents = @fread( $handle, filesize( $filename_theme_2 ) );
			@fclose( $handle );
			if ( ! ( $handle = @fopen( $filename_theme_2.'.bak', 'w' ) ) )
				return false;
			@fwrite( $handle, $contents );
			@fclose( $handle );
			
			$handle = @fopen( $filename_2, "r" );
			$contents = @fread( $handle, filesize( $filename_2 ) );
			@fclose( $handle );
			if ( ! ( $handle = @fopen( $filename_theme_2, 'w' ) ) )
				return false;
			@fwrite( $handle, $contents );
			@fclose( $handle );
			chmod( $filename_theme_2, octdec(755) );
		}
	}
}

if( ! function_exists( 'gllr_video_admin_error' ) ) {
	function gllr_video_admin_error() {
		$post = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : "" ;
		$post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : "" ;
		if ( ( 'galleryvideo' == get_post_type( $post )  || 'galleryvideo' == $post_type ) && ( ! file_exists( get_stylesheet_directory() .'/gallery-video-template.php' ) || ! file_exists( get_stylesheet_directory() .'/gallery-video-single-template.php' ) ) ) {
				gllr_video_plugin_install();
		}
		if ( ( 'galleryvideo' == get_post_type( $post )  || 'galleryvideo' == $post_type ) && ( ! file_exists( get_stylesheet_directory() .'/gallery-video-template.php' ) || ! file_exists( get_stylesheet_directory() .'/gallery-video-single-template.php' ) ) ) {
			echo '<div class="error"><p><strong>'.__( 'The following files "gallery-video-template.php" and "gallery-video-single-template.php" were not found in the directory of your theme. Please copy them from the directory `/wp-content/plugins/MPQ Video Gallery Folder/template/` to the directory of your theme for the correct work of the plugin', 'galleryvideo' ).'</strong></p></div>';
		}
	}
}

if( ! function_exists( 'gllr_video_plugin_uninstall' ) ) {
	function gllr_video_plugin_uninstall() {
		if ( file_exists( get_stylesheet_directory() .'/gallery-video-template.php' ) && ! unlink( get_stylesheet_directory() .'/gallery-video-template.php' ) ) {
			add_action( 'admin_notices', create_function( '', ' return "Error delete template file";' ) );
		}
		if ( file_exists( get_stylesheet_directory() .'/gallery-video-single-template.php' ) && ! unlink( get_stylesheet_directory() .'/gallery-video-single-template.php' ) ) {
			add_action( 'admin_notices', create_function( '', ' return "Error delete template file";' ) );
		}
		if( get_option( 'gllr_video_options' ) ) {
			delete_option( 'gllr_video_options' );
		}
	}
}

// Create post type for Gallery
if( ! function_exists( 'gllr_video_post_type_images' ) ) {
	function gllr_video_post_type_images() {
		register_post_type('galleryvideo', array(
			'labels' => array(
				'name' => __( 'Video Galleries', 'galleryvideo' ),
				'singular_name' => __( 'Video Gallery', 'galleryvideo' ),
				'add_new' => __( 'Add New', 'galleryvideo' ),
				'add_new_item' => __( 'Add New Video Gallery', 'galleryvideo' ),
				'edit_item' => __( 'Edit Video Gallery', 'galleryvideo' ),
				'new_item' => __( 'New Video Gallery', 'galleryvideo' ),
				'view_item' => __( 'View Video Gallery', 'galleryvideo' ),
				'search_items' => __( 'Search Video Galleries', 'galleryvideo' ),
				'not_found' =>	__( 'No Video Galleries found', 'galleryvideo' ),
				'parent_item_colon' => '',
				'menu_name' => __( 'Video Galleries', 'galleryvideo' )
					
			),
			'public' => true,
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'supports' => array('title', 'editor', 'thumbnail', 'author', 'page-attributes' ),
			'register_meta_box_cb' => 'init_metaboxes_galleryvideo',
			'menu_position' => 50
		));
	}
}

if( ! function_exists( 'gllr_video_addImageAncestorToMenu' ) ) {
	function gllr_video_addImageAncestorToMenu( $classes ) {
		if ( is_singular( 'galleryvideo' ) ) {
			global $wpdb, $post;
			
			if ( empty( $post->ancestors ) ) {
				$parent_id = $wpdb->get_var( "SELECT $wpdb->posts.ID FROM $wpdb->posts, $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'gallery-video-template.php' AND post_status = 'publish' AND $wpdb->posts.ID = $wpdb->postmeta.post_id" );
				while ( $parent_id ) {
					$page = get_page( $parent_id );
					if( $page->post_parent > 0 )
						$parent_id  = $page->post_parent;
					else 
						break;
				}
				wp_reset_query();
				if( empty( $parent_id ) ) 
					return $classes;
				$post_ancestors = array( $parent_id );
			}
			else {
				$post_ancestors = $post->ancestors;
			}			
			
			$menuQuery = "SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE meta_key = '_menu_item_object_id' AND meta_value IN (" . implode(',', $post_ancestors) . ")";
			$menuItems = $wpdb->get_col( $menuQuery );
			
			if ( is_array( $menuItems ) ) {
				foreach ( $menuItems as $menuItem ) {
					if ( in_array( 'menu-item-' . $menuItem, $classes ) ) {
						$classes[] = 'current-page-ancestor';
					}
				}
			}
		}

		return $classes;
	}
}

function init_metaboxes_galleryvideo() {
		add_meta_box( 'Upload-File', __( 'Upload File', 'galleryvideo' ), 'gllr_video_post_custom_box', 'galleryvideo', 'normal', 'high' ); 
		add_meta_box( 'gallery-video-Shortcode', __( 'Video Gallery Shortcode', 'galleryvideo' ), 'gllr_video_post_shortcode_box', 'galleryvideo', 'side', 'high' ); 
}

// Create custom meta box for portfolio post type
if ( ! function_exists( 'gllr_video_post_custom_box' ) ) {
	function gllr_video_post_custom_box( $obj = '', $box = '' ) {
		global $post;
		$gllr_video_options = get_option( 'gllr_video_options' );
		$key = "gllr_video_image_text";
		$gllr_video_download_link = get_post_meta( $post->ID, 'gllr_video_download_link', true );
		$link_key = "gllr_video_link_url";
		$error = "";
		$uploader = true;
		
		$post_types = get_post_types( array( '_builtin' => false ) );
		if( ! is_writable ( ABSPATH .'wp-content/plugins/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/upload/files/' ) ) {
			$error = __( "The gallery temp directory (MPQ Video Gallery Folder/upload/files) not writeable by your webserver. Please use the standard WP functional to upload the images (media library)", 'galleryvideo' );
			$uploader = false;
		}
		?>
		<div style="padding-top:10px;"><label for="uploadscreen"><?php echo __( 'Choose an video to upload:', 'galleryvideo' ); ?></label>
			<input name="MAX_FILE_SIZE" value="1048576" type="hidden" />
			<div id="file-uploader-demo1" style="padding-top:10px;">	
				<?php echo $error; ?>
				<noscript>			
					<p><?php echo __( 'Please enable JavaScript to use the file uploader.', 'galleryvideo' ); ?></p>
				</noscript>         
			</div>
			<ul id="files" ></ul>
			<div id="hidden"></div>
			<div style="clear:both;"></div></div>
			<!--<div class="gllr_video_order_message hidden">
				 <input type="checkbox" name="gllr_video_download_link" value="1" <?php if( $gllr_video_download_link != '' ) echo "checked='checked'"; ?> style="position:relative; top:-2px " /> <?php _e('Allow download link for videos in this gallery', 'galleryvideo' ); ?><br /><br />
				<?php _e( 'Please use drag and drop function to change the order of the output of videos and do not forget to save post.', 'galleryvideo'); ?>
				<br />
				<?php _e( 'Please do not forget to select ', 'galleryvideo'); echo ' `'; _e('Attachments order by', 'galleryvideo' ); echo '` -> `'; _e('attachments order', 'galleryvideo' ); echo '` '; _e('in the settings of the plugin (page ', 'galleryvideo'); ?><a href="<?php echo admin_url( 'admin.php?page='.MPQ_VIDEO_GALLERY_FOLDERNAME.'.php', 'http' ); ?>" target="_blank"><?php echo admin_url( 'admin.php?page='.MPQ_VIDEO_GALLERY_FOLDERNAME.'.php', 'http' ); ?></a>)
			</div>-->
		<script type="text/javascript">
		<?php if ($uploader === true) { ?>
		jQuery(document).ready(function()
		{
				var uploader = new qq.FileUploader({
						element: document.getElementById('file-uploader-demo1'),
						action: '../wp-admin/admin-ajax.php?action=gllr_video_upload_gallery_image',
						debug: false,
						onComplete: function(id, fileName, result) {
							if(result.error) {
								//
							}
							else {
								jQuery('<li></li>').appendTo('#files').html('<img src="'+result['videothumb']+'" alt="" /><div style="width:200px">'+fileName+'<br />' +result.width+'x'+result.height+'</div>').addClass('success');
								jQuery('<input type="hidden" name="undefined[]" id="undefined" value="'+result['videofilename']+'" />').appendTo('#hidden');
							}
						}
				});           
				jQuery('#images_albumdiv').remove();

		});
		<?php } ?>
		function img_delete(id) {
			jQuery('#'+id).hide();
			jQuery('#delete_images').append('<input type="hidden" name="delete_images[]" value="'+id+'" />');
		}
		</script>
		<?php

		$posts = get_posts(array(
			"showposts"			=> -1,
			"what_to_show"	=> "posts",
			"post_status"		=> "inherit",
			"post_type"			=> "attachment",
			"orderby"				=> $gllr_video_options['order_by'],
			"order"					=> $gllr_video_options['order'],
			"post_parent"		=> $post->ID)); ?>
		<ul class="gallery clearfix">
		<?php foreach ( $posts as $page ):
			$image_text = get_post_meta( $page->ID, $key, FALSE );
			echo '<li id="'.$page->ID.'" class="gllr_video_image_block"><div class="gllr_video_drag">';
				$image_attributes = gllr_video_get_thumbimage_src( $page->ID, 'thumbnail' );
				echo '<div class="gllr_video_border_image"><img src="'.$image_attributes[0].'" alt="'.$page->post_title.'" title="'.$page->post_title.'" height="'.get_option( 'thumbnail_size_h' ).'" width="'.get_option( 'thumbnail_size_w' ).'" /></div>';
				echo '<input type="text" name="gllr_video_image_text['.$page->ID.']" value="'.get_post_meta( $page->ID, $key, TRUE ).'" class="gllr_video_image_text" />';
				echo '<input type="text" name="gllr_video_order_text['.$page->ID.']" value="'.$page->menu_order.'" class="gllr_video_order_text '.( $page->menu_order == 0 ? "hidden" : '' ).'" />';
				//echo '<br />'.__("Link URL", "galleryvideo").'<br /><input type="text" name="gllr_video_link_url['.$page->ID.']" value="'.get_post_meta( $page->ID, $link_key, TRUE ).'" class="gllr_video_link_text" /><br /><span class="small_text">'.__("(clicking on image <br /> open the link in new window)", "galleryvideo").'</span>';
				echo '<div class="delete"><a href="javascript:void(0);" onclick="img_delete('.$page->ID.');">'.__("Delete", "galleryvideo").'</a><div/>';
			echo '</div></li>';
    endforeach; ?>
		</ul><div style="clear:both;"></div>
		<div id="delete_images"></div>	 
	<?php
	}
}

// Create shortcode meta box for portfolio post type
if ( ! function_exists( 'gllr_video_post_shortcode_box' ) ) {
	function gllr_video_post_shortcode_box( $obj = '', $box = '' ) {
		global $post;
		?>
		<p><?php _e( 'You can add the Single Gallery on the page or in the post by inserting this shortcode in the content', 'galleryvideo' ); ?>:</p>
		<p><code>[print_gllr_video id=<?php echo $post->ID; ?>]</code></p>
		<!-- <p><?php _e( 'If you want to take a brief display of the gallery with a link to a Single Sallery Page', 'galleryvideo' ); ?>:</p>
		<p><code>[print_gllr_video id=<?php echo $post->ID; ?> display=short]</code></p>-->
		<?php }
}

if ( ! function_exists ( 'gllr_video_save_postdata' ) ) {
	function gllr_video_save_postdata( $post_id, $post ) {
		if (!solveConflictWithGalleryPlugin("save_post",$post->post_type))
			return;
		global $wpdb;
		$key = "gllr_video_image_text";
		$link_key = "gllr_video_link_url";

		if( isset( $_REQUEST['undefined'] ) && ! empty( $_REQUEST['undefined'] ) ) {
			$array_file_name = $_REQUEST['undefined'];
			$uploadFile = array();
			$newthumb = array();
			$time = current_time('mysql');

			$uploadDir =  wp_upload_dir( $time );

			while( list( $key, $val ) = each( $array_file_name ) ) {
				$imagename = $val;
				$uploadFile[] = $uploadDir["path"] ."/" . $imagename;
			}
			reset( $array_file_name );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			while( list( $key, $val ) = each( $array_file_name ) ) {
				$file_name = $val;
				if( file_exists( $uploadFile[$key] ) ){
					$uploadFile[$key] = $uploadDir["path"] ."/" . pathinfo($uploadFile[$key], PATHINFO_FILENAME ).uniqid().".".pathinfo($uploadFile[$key], PATHINFO_EXTENSION );
				}

				if ( copy ( ABSPATH .'wp-content/plugins/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/upload/files/'.$file_name, $uploadFile[$key] ) ) {
					unlink( ABSPATH .'wp-content/plugins/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/upload/files/'.$file_name );
					$overrides = array('test_form' => false );
				
					$file = $uploadFile[$key];
					$filename = basename( $file );
					
					$wp_filetype	= wp_check_filetype( $filename, null );
					$attachment		= array(
						 'post_mime_type' => $wp_filetype['type'],
						 'post_title' => $filename,
						 'post_content' => '',
						 'post_status' => 'inherit'
					);
					$attach_id = wp_insert_attachment( $attachment, $file );
					$attach_data = gllr_video_generate_attachment_metadata( $attach_id, $file );
					wp_update_attachment_metadata( $attach_id, $attach_data );			
					$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_parent = %d WHERE ID = %d", $post->ID, $attach_id ) );
				}
			}
		}
		if( isset( $_REQUEST['delete_videos'] ) ) {
			foreach( $_REQUEST['delete_videos'] as $delete_id ) {
				delete_post_meta( $delete_id, $key );
				wp_delete_attachment( $delete_id );
			}
		}
		if( isset( $_REQUEST['gllr_video_image_text'] ) ) {
			$posts = get_posts(array(
				"showposts"			=> -1,
				"what_to_show"	=> "posts",
				"post_status"		=> "inherit",
				"post_type"			=> "attachment",
				"orderby"				=> "menu_order",
				"order"					=> "ASC",
				//"post_mime_type"=> "image/jpeg,image/gif,image/jpg,image/png",
				"post_parent"		=> $post->ID));
			foreach ( $posts as $page ) {
				if( isset( $_REQUEST['gllr_video_image_text'][$page->ID] ) ) {
					$value = $_REQUEST['gllr_video_image_text'][$page->ID];
					if( get_post_meta( $page->ID, $key, FALSE ) ) {
						// Custom field has a value and this custom field exists in database
						update_post_meta( $page->ID, $key, $value );
					} 
					elseif($value) {
						// Custom field has a value, but this custom field does not exist in database
						add_post_meta( $page->ID, $key, $value );
					}
				}
			}
		}
		if( isset( $_REQUEST['gllr_video_order_text'] ) ) {
			foreach( $_REQUEST['gllr_video_order_text'] as $key=>$val ){
				wp_update_post( array( 'ID'=>$key, 'menu_order'=>$val ) );
			}
		}
		if( isset( $_REQUEST['gllr_video_link_url'] ) ) {
			$posts = get_posts(array(
				"showposts"			=> -1,
				"what_to_show"	=> "posts",
				"post_status"		=> "inherit",
				"post_type"			=> "attachment",
				"orderby"				=> "menu_order",
				"order"					=> "ASC",
				//"post_mime_type"=> "image/jpeg,image/gif,image/jpg,image/png",
				"post_parent"		=> $post->ID));
			foreach ( $posts as $page ) {
				if( isset( $_REQUEST['gllr_video_link_url'][$page->ID] ) ) {
					$value = $_REQUEST['gllr_video_link_url'][$page->ID];
					if( get_post_meta( $page->ID, $link_key, FALSE ) ) {
						// Custom field has a value and this custom field exists in database
						update_post_meta( $page->ID, $link_key, $value );
					} 
					elseif($value) {
						// Custom field has a value, but this custom field does not exist in database
						add_post_meta( $page->ID, $link_key, $value );
					}
				}
			}
		}
		if( isset( $_REQUEST['gllr_video_download_link'] ) ){
			if( get_post_meta( $post->ID, 'gllr_video_download_link', FALSE ) ) {
				// Custom field has a value and this custom field exists in database
				update_post_meta( $post->ID, 'gllr_video_download_link', 1 );
			} 
			else {
				// Custom field has a value, but this custom field does not exist in database
				add_post_meta( $post->ID, 'gllr_video_download_link', 1 );
			}
		}
		else{
			delete_post_meta( $post->ID, 'gllr_video_download_link' );
		}
	}
}

if ( ! function_exists ( 'gllr_video_plugin_init' ) ) {
	function gllr_video_plugin_init() {
	// Internationalization, first(!)
		load_plugin_textdomain( 'galleryvideo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
	}
}

if( ! function_exists( 'gllr_video_custom_permalinks' ) ) {
	function gllr_video_custom_permalinks( $rules ) {
		global $wpdb;
		$parent = $wpdb->get_var("SELECT $wpdb->posts.post_name FROM $wpdb->posts, $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'gallery-video-template.php' AND (post_status = 'publish' OR post_status = 'private') AND $wpdb->posts.ID = $wpdb->postmeta.post_id");	
		$newrules = array();
		if( ! empty( $parent ) ) {
			$newrules['(.+)/'.$parent.'/([^/]+)/?$']= 'index.php?post_type=galleryvideo&name=$matches[2]&posts_per_page=-1';
			$newrules[''.$parent.'/([^/]+)/?$']= 'index.php?post_type=galleryvideo&name=$matches[1]&posts_per_page=-1';
			$newrules[''.$parent.'/page/([^/]+)/?$']= 'index.php?pagename='.$parent.'&paged=$matches[1]';
			$newrules[''.$parent.'/page/([^/]+)?$']= 'index.php?pagename='.$parent.'&paged=$matches[1]';
		}
		else {
			$newrules['(.+)/galleryvideo/([^/]+)/?$']= 'index.php?post_type=galleryvideo&name=$matches[2]&posts_per_page=-1';
			$newrules['galleryvideo/([^/]+)/?$']= 'index.php?post_type=galleryvideo&name=$matches[1]&posts_per_page=-1';
			$newrules['galleryvideo/page/([^/]+)/?$']= 'index.php?pagename=galleryvideo&paged=$matches[1]';
			$newrules['galleryvideo/page/([^/]+)?$']= 'index.php?pagename=galleryvideo&paged=$matches[1]';
		}
		return $newrules + $rules;
	}
}

// flush_rules() if our rules are not yet included
if ( ! function_exists( 'gllr_video_flush_rules' ) ) {
		function gllr_video_flush_rules(){
				$rules = get_option( 'rewrite_rules' );
				if ( ! isset( $rules['(.+)/galleryvideo/([^/]+)/?$'] ) || ! isset( $rules['/galleryvideo/([^/]+)/?$'] ) ) {
						global $wp_rewrite;
						$wp_rewrite->flush_rules();
				}
		}
}

if ( ! function_exists( 'gllr_video_template_redirect' ) ) {
	function gllr_video_template_redirect() { 
		global $wp_query, $post, $posts;
		if( 'galleryvideo' == get_post_type() && "" == $wp_query->query_vars["s"] ) {
			include( STYLESHEETPATH . '/gallery-video-single-template.php' );
			exit(); 
		}
	}
}


// Change the columns for the edit CPT screen
if ( ! function_exists( 'gllr_video_change_columns' ) ) {
	function gllr_video_change_columns( $cols ) {
		$cols = array(
			'cb'				=> '<input type="checkbox" />',
			'title'			=> __( 'Title', 'galleryvideo' ),
			'autor'			=> __( 'Author', 'galleryvideo' ),
			'galleryvideo'			=> __( 'Photo\'s', 'galleryvideo' ),
			'status'		=> __( 'Public', 'galleryvideo' ),
			'dates'			=> __( 'Date', 'galleryvideo' )
		);
		return $cols;
	}
}

if ( ! function_exists( 'gllr_video_custom_columns' ) ) {
	function gllr_video_custom_columns( $column, $post_id ) {
		global $wpdb;
		$post = get_post( $post_id );	
		$row = $wpdb->get_results( "SELECT *
				FROM $wpdb->posts
				WHERE $wpdb->posts.post_parent = $post_id
				AND $wpdb->posts.post_type = 'attachment'
				AND (
				$wpdb->posts.post_status = 'inherit'
				)
				ORDER BY $wpdb->posts.post_title ASC" );
		switch ( $column ) {
		 //case "category":
			case "autor":
				$author_id=$post->post_author;
				echo '<a href="edit.php?post_type=post&amp;author='.$author_id.'">'.get_the_author_meta( 'user_nicename' , $author_id ).'</a>';
				break;
			case "galleryvideo":
				echo count($row);
				break;
			case "status":
				if(	$post->post_status == 'publish' )
					echo '<a href="javascript:void(0)">Yes</a>';
				else
					echo '<a href="javascript:void(0)">No</a>';
				break;
			case "dates":
				echo strtolower( __( date( "F", strtotime( $post->post_date ) ), 'kerksite' ) )." ".date( "j Y", strtotime( $post->post_date ) );				
				break;
		}
	}
}

if ( ! function_exists( 'get_ID_by_slug' ) ) {
	function get_ID_by_slug($page_slug) {
			$page = get_page_by_path($page_slug);
			if ($page) {
					return $page->ID;
			} 
			else {
					return null;
			}
	}
}

if( ! function_exists( 'the_excerpt_max_charlength' ) ) {
	function the_excerpt_max_charlength( $charlength ) {
		$excerpt = get_the_excerpt();
		$charlength ++;
		if( strlen( $excerpt ) > $charlength ) {
			$subex = substr( $excerpt, 0, $charlength-5 );
			$exwords = explode( " ", $subex );
			$excut = - ( strlen ( $exwords [ count( $exwords ) - 1 ] ) );
			if( $excut < 0 ) {
				echo substr( $subex, 0, $excut );
			} 
			else {
				echo $subex;
			}
			echo "...";
		} 
		else {
			echo $excerpt;
		}
	}
}

if( ! function_exists( 'gllr_video_page_css_class' ) ) {
	function gllr_video_page_css_class( $classes, $item ) {
		global $wpdb;
		$post_type = get_query_var( 'post_type' );
		$parent_id = 0;
		if( $post_type == "galleryvideo" ) {
			$parent_id = $wpdb->get_var( "SELECT $wpdb->posts.ID FROM $wpdb->posts, $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'gallery-video-template.php' AND post_status = 'publish' AND $wpdb->posts.ID = $wpdb->postmeta.post_id" );
			while ( $parent_id ) {
				$page = get_page( $parent_id );
				if( $page->post_parent > 0 )
					$parent_id  = $page->post_parent;
				else 
					break;
			}
			wp_reset_query();
		}
		if ( $item->ID == $parent_id ) {
        array_push( $classes, 'current_page_item' );
    }
    return $classes;
	}
}

if( ! function_exists( 'mpq_add_menu_render' ) ) {
	function mpq_add_menu_render() {
		global $title;
		$active_plugins = get_option('active_plugins');
		$all_plugins		= get_plugins();

		$array_activate = array();
		$array_install	= array();
		$array_recomend = array();
		$count_activate = $count_install = $count_recomend = 0;
		$array_plugins	= array(
			array( 'gallery-xmlrpc-interface\/gllr_xmlrpc.php', 'Gallery Plugin XMLRPC Interface', 'http://wordpress.org/extend/plugins/gallery-plugin-xmlrpc-interface/', 'http://wordpress.org/extend/plugins/gallery-plugin-xmlrpc-interface/', '/wp-admin/plugin-install.php?tab=search&type=term&s=xmlrpc&plugin-search-input=Search', 'options-general.php?page=gllrxmlrpc_extapisettings' ), 
			array( 'frontend-signup-site-clone\/frontend-signup-site-clone.php', 'Frondend Signup Site Clone', 'http://wordpress.org/extend/plugins/frontend-signup-site-clone/', 'http://wordpress.org/extend/plugins/frontend-signup-site-clone/', '/wp-admin/plugin-install.php?tab=search&type=term&s=&plugin-search-input=Search+Plugins', 'admin.php?page=frontend-signup-site-clone' ),
			array( MPQ_VIDEO_GALLERY_FOLDERNAME.'\/gallery-plugin-video.php', 'Video Gallery', 'http://wordpress.org/extend/plugins/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/', 'http://wordpress.org/extend/plugins/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/', '/wp-admin/plugin-install.php?tab=search&type=term&s=&plugin-search-input=Search+Plugins', 'admin.php?page=gallery-plugin-video.php' ),
		);
		$this_plugin = dirname(plugin_basename(__FILE__));
		foreach($array_plugins as $plugins) {
			
			if (strpos($plugins[0],$this_plugin)!==false)
			{
				$array_activate[$count_activate]['title'] = $plugins[1];
				$array_activate[$count_activate]['link']	= $plugins[2];
				$array_activate[$count_activate]['href']	= $plugins[3];
				$array_activate[$count_activate]['url']	= $plugins[5];
				$count_activate++;
			}
			elseif( 0 < count( preg_grep( "/".$plugins[0]."/", $active_plugins ) ) ) {
				$array_activate[$count_activate]['title'] = $plugins[1];
				$array_activate[$count_activate]['link']	= $plugins[2];
				$array_activate[$count_activate]['href']	= $plugins[3];
				$array_activate[$count_activate]['url']	= $plugins[5];
				$count_activate++;
			}
			else if( array_key_exists(str_replace("\\", "", $plugins[0]), $all_plugins) ) {
				$array_install[$count_install]['title'] = $plugins[1];
				$array_install[$count_install]['link']	= $plugins[2];
				$array_install[$count_install]['href']	= $plugins[3];
				$count_install++;
			}
			else {
				$array_recomend[$count_recomend]['title'] = $plugins[1];
				$array_recomend[$count_recomend]['link']	= $plugins[2];
				$array_recomend[$count_recomend]['href']	= $plugins[3];
				$array_recomend[$count_recomend]['slug']	= $plugins[4];
				$count_recomend++;
			}
		}
		?>
		<div class="wrap">
			<div class="icon32 icon32-mpq" id="icon-options-general"></div>
			<h2><?php echo $title;?></h2>
			<?php if( 0 < $count_activate ) { ?>
			<div>
				<h3><?php _e( 'Activated plugins', 'galleryvideo' ); ?></h3>
				<?php foreach( $array_activate as $activate_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $activate_plugin['title']; ?></div> <p><a href="<?php echo $activate_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'galleryvideo'); ?></a> <a href="<?php echo $activate_plugin['url']; ?>"><?php echo __( "Settings", 'galleryvideo'); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_install ) { ?>
			<div>
				<h3><?php _e( 'Installed plugins', 'galleryvideo' ); ?></h3>
				<?php foreach($array_install as $install_plugin) { ?>
				<div style="float:left; width:200px;"><?php echo $install_plugin['title']; ?></div> <p><a href="<?php echo $install_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'galleryvideo'); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_recomend ) { ?>
			<div>
				<h3><?php _e( 'Recommended plugins', 'galleryvideo' ); ?></h3>
				<?php foreach( $array_recomend as $recomend_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $recomend_plugin['title']; ?></div> <p><a href="<?php echo $recomend_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'galleryvideo'); ?></a> <a href="<?php echo $recomend_plugin['href']; ?>" target="_blank"><?php echo __( "Download", 'galleryvideo'); ?></a> <a class="install-now" href="<?php echo get_bloginfo( "url" ) . $recomend_plugin['slug']; ?>" title="<?php esc_attr( sprintf( __( 'Install %s' ), $recomend_plugin['title'] ) ) ?>" target="_blank"><?php echo __( 'Install now from wordpress.org', 'galleryvideo' ) ?></a></p>
				<?php } ?>
				<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php _e( 'If you have any questions, please contact us via zhouyibhic@gmail.com or fill in our contact form on our site', 'galleryvideo' ); ?> <a href="http://bigtester.com/">http://bigtester.com/contact/</a></span>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}

if( ! function_exists( 'add_gllr_video_admin_menu' ) ) {
	function add_gllr_video_admin_menu() {
		add_menu_page( 'MPQ Plugins', 'MPQ Plugins', 'manage_options', 'mpq_plugins', 'mpq_add_menu_render', plugins_url("images/icon_16.png", __FILE__),900.43); 
		add_submenu_page('mpq_plugins', __( 'Video Gallery', 'galleryvideo' ), __( 'Video Gallery', 'galleryvideo' ), 'manage_options', "gallery-plugin-video.php", 'gllr_video_settings_page');

		//call register settings function
		add_action( 'admin_init', 'register_gllr_video_settings' );
	}
}

// register settings function
if( ! function_exists( 'register_gllr_video_settings' ) ) {
	function register_gllr_video_settings() {
		global $wpmu;
		global $gllr_video_options;
		//global $wp_filesystem;
		//WP_Filesystem();
		//var_dump($wp_filesystem);

		$gllr_video_option_defaults = array(
			'gllr_video_custom_size_name' => array( 'album-thumb', 'photo-thumb' ),
			'gllr_video_custom_size_px' => array( array(120, 80), array(160, 120) ),
			'custom_image_row_count' => 3,
			'start_slideshow' => 0,
			'slideshow_interval' => 2000,
			'order_by' => 'menu_order',
			'order' => 'ASC',
			'read_more_link_text' => __( 'Watch videos &raquo;', 'galleryvideo' ),
			'return_link' => 0,
			'return_link_text' => __( 'Return to all albums', 'galleryvideo' ),
			'return_link_shortcode' => 0
		);

		// install the option defaults
		if ( 1 == $wpmu ) {
			if( ! get_site_option( 'gllr_video_options' ) ) {
				add_site_option( 'gllr_video_options', $gllr_video_option_defaults, '', 'yes' );
			}
		} 
		else {
			if( ! get_option( 'gllr_video_options' ) )
				add_option( 'gllr_video_options', $gllr_video_option_defaults, '', 'yes' );
		}

		// get options from the database
		if ( 1 == $wpmu )
		 $gllr_video_options = get_site_option( 'gllr_video_options' ); // get options from the database
		else
		 $gllr_video_options = get_option( 'gllr_video_options' );// get options from the database

		// array merge incase this version has added new options
		$gllr_video_options = array_merge( $gllr_video_option_defaults, $gllr_video_options );

		update_option( 'gllr_video_options', $gllr_video_options );

		if ( function_exists( 'add_image_size' ) ) { 
			add_image_size( 'album-thumb', $gllr_video_options['gllr_video_custom_size_px'][0][0], $gllr_video_options['gllr_video_custom_size_px'][0][1], true );
			add_image_size( 'photo-thumb', $gllr_video_options['gllr_video_custom_size_px'][1][0], $gllr_video_options['gllr_video_custom_size_px'][1][1], true );
		}
	}
}

if( ! function_exists( 'gllr_video_settings_page' ) ) {
	function gllr_video_settings_page() {
		global $gllr_video_options;
		$error = "";
		
		// Save data for settings page
		if( isset( $_REQUEST['gllr_video_form_submit'] ) && check_admin_referer( plugin_basename(__FILE__), 'gllr_video_nonce_name' ) ) {
			$gllr_video_request_options = array();
			$gllr_video_request_options["gllr_video_custom_size_name"] = $gllr_video_options["gllr_video_custom_size_name"];

			$gllr_video_request_options["gllr_video_custom_size_px"] = array( 
				array( intval( trim( $_REQUEST['custom_image_size_w_album'] ) ), intval( trim($_REQUEST['custom_image_size_h_album'] ) ) ), 
				array( intval( trim( $_REQUEST['custom_image_size_w_photo'] ) ), intval( trim($_REQUEST['custom_image_size_h_photo'] ) ) ) 
			);
			$gllr_video_request_options["custom_image_row_count"] =  intval( trim( $_REQUEST['custom_image_row_count'] ) );
			if( $gllr_video_request_options["custom_image_row_count"] == "" || $gllr_video_request_options["custom_image_row_count"] < 1 )
				$gllr_video_request_options["custom_image_row_count"] = 1;

			if( isset( $_REQUEST['start_slideshow'] ) )
				$gllr_video_request_options["start_slideshow"] = 1;
			else
				$gllr_video_request_options["start_slideshow"] = 0;
			$gllr_video_request_options["slideshow_interval"] = $_REQUEST['slideshow_interval'];
			$gllr_video_request_options["order_by"] = $_REQUEST['order_by'];
			$gllr_video_request_options["order"] = $_REQUEST['order'];

			if( isset( $_REQUEST['return_link'] ) )
				$gllr_video_request_options["return_link"] = 1;
			else
				$gllr_video_request_options["return_link"] = 0;

			if( isset( $_REQUEST['return_link_shortcode'] ) )
				$gllr_video_request_options["return_link_shortcode"] = 1;
			else
				$gllr_video_request_options["return_link_shortcode"] = 0;

			$gllr_video_request_options["return_link_text"] = $_REQUEST['return_link_text'];
			$gllr_video_request_options["read_more_link_text"] = $_REQUEST['read_more_link_text'];			

			// array merge incase this version has added new options
			$gllr_video_options = array_merge( $gllr_video_options, $gllr_video_request_options );

			// Check select one point in the blocks Arithmetic actions and Difficulty on settings page
			update_option( 'gllr_video_options', $gllr_video_options, '', 'yes' );
			$message = __( "Options saved.", 'galleryvideo' );
		}

		if ( ! file_exists( get_stylesheet_directory() .'/gallery-video-template.php' ) || ! file_exists( get_stylesheet_directory() .'/gallery-video-single-template.php' ) ) {
				gllr_video_plugin_install();
		}
		if ( ! file_exists( get_stylesheet_directory() .'/gallery-video-template.php' ) || ! file_exists( get_stylesheet_directory() .'/gallery-video-single-template.php' ) ) {
			$error .= __( 'The following files "gallery-video-template.php" and "gallery-video-single-template.php" were not found in the directory of your theme. Please copy them from the directory `/wp-content/plugins/MPQ Video Gallery Folder/template/` to the directory of your theme for the correct work of the plugin', 'galleryvideo' );
		}

		// Display form on the setting page
	?>
	<div class="wrap">
		<div class="icon32 icon32-mpq" id="icon-options-general"></div>
		<h2><?php _e('Gallery Options', 'galleryvideo' ); ?></h2>
		<div class="updated fade" <?php if( ! isset( $_REQUEST['gllr_video_form_submit'] ) || $error != "" ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
		<div class="error" <?php if( "" == $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $error; ?></strong></p></div>
		<p><?php _e( "If you would like to add a Single Gallery to your page or post, just copy and put this shortcode onto your post or page content:", 'galleryvideo' ); ?> [print_gllr_video id=Your_gallery_post_id]</p>
		<form method="post" action="admin.php?page=gallery-plugin-video.php" id="gllr_video_form_image_size">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('The size of the cover album for gallery', 'galleryvideo' ); ?> </th>
					<td>
						<label for="custom_image_size_name"><?php _e( 'Image size name', 'galleryvideo' ); ?></label> <?php echo $gllr_video_options["gllr_video_custom_size_name"][0]; ?><br />
						<label for="custom_image_size_w"><?php _e( 'Width (in px)', 'galleryvideo' ); ?></label> <input type="text" name="custom_image_size_w_album" value="<?php echo $gllr_video_options["gllr_video_custom_size_px"][0][0]; ?>" /><br />
						<label for="custom_image_size_h"><?php _e( 'Height (in px)', 'galleryvideo' ); ?></label> <input type="text" name="custom_image_size_h_album" value="<?php echo $gllr_video_options["gllr_video_custom_size_px"][0][1]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Size for gallery image', 'galleryvideo' ); ?> </th>
					<td>
						<label for="custom_image_size_name"><?php _e( 'Image size name', 'galleryvideo' ); ?></label> <?php echo $gllr_video_options["gllr_video_custom_size_name"][1]; ?><br />
						<label for="custom_image_size_w"><?php _e( 'Width (in px)', 'galleryvideo' ); ?></label> <input type="text" name="custom_image_size_w_photo" value="<?php echo $gllr_video_options["gllr_video_custom_size_px"][1][0]; ?>" /><br />
						<label for="custom_image_size_h"><?php _e( 'Height (in px)', 'galleryvideo' ); ?></label> <input type="text" name="custom_image_size_h_photo" value="<?php echo $gllr_video_options["gllr_video_custom_size_px"][1][1]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2"><span style="color: #888888;font-size: 10px;"><?php _e( 'WordPress will create a copy of the post thumbnail with the specified dimensions when you upload a new photo.', 'galleryvideo' ); ?></span></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Count images in row', 'galleryvideo' ); ?> </th>
					<td>
						<input type="text" name="custom_image_row_count" value="<?php echo $gllr_video_options["custom_image_row_count"]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Start slideshow', 'galleryvideo' ); ?> </th>
					<td>
						<input type="checkbox" name="start_slideshow" value="1" <?php if( $gllr_video_options["start_slideshow"] == 1 ) echo 'checked="checked"'; ?> />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Slideshow interval', 'galleryvideo' ); ?> </th>
					<td>
						<input type="text" name="slideshow_interval" value="<?php echo $gllr_video_options["slideshow_interval"]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Attachments order by', 'galleryvideo' ); ?> </th>
					<td>
						<input type="radio" name="order_by" value="ID" <?php if( $gllr_video_options["order_by"] == 'ID' ) echo 'checked="checked"'; ?> /> <label class="label_radio" for="order_by"><?php _e( 'attachment id', 'galleryvideo' ); ?></label><br />
						<input type="radio" name="order_by" value="title" <?php if( $gllr_video_options["order_by"] == 'title' ) echo 'checked="checked"'; ?> /> <label class="label_radio" for="order_by"><?php _e( 'attachment title', 'galleryvideo' ); ?></label><br />
						<input type="radio" name="order_by" value="date" <?php if( $gllr_video_options["order_by"] == 'date' ) echo 'checked="checked"'; ?> /> <label class="label_radio" for="order_by"><?php _e( 'date', 'galleryvideo' ); ?></label><br />
						<input type="radio" name="order_by" value="menu_order" <?php if( $gllr_video_options["order_by"] == 'menu_order' ) echo 'checked="checked"'; ?> /> <label class="label_radio" for="order_by"><?php _e( 'attachments order (the integer fields in the Insert / Upload Media Gallery dialog )', 'galleryvideo' ); ?></label><br />
						<input type="radio" name="order_by" value="rand" <?php if( $gllr_video_options["order_by"] == 'rand' ) echo 'checked="checked"'; ?> /> <label class="label_radio" for="order_by"><?php _e( 'random', 'galleryvideo' ); ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Attachments order', 'galleryvideo' ); ?> </th>
					<td>
						<input type="radio" name="order" value="ASC" <?php if( $gllr_video_options["order"] == 'ASC' ) echo 'checked="checked"'; ?> /> <label class="label_radio" for="order"><?php _e( 'ASC (ascending order from lowest to highest values - 1, 2, 3; a, b, c)', 'galleryvideo' ); ?></label><br />
						<input type="radio" name="order" value="DESC" <?php if( $gllr_video_options["order"] == 'DESC' ) echo 'checked="checked"'; ?> /> <label class="label_radio" for="order"><?php _e( 'DESC (descending order from highest to lowest values - 3, 2, 1; c, b, a)', 'galleryvideo' ); ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Display Return link', 'galleryvideo' ); ?> </th>
					<td>
						<input type="checkbox" name="return_link" value="1" <?php if( $gllr_video_options["return_link"] == 1 ) echo 'checked="checked"'; ?> />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Display Return link in shortcode', 'galleryvideo' ); ?> </th>
					<td>
						<input type="checkbox" name="return_link_shortcode" value="1" <?php if( $gllr_video_options["return_link_shortcode"] == 1 ) echo 'checked="checked"'; ?> />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Label for Return link', 'galleryvideo' ); ?> </th>
					<td>
						<input type="text" name="return_link_text" value="<?php echo $gllr_video_options["return_link_text"]; ?>" style="width:200px;" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Label for Read More link', 'galleryvideo' ); ?> </th>
					<td>
						<input type="text" name="read_more_link_text" value="<?php echo $gllr_video_options["read_more_link_text"]; ?>" style="width:200px;" />
					</td>
				</tr>
			</table>    
			<input type="hidden" name="gllr_video_form_submit" value="submit" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			<?php wp_nonce_field( plugin_basename(__FILE__), 'gllr_video_nonce_name' ); ?>
		</form>
	</div>
	<?php } 
}

if( ! function_exists( 'gllr_video_register_plugin_links' ) ) {
	function gllr_video_register_plugin_links($links, $file) {
		$base = plugin_basename(__FILE__);
		if ($file == $base) {
			$links[] = '<a href="admin.php?page=gallery-plugin-video.php">' . __( 'Settings', 'galleryvideo' ) . '</a>';
			$links[] = '<a href="http://wordpress.org/extend/plugins/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/faq/" target="_blank">' . __( 'FAQ', 'galleryvideo' ) . '</a>';
			$links[] = '<a href="Mailto:zhouyibhic@gmail.com">' . __( 'Support', 'galleryvideo' ) . '</a>';
		}
		return $links;
	}
}

if( ! function_exists( 'gllr_video_plugin_action_links' ) ) {
	function gllr_video_plugin_action_links( $links, $file ) {
			//Static so we don't call plugin_basename on every plugin row.
		static $this_plugin;
		if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

		if ( $file == $this_plugin ){
				 $settings_link = '<a href="admin.php?page=gallery-plugin-video.php">' . __( 'Settings', 'galleryvideo' ) . '</a>';
				 array_unshift( $links, $settings_link );
			}
		return $links;
	} // end function gllr_video_plugin_action_links
}

if ( ! function_exists ( 'gllr_video_add_admin_script' ) ) {
	function gllr_video_add_admin_script() { ?>
		<script>
			(function($) {
						$(document).ready(function(){
								$('.gllr_video_image_block img').css('cursor', 'all-scroll' );
								$('.gllr_video_order_message').removeClass('hidden');
								var d=false;
								$( '#Upload-File .gallery' ).sortable({
											stop: function(event, ui) { 
													$('.gllr_video_order_text').removeClass('hidden');
													var g=$('#Upload-File .gallery').sortable('toArray');
													var f=g.length;
													$.each(		g,
														function( k,l ){
																var j=d?(f-k):(1+k);
																$('.gllr_video_order_text[name^="gllr_video_order_text['+l+']"]').val(j);
														}
													)
											}
								});
						});
			})(jQuery);
			</script>
		<?php }
}

if ( ! function_exists ( 'gllr_video_admin_head' ) ) {
	function gllr_video_admin_head() {
		wp_enqueue_style( 'gllr_videoStylesheet', plugins_url( 'css/stylesheet.css', __FILE__ ) );
		wp_enqueue_style( 'gllr_videoFileuploaderCss', plugins_url( 'upload/fileuploader.css', __FILE__ ) );
		wp_enqueue_script( 'jquery' );
		//wp_enqueue_script( 'jquery-ui-draggable' );
		//wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'jquery-ui-sortable' );	 
		wp_enqueue_script( 'gllr_videoFileuploaderJs', plugins_url( 'upload/fileuploader.js', __FILE__ ), array( 'jquery' ) );
	}
}

if ( ! function_exists ( 'gllr_video_wp_head' ) ) {
	function gllr_video_wp_head() {
		wp_enqueue_style( 'gllr_videoStylesheet', plugins_url( 'css/stylesheet.css', __FILE__ ) );
		wp_enqueue_style( 'gllr_videoFancyboxStylesheet', plugins_url( 'fancybox/jquery.fancybox-1.3.4.css', __FILE__ ) );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'gllr_videoFancyboxMousewheelJs', plugins_url( 'fancybox/jquery.mousewheel-3.0.4.pack.js', __FILE__ ), array( 'jquery' ) ); 
		wp_enqueue_script( 'gllr_videoFancyboxJs', plugins_url( 'fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__ ), array( 'jquery' ) ); 
	}
}

if ( ! function_exists ( 'gllr_video_shortcode' ) ) {
	function gllr_video_shortcode( $attr ) {
		extract( shortcode_atts( array(
				'id'	=> '',
				'display' => 'full'
			), $attr ) 
		);
		$args = array(
			'post_type'						=> 'galleryvideo',
			'post_status'				=> 'publish',
			'p'														=> $id,
			'posts_per_page'	=> 1
		);	
		ob_start();
		$second_query = new WP_Query( $args ); 
		$gllr_video_options = get_option( 'gllr_video_options' );
		if( $display == 'short' ) { ?>
				<div class="gallery_box">
				<ul>
				<?php 
					global $post, $wpdb, $wp_query;
					if ( $second_query->have_posts() ) : $second_query->the_post();
						$attachments	= get_post_thumbnail_id( $post->ID );
							if( empty ( $attachments ) ) {
								$attachments = get_children( 'post_parent='.$post->ID.'&post_type=attachment&post_mime_type=image&numberposts=1' );
								$id = key($attachments);
								$image_attributes = gllr_video_get_thumbimage_src( $id, 'album-thumb' );
							}
							else {
								$image_attributes = gllr_video_get_thumbimage_src( $attachments, 'album-thumb' );
							}
							?>
							<li>
								<img style="width:<?php echo $gllr_video_options['gllr_video_custom_size_px'][0][0]; ?>px;" alt="<?php echo $post->post_title; ?>" title="<?php echo $post->post_title; ?>" src="<?php echo $image_attributes[0]; ?>" />
								<div class="gallery_detail_box">
									<div><?php echo $post->post_title; ?></div>
									<div><?php echo the_excerpt_max_charlength(100); ?></div>
									<a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo $gllr_video_options["read_more_link_text"]; ?></a>
								</div>
								<div class="clear"></div>
							</li>
				<?php endif; ?>
				</ul></div>
		<?php } else { 
		if ($second_query->have_posts()) : 
			while ($second_query->have_posts()) : 
				global $post;
				$second_query->the_post(); ?>
				<div class="gallery_box_single">
					<?php the_content(); 
					$posts = get_posts(array(
						"showposts"			=> -1,
						"what_to_show"	=> "posts",
						"post_status"		=> "inherit",
						"post_type"			=> "attachment",
						"orderby"				=> $gllr_video_options['order_by'],
						"order"					=> $gllr_video_options['order'],
						//"post_mime_type"=> "image/jpeg,image/gif,image/jpg,image/png",
						"post_parent"		=> $post->ID
					));
					if( count( $posts ) > 0 ) {
						$playerroot = plugins_url().'/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/jwplayer/';
						$uploaddirs = wp_upload_dir();
						$uploadurl = $uploaddirs["baseurl"];
						$count_image_block = 0; ?>
						<div class="gallery clearfix">
							<?php foreach( $posts as $attachment ) { 
								$key = "gllr_video_image_text";
								$link_key = "gllr_video_link_url";
								$image_attributes = gllr_video_get_thumbimage_src( $attachment->ID, 'photo-thumb' );
								$image_attributes_large = gllr_video_get_thumbimage_src( $attachment->ID, 'large' );
								$image_attributes_full = gllr_video_get_thumbimage_src( $attachment->ID, 'full' );
								if( $count_image_block % $gllr_video_options['custom_image_row_count'] == 0 ) { ?>
								<div class="gllr_video_image_row">
								<?php } ?>
									<div class="gllr_video_image_block">
									<div  style="width:<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][0]+20; ?>px;" class="gllr_video_single_image_text"><?php echo get_post_meta( $attachment->ID, $key, true ); ?>&nbsp;</div>
										<p style="width:<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][0]+20; ?>px;height:<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][1]+20; ?>px;">
											<?php if( ( $url_for_link = get_post_meta( $attachment->ID, $link_key, true ) ) != "" ) { ?>
													<object id="player<?php echo $attachment->ID;?>" width="<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][0]; ?>" height="<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][1]; ?>" type="application/x-shockwave-flash" name="player<?php echo $attachment->ID;?>" data="<?php echo plugins_url().'/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/jwplayer/'?>player.swf">
													<param name="allowfullscreen" value="true">
													<param name="allowscriptaccess" value="always">
													<param name="flashvars" value="<?php echo $url_for_link?>&autostart=false&image=<?php echo $image_attributes[0]; ?>">
													</object>
												<?php } else { ?>
												<object id="player<?php echo $attachment->ID;?>" width="<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][0]; ?>" height="<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][1]; ?>" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9.0.115" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
												<param value="<?php echo plugins_url().'/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/jwplayer/'?>player.swf" name="movie">
												<param value="true" name="allowfullscreen">
												<param value="always" name="allowscriptaccess">
												<param value="file=<?php echo $uploadurl."/".get_post_meta( $attachment->ID, "_wp_attached_file", true );?>&fullscreen=true&controlbar=bottom&image=<?php echo $image_attributes[0]; ?>" name="flashvars">
												<embed width="<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][0]; ?>" height="<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][1]; ?>" flashvars="file=<?php echo $uploadurl."/".get_post_meta( $attachment->ID, "_wp_attached_file", true );?>&fullscreen=true&controlbar=bottom&image=<?php echo $image_attributes[0]; ?>" allowscriptaccess="always" allowfullscreen="true" src="<?php echo plugins_url().'/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/jwplayer/'?>player.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="player<?php echo $attachment->ID;?>">
												</object>
												 
												<?php } ?>
										</p>
										
									</div>
								<?php if($count_image_block%$gllr_video_options['custom_image_row_count'] == $gllr_video_options['custom_image_row_count']-1 ) { ?>
								</div>
								<?php } 
								$count_image_block++; 
							} 
							if($count_image_block > 0 && $count_image_block%$gllr_video_options['custom_image_row_count'] != 0) { ?>
								</div>
							<?php } ?>
							</div>
						<?php } ?>
					</div>
					<div class="clear"></div>
			<?php endwhile; 
		else: ?>
			<div class="gallery_box_single">
				<p class="not_found"><?php _e('Sorry - nothing to found.', 'galleryvideo'); ?></p>
			</div>
		<?php endif; ?>
		<?php if( $gllr_video_options['return_link_shortcode'] == 1 ) {
			global $wpdb;
			$parent = $wpdb->get_var("SELECT $wpdb->posts.ID FROM $wpdb->posts, $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'gallery-video-template.php' AND (post_status = 'publish' OR post_status = 'private') AND $wpdb->posts.ID = $wpdb->postmeta.post_id");	
		?>
		<div class="return_link"><a href="<?php echo ( !empty( $parent ) ? get_permalink( $parent ) : '' ); ?>"><?php echo $gllr_video_options['return_link_text']; ?></a></div>
		<?php } ?>
		<script type="text/javascript">
		(function($){
			$(document).ready(function(){
				$("a[rel=gallery_fancybox]").fancybox({
					'transitionIn'		: 'elastic',
					'transitionOut'		: 'elastic',
					'titlePosition' 	: 'inside',
					'speedIn'					:	500, 
					'speedOut'				:	300,
					'titleFormat'			: function(title, currentArray, currentIndex, currentOpts) {
						return '<span id="fancybox-title-inside">' + (title.length ? title + '<br />' : '') + 'Image ' + (currentIndex + 1) + ' / ' + currentArray.length + '</span><?php if( get_post_meta( $post->ID, 'gllr_video_download_link', true ) != '' ){?><br /><a href="'+$(currentOpts.orig).attr('rel')+'" target="_blank"><?php echo __('Download High resolution image', 'galleryvideo'); ?> </a><?php } ?>';
					}<?php if( $gllr_video_options['start_slideshow'] == 1 ) { ?>,
					'onComplete':	function() {
						clearTimeout(jQuery.fancybox.slider);
						jQuery.fancybox.slider=setTimeout("jQuery.fancybox.next()",<?php echo empty( $gllr_video_options['slideshow_interval'] )? 2000 : $gllr_video_options['slideshow_interval'] ; ?>);
					}<?php } ?>
				});
			});
		})(jQuery);
		</script>
	<?php }
		$gllr_video_output = ob_get_clean();
		wp_reset_query();
		return $gllr_video_output;
	}
}

if( ! function_exists( 'gllr_video_generate_attachment_metadata' ) ){
	function gllr_video_generate_attachment_metadata($attachment_id, $file ) {
		$attachment = get_post( $attachment_id );
	
		if ( preg_match('!^video/!', get_post_mime_type( $attachment )) && file_is_displayable_video($file) ) {
			$thumbimagefile = gllr_video_create_thumb($file);
			$ffmpegInstance = new ffmpeg_movie($file);
			$metadata['width'] = $ffmpegInstance->getFrameWidth();
			$metadata['height'] = $ffmpegInstance->getFrameHeight();
			list($uwidth, $uheight) = wp_constrain_dimensions($metadata['width'], $metadata['height'], 128, 96);
			$metadata['hwstring_small'] = "height='$uheight' width='$uwidth'";
	
			// Make the file path relative to the upload dir
			$metadata['file'] = _wp_relative_upload_path($thumbimagefile);
			$metadata['videofile'] = _wp_relative_upload_path($file);
			// make thumbnails and other intermediate sizes
			global $_wp_additional_image_sizes;
	
			foreach ( get_intermediate_image_sizes() as $s ) {
				$sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => FALSE );
				if ( isset( $_wp_additional_image_sizes[$s]['width'] ) )
					$sizes[$s]['width'] = intval( $_wp_additional_image_sizes[$s]['width'] ); // For theme-added sizes
				else
					$sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
				if ( isset( $_wp_additional_image_sizes[$s]['height'] ) )
					$sizes[$s]['height'] = intval( $_wp_additional_image_sizes[$s]['height'] ); // For theme-added sizes
				else
					$sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
				if ( isset( $_wp_additional_image_sizes[$s]['crop'] ) )
					$sizes[$s]['crop'] = intval( $_wp_additional_image_sizes[$s]['crop'] ); // For theme-added sizes
				else
					$sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options
			}
	
			$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );
	
			foreach ($sizes as $size => $size_data ) {
				$resized = image_make_intermediate_size( $thumbimagefile, $size_data['width'], $size_data['height'], $size_data['crop'] );
				if ( $resized )
					$metadata['sizes'][$size] = $resized;
			}
	
			// fetch additional metadata from exif/iptc
			$image_meta = wp_read_image_metadata( $thumbimagefile );
			if ( $image_meta )
				$metadata['image_meta'] = $image_meta;
			$vmeta = gllr_video_read_metadata ($file);
			if (!empty($vmeta))
				$metadata['video_meta'] = $vmeta;
		}
	
		return $metadata;
	}
}

if( ! function_exists( 'gllr_video_upload_gallery_image' ) ){
		function gllr_video_upload_gallery_image() {
				class qqUploadedFileXhr {
					/**
					 * Save the file to the specified path
					 * @return boolean TRUE on success
					 */
					function save($path) {
							$input = fopen("php://input", "r");
							$temp = tmpfile();
							$realSize = stream_copy_to_stream($input, $temp);
							fclose($input);
						 
							if ($realSize != $this->getSize()){            
									return false;
							}
					
							$target = fopen($path, "w");        
							fseek($temp, 0, SEEK_SET);
							stream_copy_to_stream($temp, $target);
							fclose($target);
					
							return true;
					}
					function getName() {
							return $_GET['qqfile'];
					}
					function getSize() {
							if (isset($_SERVER["CONTENT_LENGTH"])){
									return (int)$_SERVER["CONTENT_LENGTH"];            
							} else {
									throw new Exception('Getting content length is not supported.');
							}      
					}   
			}

			/**
			 * Handle file uploads via regular form post (uses the $_FILES array)
			 */
			class qqUploadedFileForm {  
					/**
					 * Save the file to the specified path
					 * @return boolean TRUE on success
					 */
					function save($path) {
							if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
							    return false;
							}
							return true;
					}
					function getName() {
							return $_FILES['qqfile']['name'];
					}
					function getSize() {
							return $_FILES['qqfile']['size'];
					}
			}

			class qqFileUploader {
					private $allowedExtensions = array();
					private $sizeLimit;
					private $file;

					function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
							$allowedExtensions = array_map("strtolower", $allowedExtensions);
							    
							$this->allowedExtensions = $allowedExtensions;        
							$this->sizeLimit = $sizeLimit;
							
							//$this->checkServerSettings();       

							if (isset($_GET['qqfile'])) {
							    $this->file = new qqUploadedFileXhr();
							} elseif (isset($_FILES['qqfile'])) {
							    $this->file = new qqUploadedFileForm();
							} else {
							    $this->file = false; 
							}
					}
			
					private function checkServerSettings(){        
							$postSize = $this->toBytes(ini_get('post_max_size'));
							$uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
							
							if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
							    $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
							    die("{error:'increase post_max_size and upload_max_filesize to $size'}");    
							}        
					}
			
					private function toBytes($str){
							$val = trim($str);
							$last = strtolower($str[strlen($str)-1]);
							switch($last) {
							    case 'g': $val *= 1024;
							    case 'm': $val *= 1024;
							    case 'k': $val *= 1024;        
							}
							return $val;
					}
			
					/**
					 * Returns array('success'=>true) or array('error'=>'error message')
					 */
					function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
							if (!is_writable($uploadDirectory)){
							    return "{error:'Server error. Upload directory isn't writable.'}";
							}
							
							if (!$this->file){
							    return "{error:'No files were uploaded.'}";
							}
							
							$size = $this->file->getSize();
							
							if ($size == 0) {
							    return "{error:'File is empty'}";
							}
							
							if ($size > $this->sizeLimit) {
							    return "{error:'File is too large'}";
							}
							
							$pathinfo = pathinfo($this->file->getName());
							$ext = $pathinfo['extension'];
							$filename = str_replace(".".$ext, "", $pathinfo['basename']);
							//$filename = md5(uniqid());

							if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
							    $these = implode(', ', $this->allowedExtensions);
							    return "{error:'File has an invalid extension, it should be one of $these .'}";
							}
							
							if(!$replaceOldFile){
							    /// don't overwrite previous files that were uploaded
							    while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
							        $filename .= rand(10, 99);
							    }
							}
							$filename_path = $uploadDirectory . $filename . '.' . $ext;
							$v_filename = $filename.'.'.$ext;
							if ($this->file->save($filename_path)){
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
						 			$video_thumb = gllr_video_create_thumb($filename_path);
									list($width, $height, $type, $attr) = getimagesize($video_thumb);
									$video_thumb_dir = explode(MPQ_VIDEO_GALLERY_FOLDERNAME.'/upload/files/', $video_thumb);
									$video_thumb_url = plugins_url().'/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/upload/files/'.$video_thumb_dir[1];
							    return "{success:true,width:".$width.",height:".$height.",videothumb:'".$video_thumb_url."',videofilename:'".$v_filename."'}";
							} else {
							    return "{error:'Could not save uploaded file. The upload was cancelled, or server error encountered'}";
							}
							
					}    
			}

			// list of valid extensions, ex. array("jpeg", "xml", "bmp")
			$allowedExtensions = array("mp4", "3gp", "3gp2", "wmv", "flv");
			// max file size in bytes
			$sizeLimit = 1024 * get_site_option( 'fileupload_maxk', 65000 );

			$uploader = new qqFileUploader( $allowedExtensions, $sizeLimit );
			$result = $uploader->handleUpload( plugin_dir_path( __FILE__ ).'upload/files/' );

			// to pass data through iframe you will need to encode all html tags
			echo $result;
			die(); // this is required to return a proper result
		}
}

function solveConflictWithGalleryPlugin($hook, $post_type)
{
	$retval = true;
	switch ($hook) {
		case "save_post":
			if ($post_type == "gallery")
			{
				$retval = false;
			}
			elseif ($post_type == "galleryvideo")
			{
				remove_action("save_post", "gllr_save_postdata", 1, 2);
				$retval = true;
			}
			break;
	}
	return $retval;
}

register_activation_hook( __FILE__, 'gllr_video_plugin_install' ); // activate plugin
register_uninstall_hook( __FILE__, 'gllr_video_plugin_uninstall' ); // deactivate plugin

// adds "Settings" link to the plugin action page
add_filter( 'plugin_action_links', 'gllr_video_plugin_action_links', 10, 2 );
//Additional links on the plugin page
add_filter( 'plugin_row_meta', 'gllr_video_register_plugin_links', 10, 2 );

add_action( 'admin_menu', 'add_gllr_video_admin_menu' );
add_action( 'init', 'gllr_video_plugin_init' );

add_action( 'init', 'gllr_video_post_type_images' ); // register post type

add_filter( 'rewrite_rules_array', 'gllr_video_custom_permalinks' ); // add custom permalink for gallery
add_action( 'wp_loaded', 'gllr_video_flush_rules' );

add_action( 'admin_init', 'gllr_video_admin_error' );

add_action( 'template_redirect', 'gllr_video_template_redirect' ); // add themplate for single gallery page

add_action( 'save_post', 'gllr_video_save_postdata', 0, 2 ); // save custom data from admin 

add_filter( 'nav_menu_css_class', 'gllr_video_addImageAncestorToMenu' );
add_filter( 'page_css_class', 'gllr_video_page_css_class', 10, 2 );

add_filter( 'manage_gallery_posts_columns', 'gllr_video_change_columns' );
add_action( 'manage_gallery_posts_custom_column', 'gllr_video_custom_columns', 10, 2 );

add_action( 'admin_head', 'gllr_video_add_admin_script' );
add_action( 'admin_enqueue_scripts', 'gllr_video_admin_head' );
add_action( 'wp_enqueue_scripts', 'gllr_video_wp_head' );

add_shortcode( 'print_gllr_video', 'gllr_video_shortcode' );

add_action( 'wp_ajax_gllr_video_upload_gallery_image', 'gllr_video_upload_gallery_image' );
?>