<?php
/**
 * Receipt Functions
 *
 * @since 5.0.0
 *
 * @package WooCommerce_Point_Of_Sale/Functions
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get receipt.
 *
 * @since 5.0.0
 *
 * @param int|WC_POS_Receipt $receipt Receipt ID or object.
 *
 * @throws Exception If receipt cannot be read/found and $data parameter of WC_POS_Receipt class constructor is set.
 * @return WC_POS_Receipt|null
 */
function wc_pos_get_receipt( $receipt ) {
	$receipt_object = new WC_POS_Receipt( (int) $receipt );

	// If getting the default receipt and it does not exist, create a new one and return it.
	if ( wc_pos_is_default_receipt( $receipt ) && ! $receipt_object->get_id() ) {
		delete_option( 'wc_pos_default_receipt' );
		WC_POS_Install::create_default_posts();

		return wc_pos_get_receipt( (int) get_option( 'wc_pos_default_receipt' ) );
	}

	return 0 !== $receipt_object->get_id() ? $receipt_object : null;
}

/**
 * Check if a specific receipt is the default one.
 *
 * @since 5.0.0
 *
 * @param int $receipt_id Receipt ID.
 * @return bool
 */
function wc_pos_is_default_receipt( $receipt_id ) {
	return (int) get_option( 'wc_pos_default_receipt', 0 ) === $receipt_id;
}


/**
 * Returns the itemised quantities.
 *
 * @param float $quantity
 * @param float $unit_price
 * @param string $currency
 *
 * @return array
 */
function wc_pos_get_itemised_quantity( $quantity, $unit_price, $currency ) {
	$quantity_ceil = ceil( $quantity );

	$quantities = [];
	for ( $i = 0; $i < $quantity_ceil; $i++ ) {
		$itemised_quantity = ( $i + 1 ) <= $quantity ? 1 : $quantity % 1;
		$quantities[]      = [
			'quantity' => $itemised_quantity,
			'total'    => wc_price( $itemised_quantity * $unit_price, [ 'currency' => $currency ] ),
		];
	}

	return $quantities;
}


/**
 * Builds the receipt props from an order object.
 *
 * @param int   $receipt_id Receipt ID.
 * @param order $order WC_Order.
 *
 * @return array|false Props or false on failure.
 */
