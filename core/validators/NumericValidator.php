<?php 
namespace Core\Validators;

use Core\Validators\CustomValidator;


/**
 * @package Core\Validators\NumericValidator
*/

class NumericValidator  extends CustomValidator
{
       
       /**
        * Run Validation
        * @return bool
       */
       public function runValidation()
       {
           $value = $this->_model->{$this->field};
           $pass = !empty($value) ? is_numeric($value) : true;
           return $pass;
       }
}