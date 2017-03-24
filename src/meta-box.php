<?php
namespace PhilipNewcomer\Faceted_Search_Example;

/**
 * Register meta boxes.
 *
 * Uses the CMB2 plugin (https://github.com/WebDevStudios/CMB2).
 */
add_action( 'cmb2_admin_init', function() {

	$meta_box = new_cmb2_box([
		'id'           => 'faceted-search-example',
		'title'        => 'Faceted Search Example Meta',
		'object_types' => [ 'post' ],
	]);

	foreach ( get_example_meta_fields() as $key => $label ) {
		$meta_box->add_field([
			'id'   => $key,
			'name' => $label,
			'type' => 'text',
		]);
	}
} );
