<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 24/09/14
 * Time: 13:50
 */

require_once __DIR__ . '/FilmList.php';

$fl = new FilmList();

if( !isset( $_REQUEST['film'] ) ) {
	$fl->run();
} else {
	$fl->details( $_REQUEST['film'] );
}