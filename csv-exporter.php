<?php
/**
 * Plugin Name: CSV Exporter
 * Plugin URI: https://www.wpdesk.net/products/csv-exporter/
 * Description: CSV Exporter
 * Version: 1.0.0
 * Author: WP Desk
 * Author URI: https://www.wpdesk.net/
 * Text Domain: csv-exporter
 * Domain Path: /lang/
 * ​
 * Requires at least: 5.8
 * Tested up to: 6.1
 * WC requires at least: 7.1
 * WC tested up to: 7.5
 * Requires PHP: 7.2
 * ​
 * Copyright 2022 WP Desk Ltd.
 * ​
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

use WPDesk\CSVExporter\Plugin;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/autoload.php';

/* THESE TWO VARIABLES CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '1.0.0';

$plugin_name        = 'CSV Exporter';
$plugin_class_name  = Plugin::class;
$plugin_text_domain = 'csv-exporter';
$product_id         = 'CSV Exporter';
$plugin_file        = __FILE__;
$plugin_dir         = __DIR__;

// todo: only for paid plugins.
$plugin_shops = [
	'default' => 'https://www.wpdesk.net/',
];

$requirements = [
	'php'          => '7.2',
	'wp'           => '5.7',
	'repo_plugins' => [
		[
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '6.6',
		],
	],
];

// todo: only for free plugins. For paid plugins use plugin-init-php52.php.
require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';
