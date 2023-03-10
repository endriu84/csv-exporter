<?php

namespace WPDesk\CSVExporter\Manager;


class UpdateManager {

	private $wp_filesystem;

	private $filepath;

	const FILENAME = '.outdated';

	public function __construct( string $filepath ) {

		global $wp_filesystem;

		// Make sure that the above variable is properly setup.
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();

        $this->wp_filesystem = $wp_filesystem;
		$this->filepath = trailingslashit( $filepath ) . self::FILENAME;
	}

	public function is_outdated(): bool {

		return $this->wp_filesystem->exists( $this->filepath );
	}

	public function set_outdated(): void {

		if ( $this->is_outdated() ) {
			return;
		}

		$this->wp_filesystem->put_contents( $this->filepath, '', FS_CHMOD_FILE );
	}

	public function unset_outdated(): void {

		$this->wp_filesystem->delete( $this->filepath );
	}
}
