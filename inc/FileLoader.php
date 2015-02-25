<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 19/11/14
 * Time: 14:04
 */

// "Core"
require_once "$IP/inc/Context.php";
require_once "$IP/inc/Config.php";
require_once "$IP/inc/DatabaseWrapper.php";
require_once "$IP/inc/Linker.php";

// Pages
require_once "$IP/inc/page/Page.php";
require_once "$IP/inc/page/IndexPage.php";
require_once "$IP/inc/page/LoginPage.php";
require_once "$IP/inc/page/FilmPage.php";
require_once "$IP/inc/page/ListFilms.php";

// Admin Pages
require_once "$IP/inc/page/admin/AdminPage.php";
require_once "$IP/inc/page/admin/AdminIndex.php";
require_once "$IP/inc/page/admin/AdminEditFilm.php";
require_once "$IP/inc/page/admin/AdminAddFilm.php";

// Automated Scripts
require_once "$IP/maintenance/autoScripts.php";

// Vendor
require_once "$IP/libs/twig/lib/Twig/Autoloader.php";
Twig_Autoloader::register();