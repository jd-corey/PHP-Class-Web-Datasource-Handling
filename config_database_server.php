<?
/* ------------------------------------------------------------------------------------------
Configuration file

Purpose: Provide data (credentials, ...) about servers - such as database or file servers
// --------------------------------------------------------------------------------------- */

// ------------------------------------------------------------------------------------------
// Connection data for MySQL database servers
//
// Variable "database"	=		Name of database to be used within the server specified below
// Variable "server"		=		Address of a MySQL server
// Variable "username"	=		Username required for logging into the server/ accessing the db
// Variable "password"	=		Password required for logging into the server/ accessing the db
// ------------------------------------------------------------------------------------------
$database																		=		array();

// Database "number_one"
$internal_handle														=		"db_number_one";
$database[$internal_handle]['database']			=		"name of database 1";
$database[$internal_handle]['server']				=		"mysql:host=EnterYourHost;dbname=".$database[$internal_handle]['database'];
$database[$internal_handle]['username']			=		"username to be used";
$database[$internal_handle]['password']			=		"secret database pw";

// Database "number_two"
$internal_handle														=		"db_number_two";
$database[$internal_handle]['database']			=		"name of database a";
$database[$internal_handle]['server']				=		"mysql:host=EnterYourHost;dbname=".$database[$internal_handle]['database'];
$database[$internal_handle]['username']			=		"username to be used";
$database[$internal_handle]['password']			=		"secret database pw";
// ------------------------------------------------------------------------------------------

?>