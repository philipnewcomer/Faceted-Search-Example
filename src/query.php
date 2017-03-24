<?php
namespace PhilipNewcomer\Faceted_Search_Example;

/**
 * Add additional query arguments to the search query.
 *
 * Adds args for meta_query, tax_query, and registered aggregations for ElasticPress.
 *
 * @param \WP_Query $query The WP_Query object.
 */
add_action( 'pre_get_posts', function( $query ) {

	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	$query->set( 'ep_integrate', true ); // Required to trigger ElasticPress integration for empty search queries.
	$query->set( 'aggs',         get_aggregation_args() );

	$query->set( 'meta_query',   get_meta_query_args() );
	$query->set( 'tax_query',    get_tax_query_args() );
} );

/**
 * Returns the aggregation query arguments.
 *
 * @return array
 */
function get_aggregation_args() {
	$aggregations = [
		'name'       => 'faceted-search-example',
		'use-filter' => true,
		'aggs'       => [],
	];

	// Taxonomy aggregations.
	foreach ( get_example_taxonomies() as $taxonomy_slug => $taxonomy_label ) {
		$aggregations['aggs'][ $taxonomy_slug ] = [
			'terms' => [
				'field' => sprintf( 'terms.%s.name.raw', $taxonomy_slug ),
				'size'  => 25,
			],
		];
	}

	// Post meta aggregations.
	foreach ( get_example_meta_fields() as $meta_key => $meta_label ) {
		$aggregations['aggs'][ $meta_key ] = [
			'terms' => [
				'field' => sprintf( 'meta.%s.raw', $meta_key ),
				'size'  => 25,
			],
		];
	}

	return $aggregations;
}

/**
 * Returns the meta query arguments.
 *
 * @return array
 */
function get_meta_query_args() {
	$meta_queries = [
		'relation' => 'AND',
	];

	foreach ( get_example_meta_fields() as $meta_key => $meta_label ) {
		if ( ! empty( $_GET[ $meta_key ] ) ) {

			// Since the query args are in the format `key1 => on, key2 => on`, etc., we need to grab the array keys,
			// not the values.
			$values = array_keys( $_GET[ $meta_key ] );

			// Run each of the values through the `sanitize_text_field()` sanitization function to make them safe.
			$values = array_map( 'sanitize_text_field', $values );

			// ElasticPress does not support 'IN' comparisons, so we need to add each value as an individual query.
			foreach ( $values as $value ) {
				$meta_queries[] = [
					'key'   => $meta_key,
					'value' => $value,
				];
			}
		}
	}

	return $meta_queries;
}

/**
 * Returns the taxonomy query arguments.
 *
 * @return array
 */
function get_tax_query_args() {
	$tax_queries = [
		'relation' => 'AND',
	];

	foreach ( get_example_taxonomies() as $taxonomy_slug => $taxonomy_label ) {
		if ( ! empty( $_GET[ $taxonomy_slug ] ) ) {

			// Since the query args are in the format `key1 => on, key2 => on`, etc., we need to grab the array keys,
			// not the values.
			$values = array_keys( $_GET[ $taxonomy_slug ] );

			// Run each of the values through the `sanitize_text_field()` sanitization function to make them safe.
			$values = array_map( 'sanitize_text_field', $values );

			// ElasticPress does not support 'IN' comparisons, so we need to add each value as an individual query.
			foreach ( $values as $value ) {
				$tax_queries[] = [
					'taxonomy' => $taxonomy_slug,
					'field'    => 'name',
					'terms'    => [ $value ],
				];
			}
		}
	}

	return $tax_queries;
}
