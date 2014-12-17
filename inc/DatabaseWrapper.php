<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 26/11/14
 * Time: 14:24
 */

use Psr\Log\LoggerInterface;

class DatabaseWrapper {

	private $db;

	private $logger;

	public function __construct(LoggerInterface $logger = null ) {
		$this->logger = $logger;
		$sql = new mysqli(
			DB_HOST,
			DB_USER,
			DB_PASS,
			DB_NAME
		);
		$sql->select_db( DB_NAME );
		$this->db = $sql;

		unset( $sql );
	}

	public function __deconstruct() {
		$this->db->close();
	}

	public function select() {
		return $this->db->query( 'stuff' );
	}

	public function insert( $table, $fields, $values ) {
		return $this->db->query( 'INSERT INTO' . $table . '( ' . implode( ',', $fields ) . ') VALUES ( ' . implode( ',', $values ) . ' );' );
	}

	public function isFieldPopulated( $film, $field ) {
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

	public function addPropChange( $film, $field, $data ) {
		try {
			$this->db->query( 'INSERT LOW_PRIORITY INTO proposedChanges(
				`pc_filmId`,`pc_field`,`pc_value` )
				VALUES(`' . $film . '`, `' . $field . '`, `' . $data . '`);' );
		} catch( Exception $e ) {
			$this->logger->error( $e->getMessage() );
		}

	}
}