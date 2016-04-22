<?php
/**
 * This file adds a custom archive page to any Genesis child theme.
 *
 */

if (is_date() || is_month() || is_year() || is_author()) {
	remove_all_actions( 'genesis_entry_footer' );
	remove_all_actions( 'genesis_entry_content' );
	add_action( 'genesis_entry_header', 'genesis_post_info' );

	add_filter( 'genesis_post_info', 'archive_post_meta' );
	function archive_post_meta($post_info) {
		$post_info = '[post_date]';
		return $post_info;
	}
}
	
genesis();