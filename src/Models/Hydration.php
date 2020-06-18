<?php

namespace Automattic\WooCommerce\Models;

class Hydration {

	protected $hydration_data = array();

	protected $post = null;

	public function set_data_for_object( $key, $object_id, $value ) {
		$data = $this->hydration_data[ $key ] ?? array();
		$data[ $object_id ] = $value;
		$this->hydration_data[ $key ] = $data;
	}

	public function set_data( $key, $values ) {
		foreach ( $values as $id => $value ) {
			$this->set_data_for_object( $key, $id, $value );
		}
	}

	public function get_data( $key ) {
		return $this->hydration_data[ $key ];
	}

	public function get_data_for_object( $key, $object_id ) {
		if ( ! isset( $this->hydration_data[ $key ] ) ) {
			return null;
		}

		if ( ! isset( $this->hydration_data[ $key ][ $object_id ] ) ) {
			return null;
		}

		return $this->hydration_data[ $key ][ $object_id ];
	}

	public function has_key( $key ) {
		return key_exists( $key, $this->hydration_data );
	}


}
