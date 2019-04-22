<?php 
namespace App\Controllers;

use Core\Controller;

class RestrictedController extends Controller
{
      

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