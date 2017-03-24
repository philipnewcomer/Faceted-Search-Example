<?php
namespace PhilipNewcomer\Faceted_Search_Example;

/**
 * Returns a list of the example meta fields used in this plugin.
 *
 * @return array
 */
function get_example_meta_fields() {
	return [
		'season'   => 'Season',
		'weather'  => 'Weather',
		'activity' => 'Activity',
	];
}

/**
 * Returns a list of the example taxonomies used in this plugin.
 *
 * @return array
 */
function get_example_taxonomies() {
	return [
		'category' => get_taxonomy( 'category' )->label,
		'post_tag' => get_taxonomy( 'post_tag' )->label,
	];
}
