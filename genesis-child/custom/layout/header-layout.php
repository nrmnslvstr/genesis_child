<?php
/*
*
* All Customization for HEADER
*
*/

//*Reposition Navigation Menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header_right', 'genesis_do_nav' );

remove_action('genesis_site_title','genesis_seo_site_title');
add_action('genesis_header','genesis_seo_site_title',7);
/*Reposition Secondary Navigation*/
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
//add_action( 'genesis_header', 'genesis_do_subnav',9 );
//* Add Search Form into Header
add_action('genesis_header','add_search_form',8);
function add_search_form() {
	get_search_form();
}

add_action('genesis_header','search_toggle',9);
function search_toggle(){?>
	<button class="search-toggle" data-toggle="modal" data-target="#search-modal"><i class="fa fa-search"></i></button>
<?php }
//* Add Childtheme Settings - Social Icons into Header
//add_action('genesis_header_right','custom_social_icons',12);
add_action('genesis_header','custom_social_icons',9);
function custom_social_icons(){
	$facebook_link = genesis_get_option( 'facebook-link', 'CHILD_SETTINGS_FIELD' );
	$twitter_link = genesis_get_option( 'twitter-link', 'CHILD_SETTINGS_FIELD' );
	$gmail_link = genesis_get_option( 'gmail-link', 'CHILD_SETTINGS_FIELD' );
	$pinterest_link = genesis_get_option( 'pinterest-link', 'CHILD_SETTINGS_FIELD' );
	$linkedin_link = genesis_get_option( 'linkedin-link', 'CHILD_SETTINGS_FIELD' );
	$youtube_link = genesis_get_option( 'youtube-link', 'CHILD_SETTINGS_FIELD' );
	if ($facebook_link) {$fblink = "<li class=\"header-social-item\"><a href=\"$facebook_link\" class=\"fb-link\" target=\"_blank\">FB</a></li>";}
	if ($twitter_link) {$twitterlink = "<li class=\"header-social-item \"><a href=\"$twitter_link\" class=\"twitter-link\" target=\"_blank\">Twitter</a></li>";}
	if ($gmail_link) {$gmaillink = "<li class=\"header-social-item\"><a href=\"$gmail_link\" class=\"google-plus-link\" target=\"_blank\">Gmail</a>";}
	if ($pinterest_link) {$pinterestlink = "<li class=\"header-social-item\"><a href=\"$pinterest_link\" class=\"pinterest-link\" target=\"_blank\">Pinterest</a>";}
	if ($linkedin_link) {$linkedinlink = "<li class=\"header-social-item\"><a href=\"$linkedin_link\" class=\"linkedin-link\" target=\"_blank\">Linkedin</a>";}
	if ($youtube_link) {$youtubelink = "<li class=\"header-social-item\"><a href=\"$youtube_link\" class=\"youtube-link\" target=\"_blank\">Youtube</a>";}
	$print_social_links = $fblink . $twitterlink . $youtubelink . $gmaillink . $pinterestlink . $linkedinlink ;
	echo "<div class=\"header-social-items-wrapper\"><ul class=\"header-social-items\">$print_social_links</ul></div>";
}

//* Change Button Text into Icon Text - Fontawesome
add_filter('genesis_search_button_text','search_icon');
function search_icon(){
	return esc_attr( '&#xf002;' );
}