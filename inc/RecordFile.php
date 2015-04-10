<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 21/01/15
 * Time: 12:22
 */

/*
 * BIG WORK IN PROGRESS
 * DO NOT USE
 * @TODO: FINISH
 */
class RecordFile extends Context {
	private $file = NULL;

	public function __construct( $film ) {
		$this->file = $this->findRecordFile( $film );
	}

	public function findRecordFile( $filmID ) {
		$query = "SELECT film_virtlocation from film where film_id = '" . $filmID . "' LIMIT 1";

		$path = $this->getDatabase()->doQuery( $query );

		if ( substr( $path, -1 ) !== '/' ) {
			$path .= '/';
		}

		$recordFile = $path . 'RecordFile.json';

		if ( file_exists( $recordFile ) ) {
			return $recordFile;
		} else {
			return false;
		}
	}

	public function constructRecordData( $data ) {
		return $this->writeRecordFile( $data );
	}

	public function writeRecordFile( $data ) {
		if ( is_array( $data ) ) {
			$fileData = json_encode( $data, JSON_PRETTY_PRINT );
		} else {
			$fileData = json_encode( implode( $data ), JSON_PRETTY_PRINT );
		}

		$fileWrite = file_put_contents( $this->file, $fileData );

		if ( $fileWrite !== false ) {
			return "Oh shit. Something went wrong!";
		} else {
			return true;
		}
	}
}