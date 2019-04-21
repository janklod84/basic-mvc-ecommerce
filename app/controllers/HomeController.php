<?php 
namespace App\Controllers;

use Core\Controller;
use App\Models\Users;


class HomeController extends Controller 
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
           $this->view->render('home/index');
  	  }

      
      /**
       * Test Ajax Request
       * @return mixed
      */
      public function testAjaxAction()
      {
           $response = [
            'success' => true,
            'data' => ['id' => 23, 'name' => 'Michelle', 'favorite_food' => 'bread']
          ];

          $this->jsonResponse($response);
      }
}