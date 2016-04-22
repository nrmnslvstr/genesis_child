<?php
/**
 * This file adds a custom archive page to any Genesis child theme.
 *
 */

add_action( 'genesis_entry_footer', 'genesis_post_meta' );
//* Add Title Banner for Single Article Page
add_action('genesis_after_header','custom_single_layout');
function custom_single_layout(){
	global $post;
	if(is_single()) {
		//* --- Create custom query for Single Post
		if (have_posts()):?>
		<div id="top-content">
			<?php 
			while (have_posts() ) : the_post();
				//* --- Set the Featured Image URL and use it as Background Image
				$post_title_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				//* --- Added Bootstrap Framework for responsive layout
				//* --- Custom content goes here ?>
				<div class="single-page-title-wrapper" style="background:url('<?php echo $post_title_image;?>');">
					<div class="page-title-content">
						<div class="post-category">
							<?php the_category(' ');?>
						</div>
			        	<h1><?php the_title();?></h1>
			        	<div class="post-author">
			        		<?php //* --- Call Global $authordata to get Post Author details
			        		global $authordata;
							$display_name = get_the_author_meta('display_name');?>
							<h5><?php echo "by $display_name";?></h5>
						</div>
			        </div>
					<div class="title-overlay"></div>
			    </div>
		    
		    <?php 
		    endwhile; ?>
	    </div>
		<?php 
		wp_reset_postdata();
		else:
		endif;

	//* --- Remove Post Title inside Content
	add_filter('genesis_post_title_text','__return_false');
	//* --- Add Related Post after Single Post Content
	add_action('genesis_after_loop','related_post');
	
	}
}

//* --- Add Related Post after Single Post Content
function related_post() {
	//* --- Create custom query for Related Post
	global $post, $cat;
	$categories = get_the_category();
	$args = array('post-status' => 'published','post-type' => 'post','orderby' => 'rand','order' => 'DESC','posts_per_page' => 3,  'category__in' => wp_get_post_categories($post->ID));
	$related_post = new WP_Query( $args );
	//* --- If have Featured Article, display. 
	if ( $related_post->have_posts() ) : ?>
		<div class="related-article">
			<div class="featured-article-container container">
				<div class="row">
				<h3>Related Articles</h3>
				<?php 
				while ( $related_post->have_posts() ) : $related_post->the_post(); 
					//* --- Set the Featured Image URL and use it as Background Image
					$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
					//* --- Added Bootstrap Framework for responsive layout
					//* --- Custom content goes here ?>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<article class="featured-article" style="margin-top:0px;margin-bottom:20px; background:url('<?php echo $feat_image;?>');">
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
		</div>
	<?php 
	wp_reset_postdata();
	else : ?>
		<p><?php _e( 'Sorry, No Related Post to show.' ); ?></p>
	<?php 
	endif; 
}
genesis();