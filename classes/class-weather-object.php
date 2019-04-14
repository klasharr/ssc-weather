<?php

class Weather_Object {

	/**
	 * @var array
	 */
	private $units = array();

	/**
	 * @var array
	 */
	private $forecast = array();

	/**
	 * @var array
	 */
	private $location = array();

	public function init( array $raw_data ) {
		$this->validate( $raw_data );

		$this->set_location( $raw_data[ 'SiteRep'][ 'DV'][ 'Location'] );
		$this->set_units( $raw_data[ 'SiteRep'][ 'Wx']['Param'] );
		$this->set_forecast( $raw_data[ 'SiteRep'][ 'DV'][ 'Location']['Period' ] );

	}

	public function validate( array $raw_data ) {

		if( empty( $raw_data[ 'SiteRep'][ 'Wx']['Param'] ) || !is_array( $raw_data[ 'SiteRep'][ 'Wx']['Param'] ) ) {
				throw new Exception( '$raw_data[ \'SiteRep\'][ \'Wx\'][\'Param\'] is empty or not an array' );
		}

		if( empty( $raw_data[ 'SiteRep'][ 'DV'] [ 'Location']['Period'] ) || !is_array( $raw_data[ 'SiteRep'][ 'DV'][ 'Location']['Period'] ) ) {
			throw new Exception( '$raw_data[ \'SiteRep\'][ \'DV\'][ \'Location\'][\'Period\'] is empty or not an array' );
		}
	}

	private function set_location( array $location ){

		$this->location = array(

			'location_id' => $location[ 'i' ],
            'lat' => $location[ 'lat' ],
            'lon' => $location[ 'lon' ],
            'name' => $location[ 'name' ],
			'country' => $location[ 'country' ],
			'continent' => $location[ 'continent' ],
			'elevation' => $location[ 'elevation'],
		);

	}

	public function get_location_value( $key ){

		if( !array_key_exists( $key, $this->location ) ) {
			throw new Exception( 'Bad key passed for location array.' );
		}

		return $this->location[ $key ];
	}

	private function set_units( array $units ){

		foreach( $units as $single_unit ) {

			$this->units[ $single_unit ['name' ] ]  = array(
				'units' => $single_unit[ 'units' ],
				'label' => $single_unit[ '$' ],
			);
		}
	}

	public function get_unit_label( $unit_letter ) {
		return $this->units[ $unit_letter ][ 'label' ];
	}

	public function get_unit_units( $unit_letter ) {

		if( $unit_letter == 'D' ) {
			return '';
		}

		return $this->units[ $unit_letter ][ 'units' ];
	}

	private function set_forecast( array $forecast ){

		$out = array();

		foreach( $forecast as $day_forecast ) {

			$date = trim( $day_forecast[ 'value' ], 'Z' );

			foreach( $day_forecast[ 'Rep'] as $segment ) {

				$minutes = null;
				$tmp = array();
				foreach( $segment as $key => $value ) {
					if( $key == '$' ) {
						$minutes = sprintf( "%02d:00", $value / 60 );
						$tmp[ 'minutes' ] = $minutes;
					} else {
						$tmp[ $this->get_unit_label( $key ) ] = $value . $this->get_unit_units( $key );
					}
				}
				$out[ $date ][ $minutes ] = $tmp;
			}
		}
		$this->forecast = $out;
	}

	function get_forecast() {
		return $this->forecast;
	}
}


/**
 *
 * [0] => Array
(
[name] => F
[units] => C
[$] => Feels Like Temperature
)

[1] => Array
(
[name] => G
[units] => mph
[$] => Wind Gust
)

[2] => Array
(
[name] => H
[units] => %
[$] => Screen Relative Humidity
)

[3] => Array
(
[name] => T
[units] => C
[$] => Temperature
)

[4] => Array
(
[name] => V
[units] =>
[$] => Visibility
)

[5] => Array
(
[name] => D
[units] => compass
[$] => Wind Direction
)

[6] => Array
(
[name] => S
[units] => mph
[$] => Wind Speed
)

[7] => Array
(
[name] => U
[units] =>
[$] => Max UV Index
)

[8] => Array
(
[name] => W
[units] =>
[$] => Weather Type
)

[9] => Array
(
[name] => Pp
[units] => %
[$] => Precipitation Probability
)

)

 */