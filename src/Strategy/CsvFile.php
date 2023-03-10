<?php

namespace WPDesk\CSVExporter\Strategy;

use League\Csv\Writer;
use League\Csv\Exception;
use WPDesk\CSVExporter\Strategy\AbstractStrategy;

class CsvFile extends AbstractStrategy {

	const CACHE_FILENAME = 'wc-products-cache.csv';
	const DOWNLOAD_FILENAME = 'wc-products.csv';

	public function export( array $data, bool $is_first_chunk ): void {

		try {
			$mode = $is_first_chunk ? 'w' : 'a+';

			$writer = Writer::createFromPath(
				$this->get_cache_filepath(),
				$mode
			);

			// TODO set all the thing related to Windows new line, encoding etc.
			$writer->setNewline("\n");
			$writer->setDelimiter(';');
			$writer->insertAll($data);

		} catch (Exception $e) {
			// We could potentially make some frendly exceptions here to print some info to the user
			// but for now lets just pass exception further
			throw new $e;
		}
	}
}
