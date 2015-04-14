<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 11/01/15
 * Time: 11:31
 */

class ListFilms extends Page {
	// $thispage - validation / title
	// $offset - initialise to allow multiple pages.
	private $offset = 0, $thispage = 'listfilms';

	public function __construct() {
		// Get the flOffset parameter from a URL.
		// Check it's a valid integer else set it to 0.
		$flOffset = isset( $_REQUEST['flOffset'] ) ? (int)$_REQUEST['flOffset'] : 0;

		if ( $flOffset !== 0 && is_int( $flOffset ) ) {
			// @TODO: Check this escaping. Seems pointless but there must be a reason
			// for it to be here?
			$this->offset = htmlspecialchars( $flOffset );
		}
	}

	/**
	 * Lets actually do things.
	 *
	 * @param $page - string. Should be compared
	 * 	$this->thispage should we write that (doPageCheck)
	 * 	function.
	 */
	public function execute( $page ) {
		// $this->doPageCheck();
		// See how many films we should put on a single page
		// and get (returned) a constructed query with the
		// appropriate limit and offset data.
		$query = $this->queryLimit();

		// Run this query.
		$dbr = $this->getDatabase()->doQuery( $query );

		// $x - counter.
		$x = 0;
		$films = array();

		// Create a cleaner, numbered array for each film returned.
		while ( $entry = $dbr->fetch_assoc() ) {
			$x++;
			$films[$x] = $entry;
		}

		// These variables are now done with, clear their contents
		// from memory.
		unset( $entry, $x, $query );

		// Load our HTML templates using the Twig library.
		$template = $this->getTwig()->loadTemplate( 'fl.twig' );
		$template->display( array(
			// Variables we want to pass to our template.
			'baseurl' => $baseURL = $this->getConfig()->getSetting( 'BaseURL' )['config_value'],
			'pagetitle' => $this->getTitle( $this->thispage ),
			'films' => $films,
		) );
	}

	private function queryLimit() {
		// Get a Config object.
		$conf = $this->getConfig();

		// This is our bulk standard query without limits.
		$baseQuery = "SELECT * FROM film
			JOIN film_details ON film.film_id = film_details.film_id
			WHERE film_active = 1";
		// Now get our limit, defined in the database, changable by the user
		// through the admin panel.
		$limit = $conf->getSetting( 'ListLimit' );
		$limit = $limit['config_value'];

		// If we have a limit which isn't 0...
		if ( isset( $limit ) && $limit !== 0 ) {
			// And the offset isn't 0...
			if ( $this->offset !== 0 ) {
				// Apply the limit and offset our query to get later
				// records.
				$limit = "LIMIT " . $this->offset . "," . $limit;
			// Or if we don't have an offset...
			} else {
				// Show the first x number of films.
				$limit = "LIMIT " . $limit;
			}
			// Lets add our correct limit to our query.
			$query = $baseQuery . " " . $limit;

			unset( $limit );
		// We don't have a limit? Show everything!
		} else {
			// So no need to change the query, just rename it.
			// we'll clear this up later.
			$query = $baseQuery;
		}

		// All queries need their semi-colon!
		$query = $query . ";";

		// Clean up unused, fairly long string.
		unset( $baseQuery );
		return $query;
	}
}