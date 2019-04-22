<?php 
namespace App\Controllers;

use Core\Controller;
use Core\Router;
use Core\Session;
use App\Models\Products;
use App\Models\ProductImages;
use Core\H;
use App\Lib\Utilities\Uploads;



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
                // $this->request->csrfCheck();
                $files = $_FILES['productImages'];

                if($files['tmp_name'][0] == '')
                {
                   $product->addErrorMessage('productImage', 'You must choose an image.');

                }else{

                    $uploads = new Uploads($files);
                    $uploads->runValidation();
                    $imagesErrors = $uploads->validates();

                    if(is_array($imagesErrors))
                    {
                        $msg = "";
                        foreach($imagesErrors as $name => $message)
                        {
                            $msg .= $message . " ";
                        }
                        $product->addErrorMessage('productImage', trim($msg));
                    }

                }

               
                $product->body = $this->request->get('body');
                $product->assign($this->request->get(), Products::blackList);
                $product->save();

                if($product->validationPassed())
                {
                     // upload images
                     ProductImages::uploadProductImage($product->id, $uploads);

                     // redirect
                     Session::addMsg('success', 'Product Added');
                     Router::redirect('adminproducts');
                }
           }

           $this->view->product = $product;
           $this->view->formAction = PROOT. 'adminproducts/add';
           $this->view->displayErrors = $product->getErrorMessages();
           $this->view->render('adminproducts/add');
      }

    
}