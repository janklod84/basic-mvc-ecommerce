<?php 
namespace Core\Validators;

use Core\Validators\CustomValidator;



/**
 * @package Core\Validators\EmailValidator
*/
class EmailValidator  extends CustomValidator
{
       
       /**
        * Run Validation
        * @return bool
       */
       public function runValidation()
       {
           $email = $this->_model->{$this->field};
           $pass = !empty($email) ? filter_var($email, FILTER_VALIDATE_EMAIL) : true;
           return $pass;
       }
}