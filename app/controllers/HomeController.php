<?php
  namespace App\Controllers;
  use Core\Controller;
  use App\Models\Products;
  use Core\H;

  class HomeController extends Controller {

    public function indexAction() {
      $pModel = new Products();
      $products = $pModel->featuredProducts();
      $this->view->products = $products;
      $this->view->render('home/index');
    }
  }
