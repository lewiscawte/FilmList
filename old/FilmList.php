<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 24/09/14
 * Time: 13:51
 */

class FilmList {
	public function run() {
		$flFilms = array( 'Black Hawk Down', 'Saving Private Ryan', 'A Million Ways To Die In The West', 'Jackass' );
		print( '<html>');
		print( '<head>');
		print( '	<title>Index | FilmList</title>');
		print( '</head>');
		print( '<body><div>');
		//print( var_dump( $flFilms) );
		foreach( $flFilms as $film ) {
			echo $film . '<br />';
		}
		print( '</div></body></html>');
	}

	public function details( $film ) {

		print( '<html>');
		print( '<head>');
		print( '	<title>' . $film . '| FilmList</title>');
		print( '</head>');
		print( '<body>');
		print( '<div>');
		print( 'Film Name:' . $film . '<br />' );
		print( 'Length:');
		print( '</div></body></html>');
	}
}