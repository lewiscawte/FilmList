<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:02
 */

$IP = __DIR__;

require "settings.php";
require "inc/FileLoader.php";

if ( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] === "index" ) {
	$page = 'index';
	$newPage = new IndexPage();
	$newPage->execute( $page );
} else {
	$page = htmlspecialchars( $_REQUEST['page'] );
	switch ( $page ) {
		case 'error':
			$newPage = new ErrorPage();
			$newPage->execute( $page );
			break;
		case 'listfilms':
			$newPage = new ListFilms();
			$newPage->execute( $page );
			break;
		default:
			Linker::doRedirect(
				array(
					'error',
					'exceptionPoorPage'
				),
				array (
					'page' => $page,
					'src' => 'IndexFile',
				)
			);
			break;
	}
}