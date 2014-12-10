<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 26/11/14
 * Time: 14:03
 */

class autoScripts extends Context {

	protected $db;

	public function __construct() {
		$this->db = $this->getDatabase();
	}

	protected function flagAdmin( $jobDetails, $status ) {

		return $db->insert(
			'admin_notifications',
			$jobDetails['jobId'],
			$jobDetails['jobFilm'],
			$status
		);
	}
} 