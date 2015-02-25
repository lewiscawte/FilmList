<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 21/01/15
 * Time: 09:05
 */

class FilmPage extends Page {
	private $thispage = 'film', $filmID = 0;

	public function __construct() {
		$filmID = isset( $_REQUEST['filmid'] ) ? (int)$_REQUEST['filmid'] : 0;

		if ( $filmID !== 0 && is_int( $filmID ) ) {
			$this->filmID = htmlspecialchars( $filmID );
		}
	}

	public function execute( $page ) {
		if ( $this->filmID === 0 ) {
			Linker::doRedirect(
				array(
					'error',
					'noSuchFilm'
				),
				array(
					'reqFilm' => $this->filmID,
					'src' => $this->thispage,
				) );
		}

		// Film_details contains most details on films, so join that to get them.
		$query = "SELECT * FROM film
			JOIN film_details ON film.film_id = film_details.film_id
			JOIN film_locations ON film.film_id = film_locations.film_id
			JOIN film_media ON film.film_id = film_media.film_id
			WHERE film.film_id = '" . $this->filmID . "'";
		$film = $this->getDatabase()->doQuery( $query )->fetch_assoc();

		$film['film_tags'] = $this->cleanTags( $film['film_tags'] );

		$adaptations = $this->sortAdaptations( $film['film_adaptations'] );
		// Data should be formatted in $adaptations, empty it from $film
		unset( $film['film_adaptations'] );

		$film['film_poster'] = $this->sortPathing( $film['film_poster'] );

		$template = $this->getTwig()->loadTemplate( 'film.twig' );
		$template->display( array(
			'baseurl' => $baseURL = $this->getConfig()->getSetting( 'BaseURL' )['config_value'],
			'pagetitle' => $this->getTitle( $this->thispage ),
			'film' => $film,
			'adaptations' => $adaptations,
		) );

	}

	/**
	 * @param $tags
	 * @return array
	 */
	private function cleanTags( $tags ) {
		$cleanTags = array();
		$x = 0;

		$tags = explode( ',', $tags );

		foreach ( $tags as $tag ) {
			$tag = htmlspecialchars( $tag );
			$x++;
			$cleanTags[$x] = $tag;
		}

		return $cleanTags;
	}

	/**
	 * @param $serialInput
	 * @return array
	 */
	private function sortAdaptations( $serialInput ) {
		$serialInput = unserialize( $serialInput );

		if ( !empty( $serialInput ) ) {
			$inspiration = array();
			if ( array_key_exists( 'inspiration', $serialInput ) ) {
				foreach ( $serialInput['inspiration'] as $inspiration ) {
					$inspiration[] = $this->adaptationTypeFilter( $inspiration );
				}
			}
			$inspired = array();
			if ( array_key_exists( 'inspired', $serialInput ) ) {
				foreach ( $serialInput['inspired'] as $inspired ) {
					$inspired[] = $this->adaptationTypeFilter( $inspired );
				}
			}

			$return = array( 'from' => $inspiration, 'to' => $inspired );
		} else {
			$return = null;
		}

		unset( $inspiration, $inspired, $serialInput );
		return $return;
	}

	/**
	 * @param $adaptation
	 * @return array|null
	 */
	private function adaptationTypeFilter( $adaptation ) {
		$adaptation = explode( '|', $adaptation );
		if ( $adaptation[0] === ( 'book' || 'film' || 'toy' || 'tv' ) ) {
			switch( $adaptation[0] ) {
				case 'tv':
					$adaptation[0] = 'tv series';
					break;
				case 'toy':
					$adaptation[0] = 'toy franchise';
					break;
				default:
					break;
			}

			$adaptation[0] = "(" . strtolower( $adaptation[0] ) . ")";

			// Ditch any other parts of this. Shouldn't be extra details in this field.
			$adaptation = array( $adaptation[0], $adaptation[1] );
		} else {
			$adaptation = NULL;
		}

		return $adaptation;
	}

	private function sortPathing( $input ) {
		$output = explode( ':', $input );
		unset( $input );

		if ( $output[0] === 'default' ) {
			$output[0] = $this->getConfig()->getSetting( 'BaseImagePath' )['config_value']
				. '/default/';
		} elseif ( $output[0] === 'base' ) {
			$query = $this->getDatabase()
				->doQuery( "SELECT film_base FROM film_locations WHERE film_id='" . $this->filmID . "'" )
				->fetch_assoc()['film_base'];

			$output[0] = $query . '/.filmlist/';
		} else {
			$output[0] = null;
		}

		$output = $output[0] . $output[1];

		return $output;
	}
}