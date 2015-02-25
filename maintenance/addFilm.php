<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 31/01/15
 * Time: 11:25
 */

class addFilmMaint extends autoScripts {

	// Test Data
	private $film = array( 'name' => 'The Best of Me', 'year' => '2014' );
	private $location = '/home/lewiscawte/Downloads/The Best of Me (2014) [1080p]';
	private $runtime = 118;
	private $budget = array( 'value' => '26000000', 'currency' => 'USD' );
	private $tags = 'Romance,Drama';

	public function __construct() {
		$this->doVeryLittle();
		$this->processRecordFile();
	}

	public function doVeryLittle() {
		//INSERT INTO `a2Test`.`film` (`film_id`, `film_name`, `film_active`, `film_virtlocation`, `film_year`, `film_runtime`, `film_plot`, `film_budget`, `film_budget_currency`, `film_tags`, `film_added`) VALUES (NULL, 'Two Night Stand', '1', '/home/lewiscawte/Downloads/Two Night Stand (2014) [1080p]', '2014', '86', NULL, '1600000', 'USD', 'Romance,Comedy', '2015-01-31');
		$this->getDatabase()->doQuery(
			'INSERT INTO a2Test.film
			( film_id, film_name, film_active, film_virtlocation, film_year, film_runtime, film_plot, film_budget, film_budget_currency, film_tags, film_added )
			VALUES ( NULL, ' . $this->film['name'] . ',1,' . $this->location . ',' . $this->film['year'] . ',' . $this->runtime . ',NULL,' . $this->budget['value'] . ',' . $this->budget['currency'] . ',' . $this->tags . ',' . date( 'Y-m-d H:i:s' ) . ');' );
	}

	public function processRecordFile() {
		$recordFile = new RecordFile( 7 );
		$recordFile->constructRecordData( $this->getDatabase()->doQuery( 'SELECT * FROM film where film_id=7;' )->fetch_assoc() );
	}
}