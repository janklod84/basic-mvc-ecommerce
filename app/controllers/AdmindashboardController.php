<?php 
namespace App\Controllers;

use Core\Controller;



# http://eshop.loc/admindashboard
class AdmindashboardController extends Controller 
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
      }


      /**
       * index action
       * @return mixed
      */
  	  public function indexAction()
  	  {
           $this->view->render('admindashboard/index');
  	  }

    
}