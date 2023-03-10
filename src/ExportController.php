<?php

namespace WPDesk\CSVExporter;

use WPDesk\CSVExporter\Manager\StateManager;
use WPDesk\CSVExporter\Manager\UpdateManager;
use WPDesk\CSVExporter\Exception\UserFriendlyException;
use WPDesk\CSVExporter\Strategy\AbstractStrategy;
use WPDesk\CSVExporter\DataProvider\DataProviderInterface;


class ExportController {

	private $export_strategy;

	private $data_provider;

	private $update_manager;

	private $state_manager;

	public function __construct(
		AbstractStrategy $export_strategy,
		DataProviderInterface $data_provider,
		UpdateManager $update_manager
	) {
		$this->export_strategy = $export_strategy;
		$this->data_provider = $data_provider;
		$this->update_manager = $update_manager;

		$this->state_manager = new StateManager();
	}

	public function is_outdated() : bool {

		return $this->update_manager->is_outdated();
	}

	public function has_cache(): bool {

		return StateManager::STATUS_READY === $this->get_status() && file_exists( $this->export_strategy->get_cache_filepath() );
	}

	public function get_status(): string {

		return $this->state_manager->get_status();
	}

	public function get_download_url(): string {

		return $this->export_strategy->get_download_url();
	}

	public function get_download_filename(): string {

		return $this->export_strategy->get_download_filename();
	}

	public function get_progress(): string {

		return $this->state_manager->get_chunk_page() . ' out of ' . $this->data_provider->get_total_products();
	}

	public function export(): void {

		// not the best option, as it requires lot of unnecessery files
		// but i will leave it for now
		require_once ABSPATH . '/wp-admin/includes/class-wp-upgrader.php';

		// prevents from multiple runs at a time
		$default_release_timeout = 10; // lets also have a default lock timeout set
		if ( ! \WP_Upgrader::create_lock( 'csv_export', $default_release_timeout ) ) {
			throw new UserFriendlyException(
				__( 'Another instance is runing. Try again later', 'csv-exporter' )
			);
		}

		try {

			$this->data_provider->setStateManager($this->state_manager);
			$this->data_provider->load();

			$this->export_strategy->setStateManager($this->state_manager);

			$first_chunk = false;
			if ( StateManager::FIRST_CHUNK_PAGE === $this->state_manager->get_chunk_page() ) {
				$first_chunk = true;
				$this->update_manager->unset_outdated();
			}

			$this->export_strategy->export(
				$this->data_provider->get_raw_data( $first_chunk ),
				$first_chunk
			);

			if ( $this->data_provider->is_end_of_data() ) {
				$this->state_manager->set_ready();
			} else {
				$this->state_manager->set_progress();
			}

			\WP_Upgrader::release_lock( 'csv_export' );

		} catch ( \Exception $e ) {
			// realease the lock, and pass exception further.
			\WP_Upgrader::release_lock( 'csv_export' );
			throw $e;
		}
	}
}
