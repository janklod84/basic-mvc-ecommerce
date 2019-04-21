<?php 
namespace Core\Validators;

use Core\Validators\CustomValidator;



/**
 * @package Core\Validators\MaxValidator
*/
class MaxValidator  extends CustomValidator
{
       
       /**
        * Run Validation
        * @return bool
       */
       public function runValidation()
       {
           $value = $this->_model->{$this->field};
           $pass  = (strlen($value) <= $this->rule);
           return $pass;
       }
}