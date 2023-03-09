<?php
/**
 * Plugin main class.
 */

namespace WPDesk\PluginTemplate;

use PluginTemplateVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use PluginTemplateVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use PluginTemplateVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Main plugin class. The most important flow decisions are made here.
 *
 * @codeCoverageIgnore
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {

	use LoggerAwareTrait;
	use HookableParent;

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	public function hooks(): void {
		parent::hooks();

		$this->hooks_on_hookable_objects();
	}
}
