<?php 
namespace App\Models;

use Core\Model;

/**
 * @package App\Models\Products 
*/ 
class Products extends Model
{
        
        /**
         * @var int      $id
         * @var datetime $created_at
         * @var datetime $updated_at
         * @var string   $title
         * @var string   $description
         * @var int      $vendor
         * @var int      $brand
         * @var int      $category
         * @var float    $list_price
         * @var float    $price
         * @var float    $shipping
         * @var int      $deleted = 0
        */
        public $id;
        public $created_at;
        public $updated_at;
        public $title;
        public $description;
        public $vendor;
        public $brand;
        public $category;
        public $list_price;
        public $price;
        public $shipping;
        public $deleted = 0;


        /**
         * Constructor
         * @return void
        */
        public function __construct()
        {
        	$table = 'products';
        	$this->_softDelete = true;
        	parent::__construct($table);
        }
}
