<?php get_header(); ?>
	<div id="container" class="content">
		<div role="main" id="content" class="content">
			<?php 
			global $post, $wp_query;
			$args = array(
				'post_type'					=> 'galleryvideo',
				'post_status'				=> 'publish',
				'name'							=> $wp_query->query_vars['name'],
				'posts_per_page'		=> 1
			);	
			$second_query = new WP_Query( $args ); 
			$gllr_video_options = get_option( 'gllr_video_options' );
			if ($second_query->have_posts()) : while ($second_query->have_posts()) : $second_query->the_post(); ?>
				<h1 class="home_page_title"><?php the_title(); ?></h1>
				<div class="gallery_video_box_single">
					<?php the_content(); 
					if (strpos(get_the_content(), "post_password") ===false) {
					$posts = get_posts(array(
						"showposts"			=> -1,
						"what_to_show"	=> "posts",
						"post_status"		=> "inherit",
						"post_type"			=> "attachment",
						"orderby"				=> $gllr_video_options['order_by'],
						"order"					=> $gllr_video_options['order'],
						"post_parent"		=> $post->ID
					));
					if( count( $posts ) > 0 ) {
						$playerroot = plugins_url().'/'.MPQ_VIDEO_GALLERY_FOLDERNAME.'/jwplayer/';
						$uploaddirs = wp_upload_dir();
						$uploadurl = $uploaddirs["baseurl"];
						$count_image_block = 0; ?>
						<div class="gallery_video clearfix">
						<script type="text/javascript" src="<?php echo $playerroot;?>jwplayer.js"></script>
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
										<div  style="width:<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][0]; ?>px;" class="gllr_video_single_image_text"><?php echo get_post_meta( $attachment->ID, $key, true ); ?>&nbsp;</div>
										<p style="width:<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][0]; ?>px;height:<?php echo $gllr_video_options['gllr_video_custom_size_px'][1][1]; ?>px;">
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
					<?php }?>
					</div>
					<div class="clear"></div>
				<?php endwhile; else: ?>
				<div class="gallery_video_box_single">
					<p class="not_found"><?php _e('Sorry - nothing to found.', 'galleryvideo'); ?></p>
				</div>
				<?php endif; ?>
				<?php if( $gllr_video_options['return_link'] == 1 ) {
					global $wpdb;
					$parent = $wpdb->get_var("SELECT $wpdb->posts.ID FROM $wpdb->posts, $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'gallery-video-template.php' AND (post_status = 'publish' OR post_status = 'private') AND $wpdb->posts.ID = $wpdb->postmeta.post_id");	
				?>
				<div class="return_link"><a href="<?php echo ( !empty( $parent ) ? get_permalink( $parent ) : '' ); ?>"><?php echo $gllr_video_options['return_link_text']; ?></a></div>
				<?php } ?>
			</div>
		</div>
	<?php get_sidebar(); ?>
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
						return '<span id="fancybox-title-inside">' + (title.length ? title + '<br />' : '') + '<?php _e( "Image ", "galleryvideo"); ?>' + (currentIndex + 1) + ' / ' + currentArray.length + '</span><?php if( get_post_meta( $post->ID, 'gllr_video_download_link', true ) != '' ){?><br /><a href="'+$(currentOpts.orig).attr('rel')+'" target="_blank"><?php echo __('Download High resolution image', 'galleryvideo'); ?> </a><?php } ?>';
					}<?php if( $gllr_video_options['start_slideshow'] == 1 ) { ?>,
					'onComplete':	function() {
						clearTimeout(jQuery.fancybox.slider);
						jQuery.fancybox.slider=setTimeout("jQuery.fancybox.next()",<?php echo empty( $gllr_video_options['slideshow_interval'] )? 2000 : $gllr_video_options['slideshow_interval'] ; ?>);
					}<?php } ?>
				});
			});
		})(jQuery);
	</script>
<?php get_footer(); ?>