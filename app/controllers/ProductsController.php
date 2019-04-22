<?php 
namespace App\Controllers;


use Core\Controller;
use Core\H;


/* http://eshop.loc/products/ */
class ProductsController extends Controller 
{
       
         /**
          * Constructor
          * @param object $controller 
          * @param string $action 
          * @return void
         */
    	   public function __construct($controller, $action)
    	   {
              parent::__construct($controller, $action);
              $this->loadModel("Products");
    	   }


  	    /**
         * details action
         * @param int $product_id
         * @return mixed
        */
    	  public function detailsAction($product_id = null)
    	  {
            $product = $this->ProductsModel->findFirst([
                 'conditions' => "id = ?",
                 'bind' => [(int)$product_id]
            ]);
            
            $this->view->product = $product;
            $this->view->render('products/details');
    	  }
}