<?php 
namespace App\Controllers;

use Core\Controller;
use App\Models\Users;
use Core\H;



class HomeController extends Controller 
{



      /**
       * index action
       * @return mixed
      */
  	  public function indexAction()
  	  {
           $this->view->render('home/index');
  	  }

}