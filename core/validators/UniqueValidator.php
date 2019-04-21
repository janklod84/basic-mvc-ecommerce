<?php 
namespace Core\Validators;

use Core\Validators\CustomValidator;

/**
 * @package Core\Validators\UniqueValidator
*/
class UniqueValidator  extends CustomValidator
{
       
       /**
        * Run Validation
        * @return bool
       */
       public function runValidation()
       {
            $field = (is_array($this->field)) ? $this->field[0] : $this->field;
            $value = $this->_model->{$field};

            $conditions = ["{$field} = ?"];
            $binds = [$value];

            // check updating record
            if(!empty($this->_model->id))
            {
                 $conditions[] = "id = ?";
                 $binds[] = $this->_model->id;
            }

            // this allow you to check multiple fields for unique
            if(is_array($this->field))
            {
                 array_unshift($this->field);

                 foreach($this->field as $adds)
                 {
                     $conditions[] = "{$adds} = ?";
                     $binds[] = $this->_model->{$adds};
                 }
            }

            $queryParams = ['conditions' => $conditions, 'bind' => $binds];
            $other = $this->_model->findFirst($queryParams);
            return(!$other);
       }



}