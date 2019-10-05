<?
/* ------------------------------------------------------------------------------------------
Exemplary use/ implementation of PHP Class "datasource"

Purpose: Provide an exemplary use of the the class "datasource" (based on configuration)
Context: It is assumed that there is a MySQL database that contains datasource information
// --------------------------------------------------------------------------------------- */

// ------------------------------------------------------------------------------------------
// Import class "datasource" and configuration
// ------------------------------------------------------------------------------------------
require_once 'config_database_server.php';
require_once 'class_datasource.php';
// ------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------
// Read datasource information from MySQL database
// ------------------------------------------------------------------------------------------
	$count											= 0;
	try
	{
		// Create new PDO instance that represents a database connection
		$db_internal_handle				=	"db_number_one";
		$pdo											= new PDO($database[$db_internal_handle]['server'],
																				$database[$db_internal_handle]['username'],
																				$database[$db_internal_handle]['password']);
		
		// Build SQL command for requesting information about datasources
		$tablename								=	"datasource";
		$sql											= "
																SELECT
																	".$tablename.".*
																FROM
		    													".$tablename."
																WHERE
																	".$tablename.".status = '1'
																	AND (
																			".$tablename.".available_datetime < '".date_format(date_create(), 'Y-m-d')."'
																			OR
																			".$tablename.".available_datetime IS NULL
																			)
																";
																
		// Execute SQL command and close connection to database
		$data											=	array();
		foreach ($pdo->query($sql) as $row)
		{
			$data[$count]						=	$row;
			$count++;
		}
		$pdo											=	null;
	}
	catch (PDOException $e)
	{
		print "Error!: " . $e->getMessage() . "<br/>";
	  die();
	}
// ------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------
// Check availability of datasources and update database accordingly
// ------------------------------------------------------------------------------------------
	if ($count > 0)
	{
		for ($i=0; $i < count($data); $i++)
		{
			// (a) Create a new datasource object
			$ds						=			new datasource($data[$i]);
			
			// (b) Check availability of URL
			$status				=			"";
			$status				=			$ds->checkAvailabilityOfUrl($ds->getBaseUrl());
			
			// (c) Update availability in database
			$update				=			$ds->updateAvailabilityInDatabase($status);
		}
	}
	else
	{
		exit;
	}
// ------------------------------------------------------------------------------------------

?>