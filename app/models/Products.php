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
        public $description;
        public $body;
        public $deleted = 0;

        
        # There fields 'll be guarded all times
        const blackList = ['id', 'deleted'];


        /**
         * Constructor
         * @return void
        */
        public function __construct()
        {
        	$table = 'products';
        	// $this->_softDelete = true;
        	parent::__construct($table);
        }

        /**
         * Action to do before saving data
         * @return void
        */
        public function beforeSave()
        {
            $this->timeStamps();
        }


        /**
         * After save
         * @return void
        */
        public function afterSave()
        {
             $this->id = $this->_db->lastID();
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
        }
}
