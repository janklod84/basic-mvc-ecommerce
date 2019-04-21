<?php 
namespace App\Controllers;

use Core\Controller;
use App\Models\Products;


# http://eshop.loc/adminproducts
class AdminproductsController extends Controller 
{

      /**
       * Constructor
       * @param string $controller 
       * @param string $action 
       * @return void
      */
      public function __construct($controller, $action)
      {
           parent::__construct($controller, $action);
           $this->view->setLayout('admin');
           // $this->loadModel('Products');
      }


      /**
       * index action
       * @return mixed
      */
  	  public function indexAction()
  	  {
           $this->view->render('adminproducts/index');
  	  }


      /**
       * add action
       * @return mixed
      */
      public function addAction()
      {
           $product = new Products();
           
           if($this->request->isPost())
           {
                $this->request->csrfCheck();
                $product->assign($this->request->get());
                $product->save();
           }

           $this->view->product = $product;
           $this->view->formAction = PROOT. 'adminproducts/add';
           $this->view->displayErrors = $product->getErrorMessages();
           $this->view->render('adminproducts/add');
      }

    
}