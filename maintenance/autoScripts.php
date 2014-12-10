<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 26/11/14
 * Time: 14:03
 */

class autoScripts extends Context {

	protected $db;
	protected $config;

	public function __construct() {
		$this->db = $this->getDatabase();
		$this->config = $this->getConfig();
	}

	/**
	 * @param $jobDetails
	 * @param $status
	 * Status codes:
	 * 1 - Verified data sources do not contain the information the
	 *     script is searching for.
	 * 2 - Other enabled data sources do not contain the requested
	 *     data.
	 * 3 - Data found but already data in the main field. Data
	 *     added to the proposal table for admin review.
	 * 4 - Data added directly to the field as no data found.
	 * @return mixed
	 */
	protected function flagAdmin( $jobDetails, $status ) {

		return $this->db->insert(
			'admin_notifications',
			$jobDetails['jobId'],
			$jobDetails['jobFilm'],
			$status
		);
	}
} 