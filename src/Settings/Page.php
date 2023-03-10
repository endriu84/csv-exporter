<?php

namespace WPDesk\CSVExporter\Settings;

use CSVExporterVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use CSVExporterVendor\WPDesk\PluginBuilder\Plugin\HookablePluginDependant;
use CSVExporterVendor\WPDesk\PluginBuilder\Plugin\PluginAccess;

/**
 * Supports page of plugin settings.
 */
class Page implements Hookable, HookablePluginDependant {

	use PluginAccess;

	const SETTINGS_PAGE = 'csv-exporter';

	const PAGE_ROOT_MENU = 'edit.php?post_type=product';

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ], 80 );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets_for_settings_page' ], 80 );
	}

	/**
	 * Registers admin page for plugin settings.
	 *
	 * @internal
	 */
	public function add_settings_page() {
		add_submenu_page(
			self::PAGE_ROOT_MENU,
			__( 'CSV Exporter Settings', 'fcsv-exporter' ),
			__( 'CSV Exporter', 'csv-exporter' ),
			'manage_woocommerce',
			self::SETTINGS_PAGE,
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Initiates loading of assets needed to operate admin page.
	 *
	 * @internal
	 */
	public function load_assets_for_settings_page() {
		if ( ! isset( $_GET['page'] ) || ( $_GET['page'] !== self::SETTINGS_PAGE ) ) { // phpcs:ignore
			return;
		}

		// add_filter( 'admin_footer_text', [ $this, 'update_footer_text' ] );
		$this->load_styles_for_page();
		$this->load_scripts_for_page();
	}

	public function render_settings_page()
	{
		ob_start();
		?>
			<div style="display: none;" class="csv-exporter-errors notice notice-error">
				<p></p>
			</div>
			<div class="wrap">
				<h2><?php esc_html_e( 'CSV Exporter', 'csv-exporter' ); ?></h2>
				<form action="" method="POST">
					<table class="form-table">
	                    <tr valign="top">
							<td>
								<button type="submit" class="button-primary" id="csv_exporter_button">
									<?php esc_html_e( 'Export products to CSV', 'csv-exporter' ); ?>
									<span class="spinner"></span>
								</button>
							</td>
						</tr>
					</table>
				</form>
			</div>

		<?php
		echo ob_get_clean();
	}

	/**
	 * Enqueues styles in WordPress Admin Dashboard.
	 */
	private function load_styles_for_page() {
		$is_debug = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );

		wp_register_style(
			'csv-exporter-admin',
			trailingslashit( $this->plugin->get_plugin_assets_url() ) . 'dist/app.css',
			[],
			( $is_debug ) ? time() : $this->plugin->get_script_version()
		);
		wp_enqueue_style( 'csv-exporter-admin' );
	}

	/**
	 * Enqueues scripts in WordPress Admin Dashboard.
	 */
	private function load_scripts_for_page() {
		$is_debug = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );

		wp_register_script(
			'csv-exporter-admin',
			trailingslashit( $this->plugin->get_plugin_assets_url() ) . 'dist/app.js',
			[],
			( $is_debug ) ? time() : $this->plugin->get_script_version(),
			true
		);
		wp_enqueue_media();
		wp_localize_script( 'csv-exporter-admin', 'csv_exporter', [
			'admin_url' => \esc_url( \admin_url( 'admin-ajax.php' ) ),
			'nonce' => \wp_create_nonce( 'csv-exporter-setup-admin-nonce' )
		] );
		wp_enqueue_script( 'csv-exporter-admin' );
	}
}
