<?php
namespace PhilipNewcomer\Faceted_Search_Example;

/**
 * Store the aggregations returned by ElasticPress in a global variable so we can access them later.
 *
 * @param array  $aggregations The aggregations from the Elasticsearch response.
 * @param array  $args         The Elasticsearch query arguments.
 * @param string $scope        The current scope.
 * @param array  $query_args   The WP_Query arguments.
 */
add_action( 'ep_retrieve_aggregations', function( $aggregations, $args, $scope, $query_args ) {

	if ( ! empty( $aggregations ) ) {
		$GLOBALS['faceted_search_example_aggregations'] = format_aggregation_data( $aggregations );
	}
}, 10, 4 );

/**
 * Build a global aggregation bucket in addition to the regular buckets.
 *
 * @param array $formatted_args The Elasticsearch formatted arguments.
 * @param array $args           The WP_Query arguments.
 */
add_filter( 'ep_formatted_args', function( $formatted_args, $args ) {

	if ( empty( $formatted_args['aggs'] ) ) {
		return $formatted_args;
	}

	$global_aggregation = [
		'global' => new \stdClass(),
		'aggs' => [
			'filtered' => [
				'filter' => [
					'bool' => [
						'must' => [],
					],
				],
				'aggs' => $formatted_args['aggs']['faceted-search-example']['aggs'],
			],
		],
	];

	// Copy all of the regular aggregation filters to the global aggregation, except for those that match one of our
	// custom facet filters, as that would restrict the global aggregation counts to only those from the current query.
	foreach ( $formatted_args['aggs']['faceted-search-example']['filter']['bool']['must'] as $filter_to_copy ) {
		$should_copy = true;

		foreach ( $formatted_args['aggs']['faceted-search-example']['aggs'] as $aggregation_filter ) {
			if ( empty( $aggregation_filter['terms']['field'] ) ) {
				continue;
			}

			if ( array_key_exists_recursive( $aggregation_filter['terms']['field'], $filter_to_copy ) ) {
				$should_copy = false;
			}
		}

		if ( $should_copy ) {
			$global_aggregation['aggs']['filtered']['filter']['bool']['must'][] = $filter_to_copy;
		}
	}

	$formatted_args['aggs'] = [
		'all'      => $global_aggregation,
		'filtered' => $formatted_args['aggs']['faceted-search-example'],
	];

	return $formatted_args;
}, 10, 2 );
