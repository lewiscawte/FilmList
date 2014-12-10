<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 26/11/14
 * Time: 14:24
 */

class DatabaseWrapper {

	private $db;

	public function __construct() {
		$sql = new mysqli(
			DB_HOST,
			DB_USER,
			DB_PASS,
			DB_NAME
		);
		$sql->select_db( DB_NAME );
		$this->db = $sql;
	}

	public function select() {
		return $this->db->query( 'stuff' );
	}

	protected function isFieldPopulated( $film, $field ) {
		$check = $this->db->query( "SELECT '" . $field . "' from film where film_id=" . $film ." LIMIT 1;" );

		if( $check === ( NULL || '' ) ) {
			$check = false;
		} else {
			$check = true;
		}

		return $check;
	}

	public function countJobs( $jobType ) {
		if( isset( $jobType ) ) {
			$this->db->query( 'COUNT * from jobs where job_type = ' . $jobType . ';' );
		}
	}
}	