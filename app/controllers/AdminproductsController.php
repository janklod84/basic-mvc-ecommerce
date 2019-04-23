<?php 
namespace App\Controllers;

use Core\H;
use Core\Controller;
use Core\Router;
use Core\Session;
use App\Models\Products;
use App\Models\ProductImages;
use App\Models\Users;
use App\Lib\Utilities\Uploads;



# http://eshop.loc/adminproducts
class AdminproductsController extends Controller 
{

          /**
           * set details
           * @return void
          */
          public function onConstruct()
          {
               $this->view->setLayout('admin');
               $this->currentUser = Users::currentUser();
          }


          /**
           * index action
           * @return mixed
          */
      	  public function indexAction()
      	  {
               $this->view->products = Products::findByUserId($this->currentUser->id);
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
                    $this->request->csrfCheck();
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

                    $product->assign($this->request->get(), Products::blackList);
                    $product->featured = ($this->request->get('featured') == 'on') ? 1 : 0;
                    $product->user_id = $this->currentUser->id;
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

          
          /**
           * Delete action
           * @return void
          */
          public function deleteAction()
          {

               # response by default
               $resp = [
                  'success' => false,
                  'msg' => 'Something went wrong...'
               ];

               if($this->request->isPost())
               {
                     $id = $this->request->get('id');
                     $product = Products::findByIdAndUserId($id, $this->currentUser->id);

                     if($product)
                     {
                          ProductImages::deleteImages($id);
                          $product->delete();
                          $resp = [
                             'success' => true , 
                             'msg' => 'Product deleted',
                             'model_id' => $id
                          ];

                     }
               }

               $this->jsonResponse($resp);
          }


          public function toggleFeaturedAction()
          {
               
               # response by default
               $resp = [
                  'success' => false,
                  'msg' => 'Something went wrong...'
               ];

               if($this->request->isPost())
               {
                     $id = $this->request->get('id');
                     $product = Products::findByIdAndUserId($id, $this->currentUser->id);

                     if($product)
                     {
                         $product->featured = !$product->featured;
                         $product->save();
                         $msg = ($product->featured == 1) ? "Product Now Featured" : "Product No Longer Featured";
                         $resp = [
                             'success' => true , 
                             'msg' => $msg,
                             'model_id' => $id,
                             'featured' => $product->featured, 
                         ];

                     }
               }

               $this->jsonResponse($resp);
          }

    
}