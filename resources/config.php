<?php 

ob_start();
/**
 * sirve para indicarle a PHP que se ha de iniciar el buffering de la salida, es decir, 
 * que debe empezar a guardar la salida en un bufer interno, en vez de enviarla al cliente.
 */

session_start();
/*
crea una sesión o reanuda la actual basada en un identificador de sesión pasado mediante una petición GET o POST
*/

//session_destroy();

defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);

defined("TEMPLATE_FRONT") ? null : define("TEMPLATE_FRONT", __DIR__ . DS . "templates/front");

defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK", __DIR__ . DS . "templates/back");

defined("DB_HOST") ? null : define("DB_HOST", "localhost");

defined("DB_USER") ? null : define("DB_USER", "root");

defined("DB_PASS") ? null : define("DB_PASS", "");

defined("DB_NAME") ? null : define("DB_NAME", "ecom1_db");

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

require_once("functions.php");
require_once("cart.php");

?>