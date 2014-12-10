<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 24/09/14
 * Time: 14:13
 */

class IndexTemplate {
	public function execute( $allFilms ) {
		print( '<html>' );
		print( '<head>' );
		print( '	<title>Index | FilmList</title>' );
		print( '</head>' );
		print( '<body><div>' );
		foreach $flFilms as $film{
			{echo $film;
	}
	}
		print( '</div></body></html>' );
	}
} 