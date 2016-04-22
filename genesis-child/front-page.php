<?php
/**
 * Front Page Template
 *
 */

//* This file handles pages, but only exists for the sake of child theme forward compatibility.
add_action('genesis_after_header','custom_front_page_content');
function custom_front_page_content() { 
	if(is_home()) { ?>
		<div id="top-content">
			<?php //* --- Use option 'show_on_front' to provide additional content in show in front Homepage settings/
			if ( 'posts' == get_option( 'show_on_front' ) ) { 
				//* --- Call Slider Revolution Shortcode
				echo do_shortcode('[rev_slider alias="Homepage Featured Article"]');
				//* --- Create custom query for Featured Article Content
				global $post;
				$args = array('post-status' => 'published','post-type' => 'post', 'orderby' => 'DESC', 'order' => 'date', 'posts_per_page' => 6,'meta_key' => 'featured_post','meta_value' => TRUE);
				$the_query = new WP_Query( $args );
				//* --- If have Featured Article, display. 
				if ( $the_query->have_posts() ) : ?>

				<div class="featured-article-container container">
					<div class="row">
					<h3>Featured Articles</h3>
					<?php while ( $the_query->have_posts() ) : $the_query->the_post();
						//* --- Set the Featured Image URL and use it as Background Image
						$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
						//* --- Added Bootstrap Framework for responsive layout
						//* --- Custom content goes here 
						?>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<article class="featured-article" style="margin: 10px 0; background:url('<?php echo $feat_image;?>');">
								<div class="overlay-wrapper">
									<a href="<?php the_permalink();?>">View</a>
								</div>
								<div class="content-wrapper">
									<div class="post-category"><?php the_category(', ');?></div>
									<div class="post-title"><h2><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2></div>
								</div>
								<div class="clearfix"></div>
							</article>
						</div>
					<?php endwhile; ?>
					</div>
				</div>

				<?php 
				wp_reset_postdata();
				else :?>
						<p><?php _e( 'No Featured Articles to show.' ); ?></p>
				<?php 
				endif; 
			} else {}?>
		</div>
	<?php 
	}
}

genesis();
