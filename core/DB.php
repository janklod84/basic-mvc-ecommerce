<?php 
namespace Core;


use \PDO;
use \PDOException;


/**
 * @package Core\DB
*/
class DB 
{
       

       const DSN_FORMAT = 'mysql:host=%s;dbname=%s;charset=utf8';


       /**
        * @var self
       */
	     private static $instance = null;

       
       /**
        * @var \PDO
       */
	     private $pdo;


	    /**
        * @var string
       */
	     private $query;


	    /**
        * @var bool
       */
	     private $error = false;


	    /**
        * @var mixed
       */
	     private $result;


  	   /**
         * @var int
       */
  	   private $count = 0;


  	   /**
         * @var int 
       */
  	   private $lastInsertID = null;

       
       /**
        * Constructor
        * @return void
       */
  	   private function __construct()
  	   {
              try 
              {
                   $this->pdo = new PDO($this->dsn(), DB_USER, DB_PASSWORD);
                   $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                   $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


              }catch(PDOException $e) {
                  
                   die($e->getMessage());
              }
  	   }

       
       /**
        * return dsn
        * @return string
       */
  	   private function dsn()
  	   {
             return sprintf(self::DSN_FORMAT, DB_HOST, DB_NAME);
  	   }

       
       /**
        * Return instance of database
        * Used pattern singleton
        * @return self
       */
  	   public static function getInstance()
  	   {
              if(!isset(self::$instance))
              {
              	self::$instance = new self();
              }

              return self::$instance;
  	   }


       
       /**
        * Excecution query
        * [$this->query return \PDOStatement]
        * 
        * @param string $sql 
        * @param array $params 
        * @param mixed $class
        * @return self
        */
  	   public function query($sql, $params = [], $class = false)
  	   {
               $this->error = false;
               
               // if query executed successfully
               if($this->query = $this->pdo->prepare($sql))
               {
               	  $x = 1;

               	  // if have params
               	  if(count($params))
               	  {
               	  	  foreach($params as $param)
               	  	  {   
               	  	  	   // bindValue(1, 'param 1'); bindValue(2, 'param 2')
               	  	  	   $this->query->bindValue($x, $param);
               	  	  	   $x++;
               	  	  }
               	  }

                    // if executed successfully
               	  if($this->query->execute())
               	  {

                        if($class)
                        {
                           $this->result  = $this->query->fetchAll(PDO::FETCH_CLASS, $class);

                        }else{

                           $this->result  = $this->query->fetchAll(PDO::FETCH_OBJ);
                        }

               	  	   $this->count   = $this->query->rowCount();
               	  	   $this->lastInsertID = $this->pdo->lastInsertId();

               	  }else{

               	  	  $this->error = true;
               	  }
               }

               return $this;

  	   }// end query method
         
       
       /**
        * Read item from table
        * @param string $table 
        * @param array $params 
        * @param mixed $class
        * @return bool
        */
       protected function read($table, $params = [], $class)
       {
	           $conditionString = '';
	           $bind = [];
	           $order = '';
	           $limit = '';


	           // conditions
	           if(isset($params['conditions']))
	           {
	           	   if(is_array($params['conditions']))
	           	   {
	           	   	   foreach($params['conditions'] as $condition)
	           	   	   {
	           	   	   	   $conditionString .= ' '. $condition . ' AND';
	           	   	   }

	           	   	   $conditionString = trim($conditionString);
	           	   	   $conditionString = rtrim($conditionString, ' AND');

	           	   }else{

	           	   	  $conditionString = $params['conditions'];
	           	   }

	           	   if($conditionString != '')
	           	   {
	           	   	    $conditionString = ' WHERE ' . $conditionString;
	           	   }
	           }


	           // bind 
	           if(array_key_exists('bind', $params))
	           {
	           	   $bind = $params['bind'];
	           }


	           // order 
	           if(array_key_exists('order', $params))
	           {
	           	   $order = ' ORDER BY ' . $params['order'];
	           }

	           // limit
	           if(array_key_exists('limit', $params))
	           {
	           	    $limit = ' LIMIT '. $params['limit'];
	           }

	           $sql = sprintf('SELECT * FROM %s%s%s%s', $table, $conditionString, $order, $limit);

	           if($this->query($sql, $bind, $class))
	           {
	           	   if(!count($this->result))
	           	   {
	           	   	    return false;
	           	   }

	           	   return true;
	           }

	           return false;
       }

       
       /**
        * Find item from table
        * @param string $table 
        * @param array $params 
        * @param mixed $class
        * @return bool
       */
       public function find($table, $params = [], $class = false)
       {
             if($this->read($table, $params, $class))
             {
             	 return $this->results();
             }

             return false;
       }


       /**
        * Find first item from table
        * @param string $table 
        * @param array $params 
        * @param mixed $class
        * @return bool
       */
       public function findFirst($table, $params = [], $class = false)
       {
             if($this->read($table, $params, $class))
             {
             	 return $this->first();
             }

             return false;
       }


       
       /**
        * Insert data into table
        * @param string $table 
        * @param array $fields 
        * @return bool
       */
       public function insert($table, $fields = [])
       {
             $fieldString = '';
             $valueString = '';
             $values = [];

             foreach ($fields as $field => $value)
             {
                  $fieldString .= '`' . $field . '`,';
                  $valueString .= '?,';
                  $values[] = $value;
             }

             $fieldString = rtrim($fieldString, ',');
             $valueString = rtrim($valueString, ',');
              
             $sql = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', 
             	             $table, 
             	             $fieldString, 
             	             $valueString
             	          );
             
             // if not errors
             if(!$this->query($sql, $values)->error())
             {
                  return true;
             }

             return false;
       }
       
       
       /**
        * Update data from table
        * @param string $table 
        * @param int $id 
        * @param array $fields 
        * @return bool
        */
       public function update($table, $id, $fields = [])
       { 
           $fieldString = '';
           $values = [];

           foreach ($fields as $field => $value)
           {
                 $fieldString .= ' ' . $field . ' = ?,';
                 $values[] = $value;
           }
           
           $fieldString = trim($fieldString); // remove white spaces
           $fieldString = rtrim($fieldString, ',');
           $sql = sprintf('UPDATE `%s` SET %s WHERE id = ?', $table, $fieldString);
           $values[] = $id;
           
           // if not errors
           if(!$this->query($sql, $values)->error())
           {
           	   return true;
           }

           return false;
       }


       
       /**
        * Delete data into table
        * @param string $table 
        * @param int $id 
        * @return bool
       */
       public function delete($table, $id)
       {
	          $sql = sprintf('DELETE FROM %s WHERE id = ?', $table, $id);
	          $values[] = $id;

	          if(!$this->query($sql, $values)->error())
	          {
	          	  return true;
	          }

	          return false;
       }

       
       /**
        * Get results query
        * @return array
       */
       public function results()
       {
       	   return $this->result;
       }

       
       /**
        * Return first record
        * @return null|array
       */
       public function first()
       {
           return !empty($this->result) ? $this->result[0] : [];
       }
       
       
       /**
        * Return count of records
        * @return int
       */
       public function count()
       {
       	   return $this->count;
       }


       
       /**
        * Return last insert ID
        * @return int
       */
       public function lastID()
       {
           return $this->lastInsertID;
       }

       
       /**
        * Get columns
        * @return 
       */
       public function get_columns($table)
       {
           return $this->query("SHOW COLUMNS FROM {$table}")->results();
       }


       /**
        * return error status
        * @return bool
       */
       public function error()
       {
       	   return $this->error;
       }



}// end class DB