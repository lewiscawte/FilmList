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

	public function __construct( LoggerInterface $logger = null ) {
		// Use our PSR logging library.
		// @TODO: Actually use this (at all/extensively)
		$this->logger = $logger;
		// Create our database connection using the database settings
		// from $IP/settings.php.
		$sql = new mysqli(
			DB_HOST,
			DB_USER,
			DB_PASS,
			DB_NAME
		);
		// USE the database.
		$sql->select_db( DB_NAME );
		$this->db = $sql;

		// Get rid of the local object. Just in case.
		unset( $sql );
	}

	public function __deconstruct() {
		// We're done with the database, close
		// our connection and let the database server
		// get some sleep.
		$this->db->close();
	}

	/**
	 * DO NOT USE - NOT COMPLETE/WORKING
	 * TODO: FIXME. It's much nicer/safer than a nasty raw query.
	 */
	public function select( $table, $vars, $conds = '', $fname = __METHOD__,
							$options = array(), $join_conds = array() ) {
		$select = $this->db->query( "SELCT '" . implode( ',', $vars ) . "' FROM " . $table . " WHERE " . $conds );

		return $select;
	}


	public function insert( $table, $fields, $values ) {
		// Query to do inserts, allowing easier programming as can feed an array or two.
		// @TODO: Check if this is used. If not, replace and/or fix function and convert.
		return $this->db->query( 'INSERT INTO' . $table . '( ' . implode( ',', $fields ) . ') VALUES ( ' . implode( ',', $values ) . ' );' );
	}

	/**
	 * Simple function to see if a film has a piece of information.
	 * To be used in maintenance/'auto' scripts (cli) scripts to
	 * see if a change should be proposed by the system (to avoid
	 * overwriting existing data) or be directly inserted.
	 *
	 * @param $film int - film_id
	 * @param $field string - name of a database column.
	 * @return bool|mysqli_result
	 */
	public function isFieldPopulated( $film, $field ) {
		$check = $this->db->query( "SELECT '" . $field . "' from film where film_id=" . $film . " LIMIT 1;" );

		// @TODO: Account for mysqli errors.
		if ( $check === ( NULL || '' ) ) {
			$check = false;
		} else {
			$check = true;
		}

		return $check;
	}

	/**
	 * Function for maintenance/auto/cli scripts, particularly cron based
	 * job system to see how many jobs available.
	 *
	 * @param $jobType string - specified type of job.
	 * 	(consider replacing with an object that returns string instead for
	 * 	sake of formatting and not throwing errors)
	 */
	public function countJobs( $jobType ) {
		if ( isset( $jobType ) ) {
			$this->db->query( 'COUNT * from jobs where job_type = ' . $jobType . ';' );
		}
	}

	/**
	 * Try to put data in the proposed changes table.
	 * Used by auto/maintenance/cli scripts when it automaticly finds
	 * data it feels should go in that field but is already populated by the
	 * user or other script.
	 *
	 * @param $film - film_id
	 * @param $field - the name of a database column.
	 * @param $data - Whatever the value of that field shall be.
	 */
	public function addPropChange( $film, $field, $data ) {
		try {
			$this->db->query( 'INSERT LOW_PRIORITY INTO proposedChanges(
				`pc_filmId`,`pc_field`,`pc_value` )
				VALUES(`' . $film . '`, `' . $field . '`, `' . $data . '`);' );
		} catch ( Exception $e ) {
			$this->logger->error( $e->getMessage() );
		}
	}

	/**
	 * Simple wrapper function for... all SQL queries.
	 * Uses the existing database object to save having to open
	 * new ones in every function, potentially duplicating code.
	 *
	 * @param $query - an SQL query.
	 * @return mysqli_result
	 */
	public function doQuery( $query ) {
		return $this->db->query( $query );
	}
}