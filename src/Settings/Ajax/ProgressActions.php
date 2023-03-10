<?php

namespace WPDesk\CSVExporter\Settings\Ajax;

use Psr\Log\LoggerInterface;
use WPDesk\CSVExporter\DataProvider\ProductDataProvider;
use WPDesk\CSVExporter\ExportController;
use WPDesk\CSVExporter\Strategy\CsvFile;
use WPDesk\CSVExporter\Manager\StateManager;
use WPDesk\CSVExporter\Manager\UpdateManager;
use WPDesk\CSVExporter\Exception\UserFriendlyException;
use CSVExporterVendor\WPDesk\PluginBuilder\Plugin\Hookable;

class ProgressActions implements Hookable
{
	private $cache_path;

	private $logger;

	public function __construct( string $cache_path, LoggerInterface $logger ) {

		$this->cache_path = $cache_path;
		$this->logger = $logger;
	}
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_run_export', [$this, 'run_export']);
	}

	/**
     * @return void
	 *
     * @internal You should not use this directly from another application
     */
	public function run_export()
	{
		check_ajax_referer( 'csv-exporter-run-admin-nonce', 'nonce' );

		try {
			$controller = new ExportController(
				new CSVFile( $this->cache_path ),
				new ProductDataProvider(),
				new UpdateManager( $this->cache_path )
			);

			$this->maybe_serve_from_cache( $controller );

			$controller->export();

			$this->maybe_serve_from_cache( $controller );

			if ( in_array( $controller->get_status(), [ StateManager::STATUS_EMPTY, StateManager::STATUS_IN_PROGRESS ] ) ) {
				// go to run_export
				\wp_send_json_success([
					'status' => $controller->get_status(),
					'action' => 'run_export',
					'nonce' => \wp_create_nonce( 'csv-exporter-run-admin-nonce' )
				]);
			}

		} catch ( UserFriendlyException $e ) {

			$this->logger->error( $e->getMessage() );
			\wp_send_json_error([
				'error' => $e->getMessage()
			]);

		} catch ( \Exception $e ) {

			$this->logger->error( $e->getMessage() );
			\wp_send_json_error([
				'error' => __( 'Unknown error occured, please check Your logs', 'csv-exporter' )
			]);
		}
	}

	private function maybe_serve_from_cache(ExportController $controller): void {

		if ( ! $controller->is_outdated() && $controller->has_cache() ) {
			// serve from cache
			\wp_send_json_success([
				'status' => $controller->get_status(),
				'href' => \esc_url( $controller->get_download_url() ),
				'download' => $controller->get_download_filename(),
			]);
		}
	}
}
