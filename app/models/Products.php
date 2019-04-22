<?php 
namespace App\Models;

use Core\Model;
use Core\Validators\{
RequiredValidator,
NumericValidator
};


/**
 * @package App\Models\Products 
*/ 
class Products extends Model
{
        
        /**
         * @var int      $id
         * @var datetime $created_at
         * @var datetime $updated_at
         * @var string   $name
         * @var int      $price
         * @var float    $list
         * @var float    $shipping
         * @var string   $description
         * @var int      $deleted = 0
        */
        public $id;
        public $created_at;
        public $updated_at;
        public $name;
        public $price;
        public $list;
        public $shipping;
        public $body;
        public $deleted = 0;

        
        # There fields 'll be guarded all times
        const blackList = ['id', 'deleted'];

        protected static $_table = 'products';
        protected static $_softDelete = true;


        /**
         * Action to do before saving data
         * @return void
        */
        public function beforeSave()
        {
              $this->timeStamps();
        }

        
        /**
         * Validation
         * @return void
        */
        public function validator()
        {
              $requiredFields = [
                 'name' => "Name", 
                 'price' => 'Price',
                 'list' => 'List Price',
                 'shipping' => 'Shipping',
                 'body' => 'Body'
              ];

              foreach($requiredFields as $field => $display)
              {
                  $this->runValidation(new RequiredValidator($this, ['field' => $field, 'msg' => $display ." is required."]));
              }

              $this->runValidation(new NumericValidator($this, [
                 'field' => 'price',
                 'msg' => 'Price must be a number.'
              ]));

              $this->runValidation(new NumericValidator($this, [
                 'field' => 'list',
                 'msg' => 'List Price must be a number.'
              ]));

              $this->runValidation(new NumericValidator($this, [
                 'field' => 'shipping',
                 'msg' => 'Shipping must be a number.'
              ]));
        }
}
