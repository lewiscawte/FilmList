<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 26/02/15
 * Time: 08:33
 */

class Ratings extends Context {
	public $ratings = null;

	public function __construct( $filmID ) {
		$this->ratings = $this->getDatabase()->doQuery( "SELECT * FROM rating WHERE rating_film_id = '" . $filmID . "'" );
	}

	public function fetchOutput() {
		$review = array();

		foreach( $this->ratings as $rating ) {
			$review[] = array(
				'user' => htmlspecialchars( $rating['rating_user'] ),
				'score' => (float)$rating['rating_score'],
				'text' => htmlspecialchars( $rating['rating_text'] ),
			);
		}

		unset( $this->ratings );

		return $review;
	}
}