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
		// Config might be needed to get settings without the data?
		// ... actually this probably doesn't need to be here on account
		// of the class extension which gets all the DB connection stuff.
		// @TODO: Check if remnant of old planned layout.
		$sql = new mysqli(
			DB_HOST,
			DB_USER,
			DB_PASS,
			DB_NAME
		);
		$this->sql = $sql;
	}

	public function getSession() {
		// Sessions aren't currently working.
		// $session = new FLSessionHandler();

		// return $session;
	}

	/**
	 * @param $setting
	 * @return bool|mysqli_result
	 */
	public function getSetting( $setting ) {
		// Get the requested (function parameter, $setting) config variable from the database and escape it.
		$confVar = $this->sql->query( "SELECT config_value FROM config where config_item = '"
			. $this->sql->escape_string( $setting ) . "';" );
		// Get it from the rather nasty mysqli wrapper object.
		$val = $confVar->fetch_assoc();
		// Get rid of this horrible object. MySQLi seems to output a nasty array with useless rubbish.
		unset ( $confVar );

		if ( $val === NULL ) {
			$val = 'THIS CONFIG ITEM DOES NOT EXIST IN THE DATABASE!!';
			// @TODO: Return an exception or something in place of this string.
		}

		return $val;
	}
}