function wc_pos_build_order_receipt( $receipt_id, $order ) {
	if ( ! is_a( $order, 'WC_Order' ) ) {
		return false;
	}

	$receipt = wc_pos_get_receipt( $receipt_id );

	if ( ! $receipt || ! is_a( $receipt, 'WC_POS_Receipt' ) ) {
		return false;
	}

	$register = wc_pos_get_register( (int) $order->get_meta( 'wc_pos_register_id' ) );

	if ( ! $register || ! is_a( $register, 'WC_POS_Register' ) ) {
		return false;
	}

	$register_id = $register->get_id();
	$outlet_id   = $register->get_outlet();
	$grid_id     = $register->get_grid();

	$app_data = WC_POS_App::instance()->get_app_data( $register_id, $outlet_id, $receipt_id, $grid_id );

	$signature     = $order->get_meta( 'wc_pos_signature' );
	$change        = (float) $order->get_meta( 'wc_pos_amount_change' );
	$dining_option = $order->get_meta( 'wc_dining_option' );
	$wc_price_args = [ 'currency' => $order->get_currency() ];

	$items = array_map(
		function ( $item_id, $item ) use ( $order, $app_data, $wc_price_args ) {
			/**
			 * Item's product.
			 *
			 * @var WC_Product
			 **/
			$product = $item->get_product();

			$meta_data = [];
			foreach ( $item->get_meta_data() as $meta ) {
				if (
					in_array( $meta->key, $app_data['pos']['hidden_order_itemmeta'], true )
					|| empty( $meta->value )
				) {
					continue;
				}

				$meta_data[] = [
					'key'   => ! empty( $meta->display_key ) ? $meta->display_key : $meta->key,
					'value' => $meta->value,
				];
			}

			$item_subtotal = (float) $order->get_item_subtotal( $item, false, false ); // Item cost before discounts.
			$item_total    = (float) $order->get_item_total( $item, false, false ); // Item cost after discounts.
			$line_subtotal = (float) $order->get_line_subtotal( $item, false, false ); // Total cost before discounts.
			$line_total    = (float) $order->get_line_total( $item, false, false ); // Total cost after discounts.

			$itemised_quantity = wc_pos_get_itemised_quantity( $item->get_quantity(), $item_subtotal, $order->get_currency() );
			$image             = wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' );
			$image             = $image ? $image[0] : '';

			$product_catregories = array_map(
				function ( $category ) {
					return [
						'name'      => $category->name,
						'parent'    => $category->parent,
						'children'  => get_term_children( $category->id, 'product_cat' ),
						'ancestors' => get_ancestors( $category->id, 'product_cat', 'taxonomy' ),
						'slug'      => $category->slug,
						'id'        => $category->id,
					];
				},
				wc_get_product_terms( $product->get_id(), 'product_cat' )
			);

			$item_normalized = [
				'image'              => $image,
				'item_subtotal'      => wc_price( $item_subtotal, $wc_price_args ),
				'item_total'         => wc_price( $item_total, $wc_price_args ),
				'itemised_quantity'  => $itemised_quantity,
				'line_subtotal'      => wc_price( $line_subtotal, $wc_price_args ),
				'line_total'         => wc_price( $line_total, $wc_price_args ),
				'metadata'           => $meta_data,
				'name'               => $item->get_name(),
				'product_categories' => $product_catregories,
				'product_id'         => $item->get_product_id(),
				'quantity'           => $item->get_quantity(),
				'sku'                => $product->get_sku(),
			];

			$original_price = wc_get_order_item_meta( $item_id, '_original_price', true );
			$original_price = $original_price ? (float) $original_price : $item_subtotal;

			if ( $original_price !== $item_subtotal ) {
				$item_normalized['original_price'] = wc_price( $original_price, $wc_price_args );
			}

			return $item_normalized;
		},
		array_keys( $order->get_items( 'line_item' ) ),
		$order->get_items( 'line_item' )
	);

	$order_totals = [];

	// Subtotal.
	$order_totals[] = [
		'label' => __( 'Items Subtotal', 'woocommerce-point-of-sale' ),
		'key'   => 'subtotal',
		'value' => wc_price( (float) $order->get_subtotal(), $wc_price_args ),
	];

	// Discounts.
	$discount_total = (float) $order->get_discount_total();
	if ( $discount_total ) {
		$order_totals[] = [
			'label' => __( 'Discounts & Coupons', 'woocommerce-point-of-sale' ),
			'key'   => 'discounts',
			'value' => '-' . wc_price( $discount_total, $wc_price_args ),
		];
	}

	// Fees.
	$total_fees = $order->get_total_fees();
	if ( $total_fees ) {
		$order_totals[] = [
			'label' => __( 'Fees', 'woocommerce-point-of-sale' ),
			'key'   => 'fees',
			'value' => wc_price( $total_fees, $wc_price_args ),
		];
	}

	// Shipping.
	$shipping_total = (float) $order->get_shipping_total();
	if ( $shipping_total ) {
		$order_totals[] = [
			'label' => __( 'Shipping', 'woocommerce-point-of-sale' ),
			'key'   => 'shipping',
			'value' => wc_price( $shipping_total, $wc_price_args ),
		];
	}
	// Rounding fee.
	$rounding_fee = 0;
	foreach ( $order->get_fees() as $fee ) {
		if ( 'yes' === $fee->get_meta( 'wc_pos_round_total' ) ) {
			$rounding_fee = (float) $fee->get_total();
			break;
		}
	}
	if ( $rounding_fee ) {
		$order_totals[] = [
			'label' => __( 'Rounding', 'woocommerce-point-of-sale' ),
			'key'   => 'rounding',
			'value' => wc_price( $rounding_fee, $wc_price_args ),
		];
	}

	// Total tax.
	if ( $app_data['wc']['tax_enabled'] && $order->get_total_tax() ) {
		$order_totals[] = [
			'label' => __( 'Tax', 'woocommerce-point-of-sale' ),
			'key'   => 'total_tax',
			'value' => wc_price( $order->get_total_tax(), $wc_price_args ),
		];
	}

	// Order total.
	$total          = $order->get_total();
	$order_totals[] = [
		'label' => __( 'Order Total', 'woocommerce-point-of-sale' ),
		'key'   => 'total',
		'value' => wc_price( $total, $wc_price_args ),
	];

	// Refunds.
	$total_refunded = (float) $order->get_total_refunded();
	if ( $total_refunded ) {
		$order_totals[] = [
			'label' => __( 'Refunded', 'woocommerce-point-of-sale' ),
			'key'   => 'refunds',
			'value' => $total_refunded,
		];

		// Net payment.
		$net_payment    = $total - $total_refunded;
		$order_totals[] = [
			'label' => __( 'Net Payment', 'woocommerce-point-of-sale' ),
			'key'   => 'net_payment',
			'value' => wc_price( $net_payment, $wc_price_args ),
		];
	}

	// Change.
	if ( $change ) {
		$order_totals[] = [
			'label' => __( 'Change', 'woocommerce-point-of-sale' ),
			'key'   => 'change',
			'value' => wc_price( $change, $wc_price_args ),
		];
	}

	// Items count.
	$order_totals[] = [
		'label' => __( 'Number of Items', 'woocommerce-point-of-sale' ),
		'key'   => 'items_count',
		'value' => (string) $order->get_item_count(),
	];

	$taxes = array_map(
		function ( $tax ) use ( $wc_price_args ) {
			$value = (float) $tax->get_tax_total() + (float) $tax->get_shipping_tax_total();

			return [
				'label' => $tax->get_label(),
				'rate'  => "{$tax->get_rate_percent()}%",
				'value' => wc_price( $value, $wc_price_args ),
			];
		},
		array_values( $order->get_taxes() )
	);

	$order_note_id = $order->get_meta( '_wc_pos_order_note_id' );
	$order_note    = wc_get_order_note( $order_note_id );
	$order_note    = $order_note ? $order_note->content : '';

	$order_data = [
		'order_note'           => $order_note,
		'customer_note'        => $order->get_customer_note(),
		'date_created_gmt'     => gmdate( 'Y-m-d H:i:s', $order->get_date_created()->getTimestamp() ),
		'id'                   => $order->get_id(),
		'needs_payment'        => $order->needs_payment(),
		'number'               => $order->get_order_number(),
		'payment_method'       => $order->get_payment_method(),
		'payment_method_title' => $order->get_payment_method_title(),
		'status'               => $order->get_status(),
	];

	$customer      = new WC_Customer( $order->get_customer_id() );
	$customer_data = $customer->get_data();

	$full_name     = trim( join( ' ', [ $customer_data['first_name'], $customer_data['last_name'] ] ) );
	$customer_name = $full_name ?? $customer_data['display_name'] ?? $customer_data['user_name'];

	$customer_data = [
		'name'     => $customer_name,
		'billing'  => $customer_data['billing'],
		'shipping' => $customer_data['shipping'],
	];

	$clerk    = [];
	$outlet   = $app_data['pos']['outlet'];
	$register = $app_data['pos']['register'];

	// TODO: DRY - @see WC_POS_REST_OrdersConstoller::prepare_object_for_response().
	$clerk_id   = intval( $order->get_meta( 'wc_pos_served_by', true ) );
	$clerk_name = $order->get_meta( 'wc_pos_served_by_name', true );
	$clerk      = [
		'id'            => $clerk_id,
		'display_name'  => $clerk_name,
		'user_nicename' => $clerk_name,
		'user_login'    => $clerk_name,
	];

	$clerk_userdata = get_userdata( $clerk_id );

	if ( $clerk_userdata ) {
		$clerk['display_name']  = $clerk_userdata->display_name;
		$clerk['user_nicename'] = $clerk_userdata->display_name;
		$clerk['user_login']    = $clerk_userdata->display_name;
	}

	$data = [
		'shop_name'           => $app_data['wp']['site_name'],
		'signature'           => $signature,
		'change'              => $change,
		'dining_option'       => $dining_option,
		'hold'                => false,
		'gift'                => false,
		'items'               => $items,
		'totals'              => $order_totals,
		'taxes'               => $taxes,
		'customer'            => $customer_data,
		'clerk'               => $clerk,
		'order'               => $order_data,
		'tax_number'          => $app_data['pos']['tax_number'],
		'locale'              => $app_data['wp']['locale'],
		'gmt_offset'          => $app_data['wp']['gmt_offset'],
		'address_formats'     => $app_data['wc']['address_formats'],
		'countries'           => $app_data['wc']['countries'],
		'full_name_format'    => $app_data['pos']['full_name_format'],
		'tax_enabled'         => $app_data['wc']['tax_enabled'],
		'outlet'              => $outlet,
		'placeholder_img_src' => $app_data['wc']['placeholder_img_src'],
		'register'            => [
			'name' => $register['name'],
		],
	];

	$shipping_method = $order->get_shipping_method();
	if ( $shipping_method ) {
		$data['shipping'] = [
			'method_id'    => $shipping_method, // FIXME: get shipping ID
			'method_title' => $shipping_method,
		];
	}

	$i18n    = $app_data['i18n'];
	$options = $app_data['pos']['receipt'];

	return [
		'i18n'    => $i18n,
		'data'    => $data,
		'options' => $options,
	];
}
