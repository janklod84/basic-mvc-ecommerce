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
             * Save the current properties to the database
             * Excecute insert or update record
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
                  if($id == '' && $this->id == '') { return false; }
                  $id = ($id == '') ? $this->id : $id;

                  if($this->beforeDelete())
                  {
                      if($this->_softDelete)
                      {
                           $delete = $this->update($id, ['deleted' => 1]);
                      }

                      $delete = $this->_db->delete($this->_table, $id);

                      if($delete)
                      {
                          $this->afterDelete();
                      }

                  }else{
                     
                      $delete = false;

                  }

                  return $delete;
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
               * Update the object with an associative array
               * Work like fields $guarded = [] , $fillable = [] in Laravel
               * 
               * @method assign
               * @param  array   $params    associative array of values to update ['property'=>'new value']
               * @param  array   $list      (optional) indexed array of keys that are to be validated against
               * @param  boolean $blackList (optional) if blacklist is set to true the list param will be treated like a blacklist else it will be treated like a whitelist
               * @return object             returns a model object allows for chaining.
            */
            public function assign($params, $list=[], $blackList=true) 
            {
                    foreach($params as $key => $val) 
                    {
                          // check if there is permission to update the object
                          $whiteListed = true;
                          if(sizeof($list) > 0)
                          {
                              if($blackList)
                              {
                                $whiteListed = !in_array($key,$list);
                              }else{
                                $whiteListed = in_array($key,$list);
                              }
                          }

                          if(property_exists($this,$key) && $whiteListed)
                          {
                              $this->{$key} = $val;
                          }
                    }
                    return $this;
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
             * @return bool
            */
            public function beforeSave(){}


            /**
             * Do something After Saving data
             * @return bool
            */
            public function afterSave(){}


            /**
             * Do something before delete
             * Runs before save needs to return a boolean
             * @return bool
            */
            public function beforeDelete(){ return true; }


            /**
             * Do something before delete
             * @return bool
            */
            public function afterDelete(){}


            /**
             * Generate timestamp for update and insert
             * @return void
            */
            public function timeStamps()
            {
                $now = date('Y-m-d H:i:s');
                $this->updated_at = $now;
                if(empty($this->id))
                {
                     $this->created_at = $now;
                }
            }


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