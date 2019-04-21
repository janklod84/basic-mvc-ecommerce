<?php 
namespace Core\Validators;


use \Exception;


/**
 * @package Core\Validators\CustomValidator
*/
abstract class CustomValidator 
{
       
       /**
        * @var bool
       */
       public $success = true;


       /**
        * @var string
       */
       public $msg = '';


       /**
        * @var string
       */
       public $field;


       /**
        * @var mixed
       */
       public $rule;

       
       /**
        * @var object Model
       */
       protected $_model;

       
       /**
        * Constructor
        * @param object $model 
        * @param array $params 
        * @return void
       */
       public function __construct($model, $params)
       {
             $this->_model = $model;

             // make sure the field exists
             if(!array_key_exists('field', $params))
             {
             	   throw new Exception("You must include a field element in the params array.");
             	   
             }else{

             	   $this->field =  is_array($params['field']) ? $params['field'][0] : $params['field'];
             }
             

             // make sure field exists in model
             if(!property_exists($model, $this->field))
             {
             	    throw new Exception("The field does not belong to the model.");
             }

             
             // make sure the message exists in the params array
             if(!array_key_exists('msg', $params))
             {
             	   throw new Exception("You must include a msg element to the params array.");

             }else{

             	   $this->msg = $params['msg'];
             }

             
             // make sure the rule exists in params array
             if(array_key_exists('rule', $params))
             {
             	    $this->rule = $params['rule'];
             }


            try {
               
                $this->success = $this->runValidation();

             }catch(Exception $e){

             	  echo "Validation Exception on " . get_class() . ': '. $e->getMessage();
             }
       }
       
       /**
        * Run validation
        * @return bool
       */
       abstract public function runValidation();
}