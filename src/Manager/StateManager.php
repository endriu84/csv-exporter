<?php

namespace WPDesk\CSVExporter\Manager;


class StateManager {

	private $status;

	private $chunk_size;

	private $chunk_page;

	const STATUS_READY = 'ready';
	const STATUS_IN_PROGRESS = 'in-progress';
	const STATUS_EMPTY = 'empty';

	const DEFAULT_CHUNK_SIZE = 3;
	const FIRST_CHUNK_PAGE = 1;

	const OPTION_PROGRESS_INFO = '_wp_desk_exporter_progress_info';

	public function __construct() {

		$options = get_option( self::OPTION_PROGRESS_INFO, [] );
		$this->status = isset( $options['status'] ) && in_array( $options['status'], [ self::STATUS_EMPTY, self::STATUS_IN_PROGRESS, self::STATUS_READY ] ) ? $options['status'] : self::STATUS_EMPTY;
		$this->chunk_page = isset( $options['chunk_page'] ) ? absint( $options['chunk_page'] ) : self::FIRST_CHUNK_PAGE;
		$this->chunk_size = isset( $options['chunk_size'] ) ? absint( $options['chunk_size'] ) : self::DEFAULT_CHUNK_SIZE;
	}

	public function get_chunk_page(): int {

		return $this->chunk_page;
	}

	public function get_chunk_size(): int {

		return $this->chunk_size;
	}

	public function get_status(): string {

		return $this->status;
	}

	public function set_ready(): void {

		$this->status = self::STATUS_READY;
		$this->chunk_page = self::FIRST_CHUNK_PAGE;
		$this->chunk_size = 0;
		$this->update();
	}

	public function set_progress(): void {

		$this->status = self::STATUS_IN_PROGRESS;
		$this->chunk_page = (int) $this->chunk_page + 1;
		$this->update();
	}

	private function update(): void {

		$options = [
			'status' => $this->status,
			'chunk_page' => $this->chunk_page
		];

		if ( $this->chunk_size ) {
			$options['chunk_size'] = $this->chunk_size;
		}

		update_option( self::OPTION_PROGRESS_INFO, $options );
	}
}
