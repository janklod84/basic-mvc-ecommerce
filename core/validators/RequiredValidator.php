<?php 
namespace Core\Validators;


use Core\Validators\CustomValidator;



/**
 * @package Core\Validators\RequiredValidator
*/
class RequiredValidator  extends CustomValidator
{
       
       /**
        * Run Validation
        * @return bool
       */
       public function runValidation()
       {
           $value = $this->_model->{$this->field};
           $pass  = (!empty($value));
           return $pass;
       }
}