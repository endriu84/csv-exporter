<?php

use WPDesk\Codeception\Tests\Acceptance\Cest\AbstractCestForPluginActivation;

class ActivationCest extends AbstractCestForPluginActivation {

	/**
	 * Deactivate plugins before tests.
	 *
	 * @param AcceptanceTester $i .
	 *
	 */
	public function _before( AcceptanceTester $i ) {
		$i->loginAsAdministrator();
		$i->amOnPluginsPage();
		$i->deactivatePlugin( $this->getPluginSlug() );
		$i->amOnPluginsPage();
		$i->deactivatePlugin( 'woocommerce' );
	}

	/**
	 * Plugin activation.
	 *
	 * @param AcceptanceTester $i .
	 *
	 */
	public function pluginActivation( AcceptanceTester $i ) {

		$i->loginAsAdministrator();

		$i->amOnPluginsPage();
		$i->seePluginDeactivated( $this->getPluginSlug() );

		$i->activateWPDeskPlugin(
			$this->getPluginSlug(),
			array( 'woocommerce' ),
			array( 'The “' . $this->getPluginTitle() . '” plugin requires activating WooCommerce plugin. Activate WooCommerce →' )
		);

	}
}
