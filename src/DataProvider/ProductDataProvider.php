<?php

namespace WPDesk\CSVExporter\DataProvider;

use WPDesk\CSVExporter\Manager\StateManager;


class ProductDataProvider implements DataProviderInterface {

	private $raw_products = [];

	private $total_products = 0;

	private $state_manager;

	public function setStateManager(StateManager $state_manager) {

		$this->state_manager = $state_manager;
	}

	private function get_header(): array {
		return [
			'name' => __( 'Product name', 'csv-exporter' ),
			'categories' => __( 'Categories', 'csv-exporter' ),
			'sku' => __( 'SKU', 'csv-exporter' ),
			'price' => __( 'Price', 'csv-exporter' ),
			'regular_price' => __( 'Regular price', 'csv-exporter' )
		];
	}

	public function get_raw_data( bool $first_chunk ): array {

		// include header
		if ( $first_chunk ) {
			array_unshift( $this->raw_products, array_values( $this->get_header() ) );
		}

		return $this->raw_products;
	}

	public function get_total_products(): int {

		return $this->total_products;
	}

	public function load(): void {

		// TODO replace this heavy data load with nice and fast select
		$this->total_products = count( wc_get_products( array( 'status' => 'publish', 'limit' => 0 ) ) );

		// Get the products for the current chunk
		$args = array(
			'status' => 'publish'
		);

		if ( $this->state_manager->get_chunk_size() ) {
			$args['limit'] = $this->state_manager->get_chunk_size();
		}
		if ( $this->state_manager->get_chunk_page() ) {
			$args['page'] = $this->state_manager->get_chunk_page();
		}

		$products = wc_get_products( $args );

		if ( ! $products ) {
			return;
		}

		foreach ( $products as $product ) {
			$row = [];
			foreach ( array_keys( $this->get_header() ) as $header ) {
				switch ($header) {
					case 'name':
						$row[] = $product->get_name();
						break;
					case 'categories':
						$categories = $product->get_category_ids();
						$category_names = array();
						foreach ( $categories as $category_id ) {
							$category = get_term_by( 'id', $category_id, 'product_cat' );
							$category_names[] = $category->name;
						}
						$row[] = implode( ', ', $category_names );
						break;
					case 'sku':
						$row[] = $product->get_sku();
						break;
					case 'price':
						$row[] = $product->get_price();
						break;
					case 'regular_price':
						$row[] = $product->get_regular_price();
						break;
					default:
						throw new \Exception( __( 'Data header could not be fetched', 'csv-exporter' ) );
						break;
				}
			}

			$this->raw_products[] = $row;
		}
	}

	public function is_end_of_data(): bool {

		if ( 0 === $this->state_manager->get_chunk_size() ) {
			return true;
		}

		return (bool) ( $this->state_manager->get_chunk_size() * $this->state_manager->get_chunk_page() >= $this->total_products );
	}

}
