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
		// If filmid is set:
		// Set $filmID to the integer value else
		// Set $filmID to 0.
		$filmID = isset( $_REQUEST['filmid'] ) ? (int)$_REQUEST['filmid'] : 0;

		if ( $filmID !== 0 && is_int( $filmID ) ) {
			// (Rather pointless seeming) escaping of an integer.
			$this->filmID = htmlspecialchars( $filmID );
		}
	}

	public function execute( $page ) {
		// 0 is not a valid film id
		if ( $this->filmID === 0 ) {
			// Redirect to 'error' page and send the noSuchFilm status.
			Linker::doRedirect(
				array(
					// Where we go
					'error',
					// Why we go there
					'noSuchFilm'
				),
				array(
					// What film we wanted
					'reqFilm' => $this->filmID,
					// Where we came from
					'src' => $this->thispage,
				) );
		}

		// Film_details contains most details on films, so join that to get them.
		// Also join the additional tables to get file system paths and then
		// get filenames for poster, films, etc.
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

		// New Ratings object, __construct does connection
		// and selects from the database. $rating will
		// be full of ugly database output (as serialised
		// php array).
		$rating = new Ratings( $this->filmID );

		$template = $this->getTwig()->loadTemplate( 'film.twig' );
		$template->display( array(
			'baseurl' => $baseURL = $this->getConfig()->getSetting( 'BaseURL' )['config_value'],
			'pagetitle' => $this->getTitle( $this->thispage ),
			'film' => $film,
			'adaptations' => $adaptations,
			'ratings' => $rating->fetchOutput(),
		) );

	}

	/**
	 * @param $tags
	 * @return array
	 */
	private function cleanTags( $tags ) {
		// Start an array so we just add onto it.
		$cleanTags = array();
		$x = 0;

		// Data is stored in a string separated
		// by commas, so use explode to separate
		// at each delimiter (comma).
		$tags = explode( ',', $tags );

		// Go through an escape, and rebuild the array
		// to make more programmer friendly (just in case)
		foreach ( $tags as $tag ) {
			// Do some escaping in case tag is, for example,
			// malicious script elements to take over the world.
			$tag = htmlspecialchars( $tag );
			// Increase counter.
			$x++;
			// Add nice escaped tag to an array.
			$cleanTags[$x] = $tag;
		}

		return $cleanTags;
	}

	/**
	 * @param $serialInput
	 * @return array
	 */
	private function sortAdaptations( $serialInput ) {
		// $serialInput should be a serialised PHP array
		// of arrays.
		$serialInput = unserialize( $serialInput );

		// If the array isn't empty then...
		if ( !empty( $serialInput ) ) {
			$inspiration = array();
			// If the key inspiration exists in the $(un)serialInput
			if ( array_key_exists( 'inspiration', $serialInput ) ) {
				// inspiration should contain an array, run through
				// each item as $inspiration.
				foreach ( $serialInput['inspiration'] as $inspiration ) {
					// Build a (not so) shiny new array after running each through
					// a filter function to clean up and format.
					$inspiration[] = $this->adaptationTypeFilter( $inspiration );
				}
			}
			// Rinse and repeat for adapations of a film.
			// @TODO: Make this ... less repetitive by making it more modular... somehow.
			$inspired = array();
			if ( array_key_exists( 'inspired', $serialInput ) ) {
				foreach ( $serialInput['inspired'] as $inspired ) {
					$inspired[] = $this->adaptationTypeFilter( $inspired );
				}
			}

			// Rebuild a nice new, even shinier array.
			$return = array( 'from' => $inspiration, 'to' => $inspired );
		} else {
			// If it's empty, output NULL.
			$return = null;
		}

		// Dump these horrendous arrays into landfill/bottom of the sea.
		unset( $inspiration, $inspired, $serialInput );
		return $return;
	}

	/**
	 * @param $adaptation
	 * @return array|null
	 */
	private function adaptationTypeFilter( $adaptation ) {
		// Each adaptation-thingy should have a 'type' before it.
		// "Explode" this at the separator (pipe) to get this
		// so we can filter/format it.
		$adaptation = explode( '|', $adaptation );
		// Does it match one of our accepted types?
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

			// Convert to lower case and wrap in parenthesis.
			$adaptation[0] = "(" . strtolower( $adaptation[0] ) . ")";

			// Ditch any other parts of this. Shouldn't be extra details in this field.
			$adaptation = array( $adaptation[0], $adaptation[1] );
		} else {
			// No? Well we'll not show/use it then.
			$adaptation = NULL;
		}

		return $adaptation;
	}

	private function sortPathing( $input ) {
		// Format: '{storage area}:{file path relative to storage area}'
		// Example: 'default:poster.jpg'
		// Explode at delimiter to get storage area and file path differently.
		$output = explode( ':', $input );
		// We're not using $input anymore after we've split it.
		// Drop it to the bottom of the sea and/or nuke it.
		unset( $input );

		if ( $output[0] === 'default' ) {
			// Get the default area from the configuration database table and append the
			// path to wherever we're storing our default/placeholders.
			$output[0] = $this->getConfig()->getSetting( 'BaseImagePath' )['config_value']
				. '/default/';
		} elseif ( $output[0] === 'base' ) {
			// Base is the film's storage location. Get this from it's entry in the film_locations
			// database table and then append the path to it's designated hidden (at least on most
			// unix systems) folder to contain stuff related to FilmList.
			$query = $this->getDatabase()
				->doQuery( "SELECT film_base FROM film_locations WHERE film_id='" . $this->filmID . "'" )
				->fetch_assoc()['film_base'];

			$output[0] = $query . '/.filmlist/';
		} else {
			$output[0] = null;
		}

		// Add the file name or additional pathing onto this. We've got a working
		// path to the file!
		$output = $output[0] . $output[1];

		return $output;
	}
}