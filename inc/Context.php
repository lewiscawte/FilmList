<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 26/11/14
 * Time: 13:52
 */

class Context {

	public function __construct() {

	}

	/**
	 * @return Config
	 */
	public function getConfig() {
		$config = new Config();

		return $config;
	}

	/**
	 * @return DatabaseWrapper
	 */
	public function getDatabase() {
		$database = new DatabaseWrapper();

		return $database;
	}
} 