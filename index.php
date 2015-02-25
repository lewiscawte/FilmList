<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:02
 */

$IP = __DIR__;
session_start();

require "settings.php";
require "inc/FileLoader.php";

if ( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] === "index" ) {
	$page = 'index';
	$newPage = new IndexPage();
	$newPage->execute( $page );
} else {
	$page = htmlspecialchars( $_REQUEST['page'] );
	switch ( $page ) {
		/*
		case 'login':
			$newPage = new LoginPage();
			$newPage->execute( $page );
			break;
		*/
		case 'error':
			$newPage = new ErrorPage();
			$newPage->execute( $page );
			break;
		case 'film':
			$newPage = new FilmPage();
			$newPage->execute( $page );
			break;
		case 'listfilms':
			$newPage = new ListFilms();
			$newPage->execute( $page );
			break;
		/*
		 * Admin panel pages
		 */
		case 'admin/index':
			$newPage = new AdminIndex();
			$newPage->execute( $page );
			break;
		case 'admin/addfilm':
			$newPage = new AdminAddFilm();
			$newPage->execute( $page );
			break;
		case 'admin/editfilm':
			$newPage = new AdminEditFilm();
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