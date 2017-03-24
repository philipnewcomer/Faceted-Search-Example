<?php
namespace PhilipNewcomer\Faceted_Search_Example;

/**
 * Recursively searches an array to determine whether the key exists at any level.
 *
 * @param string $key   The array key to search for.
 * @param array  $array The array to search.
 *
 * @return bool Whether the key exists somewhere in the array.
 */
function array_key_exists_recursive( $key, $array ) {

	if ( ! is_array( $array ) ) {
		return false;
	}

	if ( array_key_exists( $key, $array ) ) {
		return true;
	}

	foreach ( $array as $child_array ) {
		if ( true === call_user_func( __FUNCTION__, $key, $child_array ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Formats aggregation data by combining global item counts with query item counts.
 *
 * @param array $aggregation_data The aggregation data from the Elasticsearch response.
 *
 * @return array The formatted aggregation data.
 */
function format_aggregation_data( $aggregation_data ) {
	$formatted = [];

	foreach ( $aggregation_data['all']['filtered'] as $subaggregation_name => $subaggregation_data ) {

		if ( ! is_array( $subaggregation_data ) ) {
			continue;
		}

		$subaggregation_buckets = [];

		foreach ( $subaggregation_data['buckets'] as $bucket ) {

			$query_count = 0;
			$query_key   = array_search( $bucket['key'], wp_list_pluck( $aggregation_data['filtered'][ $subaggregation_name ]['buckets'], 'key' ) );

			if ( false !== $query_key ) {
				$query_count = $aggregation_data['filtered'][ $subaggregation_name ]['buckets'][ $query_key ]['doc_count'];
			}

			$subaggregation_buckets[] = [
				'value'       => $bucket['key'],
				'total_count' => $bucket['doc_count'],
				'query_count' => $query_count,
			];
		}

		$formatted[ $subaggregation_name ] = $subaggregation_buckets;
	}

	return $formatted;
}
