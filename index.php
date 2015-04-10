<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:02
 */

// Create a variable with the file path to the root of the project
// so addresses can be written relatively.
$IP = __DIR__;
// Probably useless, alpha testing for the login feature showed that
// session details weren't being set.
session_start();

// Load our information for database connections set in the installer.
require "settings.php";
// Load all of our main classes ready to be used.
require "inc/FileLoader.php";

// Special case for index which should show if there is no page parameter set
// i.e default index page is expected.
if ( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] === "index" ) {
	$page = 'index';
	$newPage = new IndexPage();
	$newPage->execute( $page );
} else {
	// Who knows what crazy things you could be trying to access.
	$page = htmlspecialchars( $_REQUEST['page'] );
	// All of our valid pages.
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
		// You don't match any of the above? Well go and see our
		// friend the error page.
		default:
			Linker::doRedirect(
				array(
					'error', // Go to error page
					'exceptionPoorPage' // Why?
				),
				array (
					'page' => $page, // What page did you want?
					'src' => 'IndexFile', // Who sent you?
				)
			);
			break;
	}
}