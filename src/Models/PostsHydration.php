<?php

namespace Automattic\WooCommerce\Models;

class PostsHydration extends Hydration {
	protected $hydration_data = array( 'post' => array() );

	public function set_post( $post ) {
		$this->hydration_data[ 'post' ][ $post->ID ] = $post;
	}

	public function get_post( $object_id ) {
		return $this->hydration_data[ 'post' ][ $object_id ];
	}

	public function set_raw_meta_data( $data, $index_key ) {
		$this->set_collection( $data, 'raw_meta_data', $index_key );
	}

	public function set_collection ( $data, $name, $index_key ) {
		$collection = array();
		foreach ( $data as $meta_data ) {
			$index = $meta_data->$index_key;
			if ( ! isset( $collection[ $index ] ) ) {
				$collection[ $index ] = array();
			}
			$collection[ $index ][] = $meta_data;
		}
		$this->hydration_data[ $name ] = $collection;
	}

	public function set_refunds( $refunds ) {
		$this->set_collection( $refunds, 'refunds', 'parent' );
	}

	public function get_raw_meta_data() {
		return $this->get_data( 'raw_meta_data' );
	}

	public function get_raw_meta_data_for_object( $object_id ) {
		if ( $this->has_key( 'raw_meta_data', $object_id ) ) {
			return $this->get_data( 'raw_meta_data' )[ $object_id ];
		}
		return array();
	}

}
