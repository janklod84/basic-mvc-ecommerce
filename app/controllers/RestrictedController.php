<?php 
namespace App\Controllers;

use Core\Controller;

class RestrictedController extends Controller
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
      }


      /**
       * index action
       * @return mixed
      */
  	  public function indexAction()
  	  {
           $this->view->render('restricted/index');
  	  }

      
      /**
       * Bad Token Action
       * @return void
      */
      public function badTokenAction()
      {
           $this->view->render('restricted/badToken');
      }
}