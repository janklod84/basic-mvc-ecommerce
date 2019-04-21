<?php 
namespace Core;


use \stdClass;


/**
 * @package Core\Model
*/
class Model 
{
        
	        /**
	         * Connection to database
	         * @var \DB
	         */
		    protected $_db;


		    /**
		     * table name
		     * @var string
		    */
		    protected $_table;

	        
	        /**
	         * @var string
	        */
		    protected $_modelName;

	        
	        /**
	         * @var bool
	        */
		    protected $_softDelete = false;


            
            /**
             * @var bool
            */
            protected $_validates = true;


            /**
             * @var array
            */
            protected $_validationErrors = [];




	        /**
	         * @var int
	        */
		    public $id;


	        
	        /**
	         * Constructor
	         * Ex: if $table = 'user_sessions' => $modelName = 'UserSessions'
	         * 
	         * @param string $table
	         * @return void
	        */
		    public function __construct($table)
		    {
                   $this->_db = DB::getInstance();
                   $this->_table = $table;
                   $this->_modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->_table)));
		    }

        
            /**
             * Get columns
             * @return array
            */
		    public function get_columns()
		    {
		    	 return $this->_db->get_columns($this->_table);
		    }

            
            /**
             * Soft Delete concept
             * For deleting rows or params from database 
             * 
             * @param array $params 
             * @return bool
            */
            protected function _softDeleteParams($params)
            {
                 if($this->_softDelete)
                 {
                     if(array_key_exists('conditions', $params))
                     {
                            if(is_array($params['conditions']))
                            {
                                $params['conditions'][] = "deleted != 1";

                            }else{

                                $params['conditions'] .= " AND deleted != 1";
                            }

                     }else{

                         $params['conditions'] = "deleted != 1";
                     }
                 }
                 return $params;
            }

            
            /**
             * Find item with parses params
             * Extrating params
             * 
             * @param array $params 
             * @return array
            */
		    public function find($params = [])
		    {
                 $params = $this->_softDeleteParams($params);
                 $resultsQuery = $this->_db->find($this->_table, $params, get_class($this));
                 if(!$resultsQuery) { return []; }
                 return $resultsQuery;
		    }


            
            /**
             * Find first record
             * @param array $params 
             * @return array
            */
		    public function findFirst($params = [])
		    {
                 $params = $this->_softDeleteParams($params);
		    	 $resultQuery = $this->_db->findFirst($this->_table, $params, get_class($this));
                 return $resultQuery;
		    }

            
            /**
             * Find item by id
             * @param int $id 
             * @return mixed
            */
		    public function findById($id)
		    {
                 return $this->findFirst([
                     'conditions' => "id = ?",
                     'bind' => [$id]
                 ]);
		    }

            
            /**
             * Return insert or update record
             * @return bool
            */
            public function save()
            {
                // Run validator before saving
                $this->validator();
                
                // if validation passed, we will run next scripts
                if($this->_validates)
                {
                    
                    // run before save
                    $this->beforeSave();

                    // Get fields current Model
                    $fields = H::getObjectProperties($this);

                    // determine whether to update or insert 
                    if(property_exists($this, 'id') && $this->id != '')
                    {
                        
                          $save = $this->update($this->id, $fields);

                          // run after save
                          $this->afterSave();

                          return $save;

                    }else{
                        
                         /* H::debug($fields, true); */

                         $save = $this->insert($fields);

                         // run after save
                         $this->afterSave();

                         return $save;
                    }
                }
                
                return false;
            }



            /**
             * Insert data into table
             * @param array $fields 
             * @return bool
            */
		    public function insert($fields)
		    {
                 if(empty($fields))
                 {
                 	return false;
                 }

                 return $this->_db->insert($this->_table, $fields);
		    }


            /**
             * Update data [ record ]
             * @param int $id 
             * @param array $fields 
             * @return bool
            */
		    public function update($id, $fields)
		    {
                if(empty($fields) || $id == '')
                {
                	 return false;
                }

                return $this->_db->update($this->_table, $id, $fields);
		    }

            
            /**
             * Delete record 
             * @param int $id 
             * @return bool
            */
		    public function delete($id = '')
		    {
                  if($id == '' && $this->id == '')
                  {
                  	   return false;
                  }

                  $id = ($id == '') ? $this->id : $id;

                  if($this->_softDelete)
                  {
                  	   return $this->update($id, ['deleted' => 1]);
                  }

                  return $this->_db->delete($this->_table, $id);
		    }

            
            /**
             * Execute Query
             * @param string $sql 
             * @param array $bind 
             * @return bool
             */
		    public function query($sql, $bind = [])
		    {
		    	 return $this->_db->query($sql, $bind);
		    }


            
            /**
             * Return data
             * @return array
            */
		    public function data()
		    {
		    	 $data = new stdClass();

		    	 foreach(H::getObjectProperties($this) as $column => $value)
		    	 {
		    	 	   $data->column = $value;
		    	 }

		    	 return $data;
		    }

            
            /**
             * Assignement for exemple data from request $_POST
             * 
             * @param array $params 
             * @return void
            */
		    public function assign($params)
		    {
		    	 if(!empty($params))
		    	 {
		    	 	 foreach($params as $key => $val)
		    	 	 {
		    	 	 	 if(property_exists($this, $key))
		    	 	 	 {
		    	 	 	 	  $this->{$key} = $val;
		    	 	 	 }
		    	 	 }

		    	 	 return true;
		    	 }

		    	 return false;
		    }

            
            /**
             * Populate object data
             * @param object $obj 
             * @return void
            */
		    protected function populateObjData($result)
		    {
                 foreach($result as $key => $val)
                 {
                 	   $this->{$key} = $val;
                 }
		    }

            
            /**
             * Validator
             * @return 
            */
            public function validator(){}
            

            
            /**
             * Run validator
             * @param object $validator 
             * @return void
            */
            public function runValidation($validator)
            {
                 $key = $validator->field;

                 if(!$validator->success)
                 {
                     $this->_validates = false;
                     $this->_validationErrors[$key] = $validator->msg;
                 }
            }

            
            /**
             * Get Error messages
             * @return array
            */
            public function getErrorMessages()
            {
                return $this->_validationErrors;
            }

            
            /**
             * Get all passed validation
             * @return bool
            */
            public function validationPassed()
            {
                return $this->_validates;
            }

            
            /**
             * Add Error Message
             * @param string $field
             * @param string $msg
             * @return void
            */
            public function addErrorMessage($field, $msg)
            {
                  $this->_validates = false;
                  $this->_validationErrors[$field] = $msg;
            }


            /**
             * Do something Before Saving data
             * @return void
            */
            public function beforeSave(){}


            /**
             * Do something After Saving data
             * @return void
            */
            public function afterSave(){}


            
            /**
             * Determine if has property $id
             * and if is set
             * @return bool
            */
            public function isNew()
            {
                return (property_exists($this, 'id') && !empty($this->id)) ? false : true;
            }
}