<?php
/**
 * WPSEO plugin file.
 *
 * @package WPSEO\Admin\Import\Plugins
 */

/**
 * Class WPSEO_Import_WooThemes_SEO
 *
 * Class with functionality to import Yoast SEO settings from WooThemes SEO.
 */
class WPSEO_Import_WooThemes_SEO extends WPSEO_Plugin_Importer {
	/**
	 * @var string The plugin name
	 */
	protected $plugin_name = 'WooThemes SEO';

	/**
	 * @var string Meta key, used in like clause for detect query.
	 */
	protected $meta_key = 'seo_title';

	/**
	 * Cleans up the WooThemes SEO settings.
	 *
	 * @return void
	 */
	protected function cleanup() {
		$this->cleanup_options();
		$this->cleanup_meta();
	}

	/**
	 * Imports meta values if they're applicable.
	 *
	 * @return bool Import success status.
	 */
	protected function import() {
		$clone_keys = array(
			array(
				'old_key' => 'seo_description',
				'new_key' => 'metadesc',
			),
			array(
				'old_key' => 'seo_title',
				'new_key' => 'title',
			),
			array(
				'old_key' => 'seo_noindex',
				'new_key' => 'meta-robots-noindex',
			),
			array(
				'old_key' => 'seo_follow',
				'new_key' => 'meta-robots-nofollow',
			),
		);
		return $this->meta_keys_clone( $clone_keys );
	}

	/**
	 * Removes the Woo Options from the database.
	 *
	 * @return void
	 */
	private function cleanup_options() {
		foreach ( array(
			'seo_woo_archive_layout',
			'seo_woo_single_layout',
			'seo_woo_page_layout',
			'seo_woo_wp_title',
			'seo_woo_meta_single_desc',
			'seo_woo_meta_single_key',
			'seo_woo_home_layout',
			) as $option ) {
			delete_option( $option );
		}
	}

	/**
	 * Removes the post meta fields from the database.
	 *
	 * @return void
	 */
	private function cleanup_meta() {
		foreach ( array( 'seo_follow', 'seo_noindex', 'seo_title', 'seo_description', 'seo_keywords' ) as $key ) {
			$this->cleanup_meta_key( $key );
		}
	}

	/**
	 * Removes a single meta field from the postmeta table in the database.
	 *
	 * @param string $key The meta_key to delete.
	 *
	 * @return void
	 */
	private function cleanup_meta_key( $key ) {
		$this->wpdb->query( $this->wpdb->prepare( "DELETE FROM {$this->wpdb->postmeta} WHERE meta_key = %s", $key ) );
	}

}
