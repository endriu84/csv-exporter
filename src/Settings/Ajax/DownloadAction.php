<?php

namespace WPDesk\CSVExporter\Settings\Ajax;

use CSVExporterVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * This class fire ajax action for download package from URL.
 *
 * @package WPDesk\FIS\Cron\Invoices
 */
class DownloadAction implements Hookable {

	/**
	 * Fires hooks.
	 */
	public function hooks() {
		add_action( 'wp_ajax_download_csv', [ $this, 'download_csv' ] );
	}

	/**
	 * Download csv from URL.
	 *
	 * @internal Do not use outside of class.
	 */
	public function download_csv() {

		check_ajax_referer( 'csv-exporter-download-csv-nonce', 'nonce' );

		if ( ! file_exists( $filename ) ) {
			wp_die( 'File does not exists!' );
		}
		header( 'Content-Description: File Transfer' );
		header( 'Content-Type: application/octet-stream' );
		header( 'Cache-Control: no-cache, must-revalidate' );
		header( 'Expires: 0' );
		header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
		header( 'Content-Length: ' . filesize( $filename ) );
		header( 'Pragma: public' );
		flush();
		readfile( $filename );
		die();
	}

}
