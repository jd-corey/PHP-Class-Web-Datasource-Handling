<?
/* ------------------------------------------------------------------------------------------
PHP Class "datasource"

Purpose: Provide simple functionality for gathering & storing data about service availability
That is, the class provides a simple interface for handling URL-based availability checks.
It wraps some PHP CURL functionalities & combines it with a SQL table for storing status data.
// --------------------------------------------------------------------------------------- */

// ------------------------------------------------------------------------------------------
// Dependencies: The class requires a configuration file
// ------------------------------------------------------------------------------------------
require_once 'config_database_server.php';		// File contains information about the database
// ------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------
// Class definition: Variables, constructor and methods
// ------------------------------------------------------------------------------------------
final class datasource
{
	// ----------------------------------------------------------------------------------------
	// Variables needed for constructing datasource objects
	// ----------------------------------------------------------------------------------------
	private $id;									// String: Datasource ID used for identifying it in the database
	private $name;								// String: Name of datasource
	private $base_url;						// String: URL of datasource
	private $data_format;					// String: Data format (e.g. XML, HTML, ...)
	private $license;							// String: Type of the license applied by the datasource
	private $owner;								// String: Owner of the datasource
	private $available_status;		// Integer: Availability of datasource (can be 1 or 0)
	private $available_datetime;	// DateTime: Point in time of availbility
	private $status;							// Integer: Status code of the data record (and not the URL)
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Constructor method
	// ----------------------------------------------------------------------------------------
	public function __construct($data)
	{
		$this->id												=				$data['id'];
		$this->name											=				$data['name'];
		$this->base_url									=				$data['base_url'];
		$this->data_format							=				$data['data_format'];
		$this->license									=				$data['license'];
		$this->owner										=				$data['owner'];
		$this->available_status					=				$data['available_status'];
		$this->available_datetime				=				$data['available_datetime'];
		$this->source_status						=				$data['status'];
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get ID from respective datasource object
	// ----------------------------------------------------------------------------------------
	public function getId()
	{
		return $this->id;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get name from respective datasource object
	// ----------------------------------------------------------------------------------------
	public function getName()
	{
		return $this->name;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get URL from respective datasource object
	// ----------------------------------------------------------------------------------------	
	public function getBaseUrl()
	{
		return $this->base_url;
	}
	// ----------------------------------------------------------------------------------------

	// ----------------------------------------------------------------------------------------
	// Get data format from respective datasource object
	// ----------------------------------------------------------------------------------------
	public function getDataFormat()
	{
		return $this->data_format;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get license from respective datasource object
	// ----------------------------------------------------------------------------------------
	public function getLicense()
	{
		return $this->license;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get owner from respective datasource object
	// ----------------------------------------------------------------------------------------
	public function getOwner()
	{
		return $this->owner;
	}
	// ----------------------------------------------------------------------------------------

	// ----------------------------------------------------------------------------------------
	// Get availability status from respective datasource object
	// ----------------------------------------------------------------------------------------
	public function getAvailableStatus()
	{
		return $this->available_status;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get availability timestamp from respective datasource object
	// ----------------------------------------------------------------------------------------
	public function getAvailableDateTime()
	{
		return $this->available_datetime;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get status from respective datasource object
	// ----------------------------------------------------------------------------------------
	public function getStatus()
	{
		return $this->source_status;
	}
	// ----------------------------------------------------------------------------------------

	// ----------------------------------------------------------------------------------------
	// Check, if a given URL responds to a call, and return the result 
	// ----------------------------------------------------------------------------------------
 	public function checkAvailabilityOfUrl($url)
  {
  	// Check, if a valid url is provided via the method parameter
    if(!filter_var($url, FILTER_VALIDATE_URL))
    {
    	return false;
    }

		// Initialize CURL based on the URL provided
		$curl_init = curl_init($url);
		curl_setopt($curl_init,CURLOPT_CONNECTTIMEOUT,10);
		curl_setopt($curl_init,CURLOPT_HEADER,true);
		curl_setopt($curl_init,CURLOPT_NOBODY,true);
		curl_setopt($curl_init,CURLOPT_RETURNTRANSFER,true);

		// Call URL and save the response
		$response = curl_exec($curl_init);

		// Close CURL connection
		curl_close($curl_init);

		// Return result regarding availability
		if ($response)
		{
			return true;
		}
		else
		{
			return false;
		}   
  }
  // ----------------------------------------------------------------------------------------
  
  // ----------------------------------------------------------------------------------------
	// Write availability status to database 
	// ----------------------------------------------------------------------------------------
  public function updateAvailabilityInDatabase ($status)
  {
  	if (isset($status) AND !empty($status) AND is_bool($status))
  	{
  		try
			{
	  		global $database;			// variable is declared in config file imported above
	  		
	  		// Turn boolean value of $status into numeric/ integer value
	  		$status_for_db				=			"";
	  		if ($status === true)
	  		{
	  			$status_for_db			=			1;
	  		}
	  		elseif ($status === false)
	  		{
	  			$status_for_db			=			0;
	  		}
	  		
	  		// Create new PDO instance that represents a database connection
	  		$db_internal_handle		=	"db_number_one";
	  		$pdo									= new PDO($database[$db_internal_handle]['server'],
	  																		$database[$db_internal_handle]['username'],
	  																		$database[$db_internal_handle]['password']);
	  			  		
	  		// Build SQL command for updating status information
	  		$tablename						=	"datasource";
	  		$current_datetime			=	date_format(date_create(), 'Y-m-d H:i:s');
	  		$sql									=	"
	  														UPDATE
																	".$tablename."
																SET
																	".$tablename.".available_status = '".$status_for_db."',
																	".$tablename.".available_datetime = '".$current_datetime."',
																	".$tablename.".update_datetime = '".$current_datetime."'
																WHERE
																	".$tablename.".id = '".$this->getId()."'
																LIMIT 1
																";
				
				// Execute SQL command and close connection to database
				$pdo->exec($sql);
				$pdo									=	null;
					
				return true;
			}
			catch (PDOException $e)
			{
			  die();
			}
  	}
  	else
  	{
  		return null;
  	}
  }
  // ----------------------------------------------------------------------------------------

}
// ------------------------------------------------------------------------------------------
?>