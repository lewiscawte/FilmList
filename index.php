<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:02
 */

require "settings.php";
require "inc/FileLoader.php";

$IP = __DIR__;

if ( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] === "index" ) {
	$page = "index";
	$newPage = new IndexPage();
	$newPage->execute( $page );
}

/*
if( !isset( $_REQUEST['film'] ) ) {
	$fl->run();
} else {
	$fl->details( $_REQUEST['film'] );
}
*/