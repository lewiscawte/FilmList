<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:08
 */

class Config extends Context {

	private $sql;

	public function __construct() {
		$sql = new mysqli(
			DB_HOST,
			DB_USER,
			DB_PASS,
			DB_NAME
		);
		$this->sql = $sql;
	}

	protected function getSession() {
		$session = new SessionHandler();

		return $session;
	}

	/**
	 * @param $setting
	 * @return bool|mysqli_result
	 */
	public function getSetting( $setting ) {
		$confVar = $this->sql->query( "SELECT 'config_value' FROM config where config_item='" . $setting . "';" );
		return $confVar;
	}
}