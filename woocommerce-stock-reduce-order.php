<?php
/**
 * Plugin Name: WooCommerce Stock Reduce Order
 * Description: Reduces items in stock in order status "Awaiting payment"
 * Version: 1.0.0
 * Author: WooCommerce
 * Author URI: http://woocommerce.com/
 * Developer: Daniil Tukmakov
 * Developer URI: https://github.com/frizus/
 * Text Domain: woocommerce-stock-reduce-order
 * Domain Path: /languages
 *
 * WC requires at least: 3.2.5
 * WC tested up to: 3.2.5
 *
 * Copyright: © 2009-2015 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    exit;
}

add_action('woocommerce_checkout_update_order_meta', 'woocommerce_stock_reduce_order_woocommerce_checkout_update_order_meta');

function woocommerce_stock_reduce_order_woocommerce_checkout_update_order_meta($order_id)
{
	$order = wc_get_order($order_id);
	if (($order instanceof WC_Order) && ($order->data['created_via'] == 'checkout') && ($order->data['status'] == 'pending')) {
		wc_reduce_stock_levels($order_id);
	}
}

add_filter('woocommerce_can_reduce_order_stock', 'woocommerce_stock_reduce_order_woocommerce_can_reduce_order_stock', 10, 2);

function woocommerce_stock_reduce_order_woocommerce_can_reduce_order_stock($value, $order)
{
	if (($order instanceof WC_Order) && ($order->data['created_via'] == 'checkout') && ($order->data['status'] == 'on-hold')) {
		$value = false;
	}
	return $value;
}
