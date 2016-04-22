<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Templates
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/genesis/
 */

//* Remove default loop
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'genesis_404' );
/**
 * This function outputs a 404 "Not Found" error message
 *
 * @since 1.6
 */
function genesis_404() {

	echo genesis_html5() ? '<article class="entry">' : '<div class="post hentry">';

		printf( '<h1 class="entry-title">%s</h1>', apply_filters( 'genesis_404_entry_title', __( '404 ERROR</br> Looks like you&#39;ve stumbled into a mess.', 'genesis' ) ) );
		echo '<div class="entry-content">';

			if ( genesis_html5() ) :

				echo apply_filters( 'genesis_404_entry_content', '<p>' . sprintf( __( 'We apologize, but the content you’re looking for seems to be missing. Please let us know what you were looking for here , and we’ll get this cleaned up right away. </br> If you want to go back to the beginning, click <a href="%s">here</a> to go back to the homepage.', 'genesis' ), trailingslashit( home_url() ) ) . '</p>' );

				get_search_form();

			else :
	?>

			<p><?php printf( __( 'We apologize, but the content you’re looking for seems to be missing. Please let us know what you were looking for here , and we’ll get this cleaned up right away. </br></br> If you want to go back to the beginning, click <a href="%s">here</a> to go back to the homepage.', 'genesis' ), trailingslashit( home_url() ) ); ?></p>



	<?php
			endif;

			if ( ! genesis_html5() ) {
				genesis_sitemap( 'h4' );
			} elseif ( genesis_a11y( '404-page' ) ) {
				echo '<h2>' . __( 'Sitemap', 'genesis' ) . '</h2>';
				genesis_sitemap( 'h3' );
			}

			echo '</div>';

		echo genesis_html5() ? '</article>' : '</div>';

}


add_action('genesis_after_header','page_banner_404');
function page_banner_404(){ 
	if (is_404()) { ?>
		<div id="top-content">
			<div class="page-banner">
				<div class="inside"><img src="<?php bloginfo('stylesheet_directory');?>/images/workshop-logo-black.jpg">
				</div>
			</div>
		</div>
 	<?php }
}

genesis();
