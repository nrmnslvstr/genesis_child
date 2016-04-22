<?php
/**
 * This file adds a custom archive page to any Genesis child theme.
 *
 */

add_action('genesis_after_header','category_custom_content');
function category_custom_content() {

			//* --- Create custom query for Page Title into our Category Page
			global $post, $cat;
			$args = array('post-status' => 'published','post-type' => 'post', 'orderby' => 'DESC', 'order' => 'date', 'posts_per_page' => 1, 'cat' => $cat,'meta_key' => 'featured_post','meta_value' => TRUE);
			$title_post = new WP_Query($args);
			//* --- If have a Post with this Category, display the Latest Post as Page Title
			if ($title_post->have_posts()): ?>
			 	<div id="top-content">
					<?php 
					while ($title_post->have_posts() ) : $title_post->the_post();
						//* --- Set the Featured Image URL and use it as Background Image
						$post_title_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
						//* --- Added Bootstrap Framework for responsive layout
						//* --- Custom content goes here ?>
						<div class="single-page-title-wrapper" style="background:url('<?php echo $post_title_image;?>');">
							<div class="page-title-content">
					        	<h1><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
					        	<div class="post-author">
					        		<?php 
					        		//* --- Call Global $authordata to get Post Author details
					        		global $authordata;
									$firstname = get_the_author_meta('first_name');
									$lastname = get_the_author_meta('last_name');?>
									<h5><?php echo "by ".$firstname." ".$lastname;?></h5>
								</div>
					        </div>
							<div class="title-overlay"></div>
					    </div>
				    
				    <?php 
				    endwhile; ?>
			    </div>
				<?php wp_reset_postdata();
			else: ?>
				<div id="top-content">
					<h3 style="text-align: center; padding-top: 20px;">No Featured Article to show.</h3>
				</div>
			<?php endif;
		
				
			//* --- Create custom query for Featured Article Content 3X2 Grid
			$args = array('post-status' => 'published','post-type' => 'post','orderby' => 'date','order' => 'DESC','posts_per_page' => 6, 'cat' => $cat);
			$related_post = new WP_Query( $args );
			//* --- If have a Post with this Category, display the 2nd - 7th Latest Post from this Category
			if ( $related_post->have_posts() ) : $count=0;?>
				<div class="featured-article-container container">
					<div class="row">
					<?php 
					while ( $related_post->have_posts() ) : $related_post->the_post(); $count++;
						//* --- Set the Featured Image URL and use it as Background Image
						$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
						//* --- Added Bootstrap Framework for responsive layout
						//* --- Custom content goes here ?>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<article class="featured-article" style="margin:10px 0; background:url('<?php echo $feat_image;?>');">
								<div class="overlay-wrapper">
									<a href="<?php the_permalink();?>">View</a>
								</div>
								<div class="content-wrapper">
									<div class="post-title"><h2><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2></div>
								</div>
								<div class="clearfix"></div>
							</article>
						</div>
					<?php 
					endwhile; ?>
					</div>
				</div>
			<?php 
			wp_reset_postdata();
			else: ?>
				<p style="display: block; text-align: center; padding-top: 20px;"><?php _e( 'Nothing more to show in this category.' ); ?></p>
			<?php 
			endif; 
}
genesis();