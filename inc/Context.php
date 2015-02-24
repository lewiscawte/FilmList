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

	public function getTwig() {
		global $IP;

		$loader = new Twig_Loader_Filesystem( "$IP/inc/templates" );
		$twig = new Twig_Environment( $loader, array() );

		return $twig;
	}
} 