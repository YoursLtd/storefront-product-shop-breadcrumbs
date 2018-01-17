<?php
/*
Plugin Name: Storefront Product Shop Breadcrumbs
Author: JointByte - Anthony Iacono
Version: 1.0
Text Domain: product-shop-breadcrumbs
*/

add_action('init', 'storefront_product_shop_breadcrumbs_init');

function storefront_product_shop_breadcrumbs_init()
{
	remove_action('storefront_content_top', 'woocommerce_breadcrumb', 10);
	add_action('storefront_content_top', 'storefront_product_shop_breadcrumbs_render', 10);
}

function storefront_product_shop_breadcrumbs_render()
{
	$args = wp_parse_args($args, apply_filters('woocommerce_breadcrumb_defaults', array(
		'delimiter'   => '&nbsp;&#47;&nbsp;',
		'wrap_before' => '<nav class="woocommerce-breadcrumb">',
		'wrap_after'  => '</nav>',
		'before'      => '',
		'after'       => '',
		'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
	)));

	$breadcrumbs = new WC_Breadcrumb();

	if(!empty($args['home'])) {
		$breadcrumbs->add_crumb($args['home'], apply_filters('woocommerce_breadcrumb_home_url', home_url()));
	}

	// We should add in a shop link if we are on a product detail page
	if(is_singular('product')) {
		$shop_page_url = get_permalink(woocommerce_get_page_id('shop'));
		$shop_page_title = get_the_title(woocommerce_get_page_id('shop'));

		$breadcrumbs->add_crumb($shop_page_title, $shop_page_url);
	}

	$args['breadcrumb'] = $breadcrumbs->generate();

	/**
	 * WooCommerce Breadcrumb hook
	 *
	 * @hooked WC_Structured_Data::generate_breadcrumblist_data() - 10
	 */
	do_action('woocommerce_breadcrumb', $breadcrumbs, $args);

	wc_get_template('global/breadcrumb.php', $args);
}