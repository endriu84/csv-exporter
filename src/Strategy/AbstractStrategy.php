<?php

namespace WPDesk\CSVExporter\Strategy;


abstract class AbstractStrategy {

	protected $cache_path;

	const CACHE_FILENAME = 'wc-products-cache.csv';
	const DOWNLOAD_FILENAME = 'wc-products.csv';

	public function __construct( string $cache_path ) {

		$this->cache_path = $cache_path;
	}

	public function get_cache_filepath(): string {

		return trailingslashit( $this->cache_path ) . static::CACHE_FILENAME;
	}

	public function delete_cache(): void {

		global $wp_filesystem;

		$wp_filesystem->delete( $this->get_cache_filepath() );
	}

	public function get_download_url(): string {

		$nonce = \wp_create_nonce( 'csv-exporter-download-csv-nonce' );

		return \admin_url( 'admin-ajax.php?action=download_csv&nonce=' . $nonce );
	}

	public function get_download_filename(): string {

		return static::DOWNLOAD_FILENAME;
	}

	// function to implement in child classes
	abstract public function export( array $raw_data, bool $is_first_chunk ): void;
}
