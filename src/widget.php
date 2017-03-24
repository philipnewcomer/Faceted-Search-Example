<?php
namespace PhilipNewcomer\Faceted_Search_Example;

/**
 * Class Faceted_Search_Example_Controls
 *
 * A widget to display the user-facing faceted search controls.
 */
class Faceted_Search_Example_Controls extends \WP_Widget {

	function __construct() {
		parent::__construct( 'faceted-search-example-controls', 'Faceted Search Example Controls' );
	}

	/**
	 * Widget output function.
	 *
	 * @param array $args     The widget's general arguments.
	 * @param array $instance The widget's instance arguments.
	 */
	function widget( $args, $instance ) {

		if ( ! is_search() ) {
			return;
		}

		// Make sure ElasticPress was active for this query and has provided aggregation data.
		if ( empty( $GLOBALS['faceted_search_example_aggregations'] ) ) {
			return;
		}

		$aggregations = $GLOBALS['faceted_search_example_aggregations'];

		echo $args['before_widget'];
		echo $args['before_title'] . esc_html( $args['widget_name'] ) . $args['after_title'];
		?>

		<?php // Submits to the current page, at the #content anchor. ?>
		<form action="/#content">

			<?php
			// Include the search query as a form field here, otherwise the search query will be lost when the
			// form is submitted.
			?>
			<input type="hidden" name="s" value="<?php echo esc_attr( get_search_query() ); ?>">

			<?php
			// Display post meta aggregations.
			foreach ( get_example_meta_fields() as $meta_key => $meta_label ) : ?>
				<h3><?php echo esc_html( $meta_label ); ?></h3>
				<ul>
					<?php
					foreach ( $aggregations[ $meta_key ] as $aggregation ) {
						printf( '<li><label><input type="checkbox" name="%s" %s> %s (%s)</label></li>',
							esc_attr( sprintf( '%s[%s]', $meta_key, $aggregation['value'] ) ),
							! empty( $_GET[ $meta_key ][ $aggregation['value'] ] ) ? 'checked' : '',
							esc_html( $aggregation['value'] ),
							esc_html( $aggregation['query_count'] )
						);
					}
					?>
				</ul>
			<?php endforeach; ?>

			<?php
			// Display taxonomy aggregations.
			foreach ( get_example_taxonomies() as $taxonomy_slug => $taxonomy_label ) : ?>
				<h3><?php echo esc_html( $taxonomy_label ); ?></h3>
				<ul>
					<?php
					foreach ( $aggregations[ $taxonomy_slug ] as $aggregation ) {
						printf( '<li><label><input type="checkbox" name="%s" %s> %s (%s)</label></li>',
							esc_attr( sprintf( '%s[%s]', $taxonomy_slug, $aggregation['value'] ) ),
							! empty( $_GET[ $taxonomy_slug ][ $aggregation['value'] ] ) ? 'checked' : '',
							esc_html( $aggregation['value'] ),
							esc_html( $aggregation['query_count'] )
						);
					}
					?>
				</ul>
			<?php endforeach; ?>

			<input type="submit" value="Update">
		</form>

		<?php
		echo $args['after_widget'];
	}
}

add_action( 'widgets_init', function() {
	register_widget( __NAMESPACE__ . '\Faceted_Search_Example_Controls' );
} );
