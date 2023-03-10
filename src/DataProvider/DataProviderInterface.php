<?php

namespace WPDesk\CSVExporter\DataProvider;


interface DataProviderInterface {

	public function load(): void;

	public function get_raw_data(bool $include_header): array;

	public function get_total_products(): int;

	public function is_end_of_data(): bool;
}
