<?php
/**
 * @package Admin
 */

if ( !defined( 'WPSEO_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/**
 * Class that handles the edit boxes on taxonomy edit pages.
 */
class WPSEO_Taxonomy {

	/**
	 * Class constructor
	 */
	function __construct() {
		$options = get_wpseo_options();

		if ( is_admin() && isset( $_GET['taxonomy'] ) &&
			( !isset( $options['tax-hideeditbox-' . $_GET['taxonomy']] ) || !$options['tax-hideeditbox-' . $_GET['taxonomy']] )
		)
			add_action( $_GET['taxonomy'] . '_edit_form', array( $this, 'term_seo_form' ), 10, 1 );

		add_action( 'edit_term', array( $this, 'update_term' ), 99, 3 );

		add_action( 'init', array( $this, 'custom_category_descriptions_allow_html' ) );
		add_filter( 'category_description', array( $this, 'custom_category_descriptions_add_shortcode_support' ) );
	}

	/**
	 * Create a row in the form table.
	 *
	 * @param string $var      Variable the row controls.
	 * @param string $label    Label for the variable.
	 * @param string $desc     Description of the use of the variable.
	 * @param array  $tax_meta Taxonomy meta value.
	 * @param string $type     Type of form row to create.
	 * @param array  $options  Options to use when form row is a select box.
	 */
	function form_row( $var, $label, $desc, $tax_meta, $type = 'text', $options = array() ) {
		$val = '';
		if ( isset( $tax_meta[$var] ) && !empty( $tax_meta[$var] ) )
			$val = sxonomy, $current ),
			'index'   => __( 'Always index', 'wordpress-seo' ),
			'noindex' => __( 'Always noindex', 'wordpress-seo' ) );
		$this->form_row( 'wpseo_noindex', sprintf( __( 'Noindex this %s', 'wordpress-seo' ), $term->taxonomy ), sprintf( __( 'This %s follows the indexation rules set under Metas and Titles, you can override it here.', 'wordpress-seo' ), $term->taxonomy ), $tax_meta, 'select', $noindex_options );

		$this->form_row( 'wpseo_sitemap_include', __( 'Include in sitemap?', 'wordpress-seo' ), '', $tax_meta, 'select', array(
			"-"      => __( "Auto detect", 'wordpress-seo' ),
			"always" => __( "Always include", 'wordpress-seo' ),
			"never"  => __( "Never include", 'wordpress-seo' ),
		) );

		echo '</table>';
	}

	/**
	 * Update the taxonomy meta data on save.
	 *
	 * @param int    $term_id  ID of the term to save data for
	 * @param int    $tt_id    The taxonomy_term_id for the term.
	 * @param string $taxonomy The taxonmy the term belongs to.
	 */
	function update_term( $term_id, $tt_id, $taxonomy ) {
		$tax_meta = get_option( 'wpseo_taxonomy_meta' );

		if ( !is_array( $tax_meta[$taxonomy][$term_id] ) )
			$tax_meta[$taxonomy][$term_id] = array();

		foreach ( array( 'title', 'desc', 'metakey', 'bctitle', 'canonical', 'noindex', 'sitemap_include' ) as $key ) {
			if ( isset( $_POST['wpseo_' . $key] ) && !empty( $_POST['wpseo_' . $key] ) ) {
				$val = trim( $_POST['wpseo_' . $key] );

				if ( $key == 'canonical' )
					$val = esc_url( $val );
				else
					$val = sanitize_text_field( $val );

				$tax_meta[$taxonomy][$term_id]['wpseo_' . $key] = $val;
			} else {
				if ( isset( $tax_meta[$taxonomy][$term_id]['wpseo_' . $key] ) )
					unset( $tax_meta[$taxonomy][$term_id]['wpseo_' . $key] );
			}
		}

		update_option( 'wpseo_taxonomy_meta', $tax_meta, 99 );

		if ( defined( 'W3TC_DIR' ) && class_exists( 'W3_ObjectCache' ) ) {
			require_once W3TC_DIR . '/lib/W3/ObjectCache.php';
			$w3_objectcache = & W3_ObjectCache::instance();

			$w3_objectcache->flush();
		}
	}

	/**
	 * Allows HTML in descriptions
	 */
	function custom_category_descriptions_allow_html() {
		$filters = array(
			'pre_term_description',
			'pre_link_description',
			'pre_link_notes',
			'pre_user_description'
		);

		foreach ( $filters as $filter ) {
			remove_filter( $filter, 'wp_filter_kses' );
		}
		remove_filter( 'term_description', 'wp_kses_data' );
	}

	/**
	 * Adds shortcode support to category descriptions.
	 *
	 * @param string $desc String to add shortcodes in.
	 * @return string
	 */
	function custom_category_descriptions_add_shortcode_support( $desc ) {
		return do_shortcode( $desc );
	}

}

$wpseo_taxonomy = new WPSEO_Taxonomy();
