<?php 
namespace Core\Validators;

use Core\Validators\CustomValidator;



/**
 * @package Core\Validators\MatchesValidator
*/
class MatchesValidator  extends CustomValidator
{
       
       /**
        * Run Validation
        * @return bool
       */
       public function runValidation()
       {
           $value = $this->_model->{$this->field};
           return $value == $this->rule; // $value === $this->rule
       }
}