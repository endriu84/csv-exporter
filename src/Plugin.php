<?php
/**
 * Plugin main class.
 */

namespace WPDesk\CSVExporter;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use CSVExporterVendor\Psr\Log\NullLogger;
use CSVExporterVendor\WPDesk\Logger\WPDeskLoggerFactory;
use CSVExporterVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use CSVExporterVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use CSVExporterVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;

/**
 * Main plugin class. The most important flow decisions are made here.
 *
 * @codeCoverageIgnore
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {

	use LoggerAwareTrait;
	use HookableParent;

	private $cache_path;

	const DEBUG_MODE = false;

	/**
	 * @param WPDesk_Plugin_Info $plugin_info Plugin data.
	 */
	public function __construct( $plugin_info ) {
		$this->plugin_info = $plugin_info;
		parent::__construct( $this->plugin_info );
		$this->cache_path = trailingslashit($this->plugin_info->get_plugin_dir()) . 'cache';
		// $this->setLogger( $this->is_debug_mode() ? ( new WPDeskLoggerFactory() )->createWPDeskLogger() : new NullLogger() );
		$this->setLogger( new NullLogger() );
	}

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	public function hooks(): void {
		parent::hooks();

		$this->add_hookable( new Settings\Page() );
		$this->add_hookable( new UpdateListener( $this->cache_path ) );
		if ( wp_doing_ajax() ) {
			$this->add_hookable( new Settings\Ajax\ProgressActions( $this->cache_path, $this->logger ) );
			$this->add_hookable( new Settings\Ajax\DownloadAction() );
		}

		$this->hooks_on_hookable_objects();
	}

	/**
	 * Returns true when debug mode is on.
	 *
	 * @return bool
	 */
	private function is_debug_mode(): bool {
		return self::DEBUG_MODE;
	}
}
