<?php
/**
 * 
 * @author Victor Andres Chavarro Perez
 * victor.chavarro@brainz.co
 *
 * Class connection: Connect to mongo database
 */
class connection{
	
	/**
	 * Connection with mongo
	 * @access public
	 * @var connection
	 */
	public $connection;
	
	/**
	 * Database
	 * @access private
	 */
	private $db;
	
	/**
	 * Collection
	 * @var $collection
	 * @access private
	 */
	private $collection;

    /**
     * Connect with mongo database,
     * optionals parameters, host and port to connect
     * @param $db
     * @param string $host
     * @param int $port
     * @return connection
     */
	public function __construct( $db, $host = NULL, $port = 27017 ){
		
		//Connect with DB
		if( $host == NULL ){
			$this->connection = new Mongo();
		}
		else{
			$connection_host = "".$host.":"."$port";
			$this->connection = new Mongo( $connection_host );
		}
		
		//Select Database
		if( $db != NULL ){
			return $this->select_database($db);
		}
		
		
	}
	
	/**
	 * Select database
	 * @param string database
	 * @return databaseObj
	 */
	public function select_database( $db ){
		return $this->db = $this->connection->$db;
	}
	
	/**
	 * Select collection (table of documments)
	 * @param string $collection_name
	 */
	public function select_collection( $collection_name ){
		$this->collection = $this->db->$collection_name;
	}
	
	/**
	 * Insert into collection (table of documments)
	 * @param string $info_to_insert
     * @return bool
	 */
	public function insert($info_to_insert){

		if($this->collection->insert($info_to_insert))
		{
			return true;
		}	
		else{
			return false;
		}
	}
	
	/**
	 * Search into a collection 
	 * @param array $query
	 * @return array
	 */
	public function search( $limit = 1000, $sortBy = null, $sort = 1, $query = null, $fields = null ){

		$array_return = array(); //array with the info

        if($query == null){
            if($sortBy == null)
            {
                $cursor =  $this->collection->find()->limit($limit);
            }
            else
            {
                $cursor =  $this->collection->find()->sort( array($sortBy => $sort ) )->limit($limit);
            }

        }
        else{
            if($sortBy == null)
            {
                $cursor =  $this->collection->find($query)->limit($limit);
            }
            else
            {
                if ($fields == null)
                    $cursor =  $this->collection->find($query)->sort( array( $sortBy => $sort ) )->limit($limit);
                else
                    $cursor =  $this->collection->find($query, $fields)->sort( array( $sortBy => $sort ) )->limit($limit);
            }

        }

		while( $cursor->hasNext() ) {
		    $array_return[] = $cursor->getNext();
		}

		return $array_return;
	}
	
	/**
	 * 
	 * Update/Replace a documment 
	 * @param array $criteria
	 * @param array $new_data
     * @return bool
	 */
	public function update( $criteria, $new_data ){

        if($this->collection->update($criteria, $new_data))
		{
		 return true; 
		}
		else { return false; }
		
	}

    /**
     * Drop all indexes from collection
     * @return bool
     */
    public function drop(){
        if($this->collection->drop())
        {
            return true;
        }
        else{
            return false;
        }
    }

    /*Function ready*/
	public function connection_debug(){

	}


    /**
     * Connection alerts Function can help to struct outputs
     * @param string $info_to_debug
     * @param int $error_code
     * @param string $status
     * @param bool $print_json
     * @param bool $print_on_screen
     * @return array|bool|string
     */
    public function print_debug( $info_to_debug, $status = 'SUCCESS', $error_code = 0, $print_json = true, $print_on_screen = true ){
		$array_output = array(
			'error_code' => $error_code,
			'Status' => $status,
			'Debug_info' => $info_to_debug 
		);
		
		if($print_on_screen){
			if($print_json){
				echo json_encode($array_output);
			}
			else{
				print_r($array_output);
			}
			return true;
		}
		else{
			if($print_json){
				return json_encode($array_output);
			}
			else{
				return $array_output;
			}
			
		}
	}
	
	public function __destruct(){
		
	}
}

?>