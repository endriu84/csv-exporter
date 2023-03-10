<?php

namespace WPDesk\CSVExporter;

use WPDesk\CSVExporter\Manager\UpdateManager;
use CSVExporterVendor\WPDesk\PluginBuilder\Plugin\Hookable;


class UpdateListener implements Hookable {

	private $cache_path;

	public function __construct( string $cache_path ) {

		$this->cache_path = $cache_path;
	}

	public function hooks() {
		add_action( 'woocommerce_after_save_product', array( $this, 'mark_csv_as_outdated' ), 10, 2 );
        add_action( 'woocommerce_delete_product', array( $this, 'mark_csv_as_outdated' ), 10, 1 );
	}

	public function mark_csv_as_outdated() {

		$manager = new UpdateManager( $this->cache_path );
		$manager->set_outdated();
	}
}
