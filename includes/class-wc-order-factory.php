<?php
/**
 * Order Factory
 *
 * The WooCommerce order factory creating the right order objects.
 *
 * @version 3.0.0
 * @package WooCommerce/Classes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Order factory class
 */
class WC_Order_Factory {

	/**
	 * Get order.
	 *
	 * @param  mixed $order_id (default: false) Order ID to get.
	 * @return WC_Order|bool
	 */
	public static function get_order( $order = false ) {
		$order_id = self::get_order_id( $order );

		if ( ! $order_id ) {
			return false;
		}

		$order_type      = WC_Data_Store::load( 'order' )->get_order_type( $order );
		$classname = self::get_class_name_for_order( $order_id, $order_type );

		try {
			return new $classname( $order_id );
		} catch ( Exception $e ) {
			wc_caught_exception( $e, __FUNCTION__, array( $order_id ) );
			return false;
		}
	}

	/**
	 * @param PostHydration $hydration
	 */
	public static function get_order_from_hydration( $order, $hydration ) {
		$order_id = self::get_order_id( $order );

		if ( ! $order_id ) {
			return false;
		}

		$order_type = WC_Data_Store::load( 'order' )->get_order_type( $order );
		$classname = self::get_class_name_for_order( $order_id, $order_type );

		// Lets create an empty object first to see if data store supports post hydration.
		// We do this code gymnastics because we don't know if data store for this class (which could be modified by a filter) supports loading from WP_Post or not.
		$order_object = new $classname( new stdClass() );
		$data_store = $order_object->get_data_store();

		try {
			$order_object->set_id( $order_id );
			$data_store->read_from_hydration( $order_object, $hydration );
			if ( $order_object->get_object_read() ) {
				return $order_object;
			} else {
				return new $classname( $order_id );
			}
		} catch ( Exception $e ) {
			wc_caught_exception( $e, __FUNCTION__, array( $order_id ) );
			return false;
		}
	}

	private static function get_class_name_for_order( $order_id, $order_type ) {
		$order_type_data = wc_get_order_type( $order_type );
		if ( $order_type_data ) {
			$classname = $order_type_data['class_name'];
		} else {
			$classname = false;
		}

		// Filter classname so that the class can be overridden if extended.
		$classname = apply_filters( 'woocommerce_order_class', $classname, $order_type, $order_id );


		if ( ! class_exists( $classname ) ) {
			return false;
		}

		return $classname;
	}

	public static function get_order_item_class( $item_id = 0 ) {
		if ( is_numeric( $item_id ) ) {
			$item_type = WC_Data_Store::load( 'order-item' )->get_order_item_type( $item_id );
			$id        = $item_id;
		} elseif ( $item_id instanceof WC_Order_Item ) {
			$item_type = $item_id->get_type();
			$id        = $item_id->get_id();
		} elseif ( is_object( $item_id ) && ! empty( $item_id->order_item_type ) ) {
			$id        = $item_id->order_item_id;
			$item_type = $item_id->order_item_type;
		} else {
			$item_type = false;
			$id        = false;
		}
		$classname = false;

		if ( $id && $item_type ) {
			switch ( $item_type ) {
				case 'line_item':
				case 'product':
					$classname = 'WC_Order_Item_Product';
					break;
				case 'coupon':
					$classname = 'WC_Order_Item_Coupon';
					break;
				case 'fee':
					$classname = 'WC_Order_Item_Fee';
					break;
				case 'shipping':
					$classname = 'WC_Order_Item_Shipping';
					break;
				case 'tax':
					$classname = 'WC_Order_Item_Tax';
					break;
			}
			$classname = apply_filters( 'woocommerce_get_order_item_classname', $classname, $item_type, $id );
		}

		return $classname;
	}

	/**
	 * Get order item.
	 *
	 * @param int $item_id Order item ID to get.
	 * @return WC_Order_Item|false if not found
	 */
	public static function get_order_item( $item_id = 0 ) {
		$classname = self::get_order_item_class( $item_id );
		if ( $classname && class_exists( $classname ) ) {
			try {
				return new $classname( $item_id );
			} catch ( Exception $e ) {
				return false;
			}
		}
		return false;
	}



	/**
	 * Get the order ID depending on what was passed.
	 *
	 * @since 3.0.0
	 * @param  mixed $order Order data to convert to an ID.
	 * @return int|bool false on failure
	 */
	public static function get_order_id( $order ) {
		global $post;

		if ( false === $order && is_a( $post, 'WP_Post' ) && 'shop_order' === get_post_type( $post ) ) {
			return absint( $post->ID );
		} elseif ( is_numeric( $order ) ) {
			return $order;
		} elseif ( $order instanceof WC_Abstract_Order ) {
			return $order->get_id();
		} elseif ( ! empty( $order->ID ) ) {
			return $order->ID;
		} else {
			return false;
		}
	}
}
