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

		if( $filmID !== 0 && is_int( $filmID ) ) {
			$this->filmID = htmlspecialchars( $filmID );
		}
	}

	public function execute( $page ) {
		if( $this->filmID === 0 ) {
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

		$query = "SELECT * FROM film where film_id = '" . $this->filmID . "' LIMIT 1";
		$film = $this->getDatabase()->doQuery( $query )->fetch_assoc();

		$film['film_tags'] = $this->cleanTags( $film['film_tags'] );

		$template = $this->getTwig()->loadTemplate( 'film.twig' );
		$template->display( array(
			'baseurl' => $baseURL = $this->getConfig()->getSetting( 'BaseURL' )['config_value'],
			'pagetitle' => $this->getTitle( $this->thispage ),
			'film' => $film,
		) );

	}

	private function cleanTags( $tags ) {
		$cleanTags = array();
		$x = 0;

		$tags = explode( ',', $tags );
		
		foreach( $tags as $tag ) {
			$tag = htmlspecialchars( $tag );
			$x++;
			$cleanTags[$x] = $tag;
		}

		return $cleanTags;
	}
}