<?php 
namespace App\Controllers;

use Core\Controller;
use App\Models\Products;
use App\Models\ProductImages;
use Core\H;


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
       * $product->assign(request-params, [there params will be not assigned], false)
       * Ex:
       * if false ['price', 'shipping'] will be BlackList, buy default it's setted true
       * debug How blackList and WhiteList work
       * H::debug($product);
       * 
       * @return mixed
      */
      public function addAction()
      {
           $product = new Products();
           $productImage = new ProductImages();

           if($this->request->isPost())
           {
                $files = $_FILES['productImages'];
                $this->request->csrfCheck();
                $imagesErrors = $productImage->validateImages($files);

                if(is_array($imagesErrors))
                {
                    $msg = "";
                    foreach($imagesErrors as $name => $message)
                    {
                        $msg .= $message . " ";
                    }
                    $product->addErrorMessage('productImage', trim($msg));
                }
                $product->body = $this->request->get('body');
                $product->assign($this->request->get(), Products::blackList);
                $product->save();

                if($product->validationPassed())
                {
                     // upload images
                     $structuredFiles = ProductImages::restructureFiles($files);
                     ProductImages::uploadProductImage($product->id, $structuredFiles);
                }
           }

           $this->view->product = $product;
           $this->view->formAction = PROOT. 'adminproducts/add';
           $this->view->displayErrors = $product->getErrorMessages();
           $this->view->render('adminproducts/add');
      }

    
}