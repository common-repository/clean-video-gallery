<?php
/*
Template Name: Video Gallery Template
*/
?>

<?php get_header(); ?>
	<div id="container" class="content">
		<div role="main" id="content">
			<h1 class="home_page_title"><?php the_title(); ?></h1>
			<?php if ( function_exists( 'pdfprnt_show_buttons_for_custom_post_type' ) ) echo pdfprnt_show_buttons_for_custom_post_type( 'post_type=galleryvideo&orderby=post_date' ); ?>
			<div class="gallery_video_box">
				<ul id="double">
				<?php 
					global $post;
					global $wpdb;
					global $wp_query;
					$paged = $wp_query->query_vars["paged"];
					$permalink = get_permalink();
					$gllr_video_options = get_option( 'gllr_video_options' );
					if( substr( $permalink, strlen( $permalink ) -1 ) != "/" )
					{
						if( strpos( $permalink, "?" ) !== false ) {
							$permalink = substr( $permalink, 0, strpos( $permalink, "?" ) -1 )."/";
						}
						else {
							$permalink .= "/";
						}
					}
					$count = 0;
					$args = array(
						'post_type'					=> 'galleryvideo',
						'post_status'				=> 'publish',
						'orderby'						=> 'post_date',
						'posts_per_page'		=> -1
					);
					$second_query = new WP_Query( $args );
					if ( function_exists( 'pdfprnt_show_buttons_for_custom_post_type' ) ) echo pdfprnt_show_buttons_for_custom_post_type( $second_query );
					$count_all_albums = count($second_query->posts);
					$per_page = $showitems = get_option( 'posts_per_page' );  
					if( $paged != 0 )
						$start = $per_page * ($paged - 1);
					else
						$start = $per_page * $paged;
					if ($second_query->have_posts()) : while ($second_query->have_posts()) : $second_query->the_post();
						if( $count < $start ) {
							$count++;
							continue;
						}
						if( ( $count - $start ) > $per_page - 1 )
							break;

					$attachments	= get_post_thumbnail_id( $post->ID );
					if( empty ( $attachments ) ) {
						$attachments = get_children( 'post_parent='.$post->ID.'&post_type=attachment&post_mime_type=video&numberposts=1' );
						$id = key($attachments);
						$img_postparent = wp_get_post_parent_id($id);
						if (post_password_required($img_postparent))
						{
							$image_attributes = array("/wp-includes/images/passprotect.jpg", 160, 120, true);
						}
						else
						{
							$image_attributes = gllr_video_get_thumbimage_src( $id, 'album-thumb' );
						}
					}
					else {
						$image_attributes = gllr_video_get_thumbimage_src( $attachments, 'album-thumb' );
					}
					$count++;
					if ($count % 2)
						echo "<li style='float:right; clear:none; width:48%;'>";
					else
						echo "<li style='float:right; clear:none;  width:48%;'>";
					
				?>
					
						<img style="width:<?php echo $gllr_video_options['gllr_video_custom_size_px'][0][0]; ?>px;" alt="<?php echo $post->post_title; ?>" title="<?php echo $post->post_title; ?>" src="<?php echo $image_attributes[0]; ?>" />
						<div class="gallery_video_detail_box">
							<div><?php echo $post->post_title; ?></div>
							<div><?php echo the_excerpt_max_charlength(100); ?></div>
							<a href="<?php echo $permalink; echo basename( get_permalink( $post->ID ) ); ?>"><?php echo $gllr_video_options["read_more_link_text"]; ?></a>
						</div>
						<div class="clear"></div>
					</li>
				<?php endwhile; endif; wp_reset_query(); ?>
				</ul>
				<?php
					if( $paged == 0 )
							$paged = 1;
					$pages = intval ( $count_all_albums/$per_page );
					if( $count_all_albums % $per_page > 0 )
						$pages +=1;
					$range = 100;
					if( ! $pages ) {
						$pages = 1;
					}
					if( 1 != $pages ) {
						echo "</div><div class='clear'></div><div class='pagination'>";
						for ( $i = 1; $i <= $pages; $i++ ) {
							if ( 1 != $pages && ( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
								echo ( $paged == $i ) ? "<span class='current'>". $i ."</span>":"<a href='". get_pagenum_link($i) ."' class='inactive' >". $i ."</a>";
							}
						}

						echo "<div class='clear'></div></div>\n";
					} else {?>
						</div>
					<?php } ?>
		</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>