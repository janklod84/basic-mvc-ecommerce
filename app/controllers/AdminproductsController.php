<?php
namespace App\Controllers;

use Core\Controller;
use Core\H;
use Core\Session;
use Core\Router;
use App\Models\Products;
use App\Models\ProductImages;
use App\Lib\Utilities\Uploads;
use App\Models\Users;
use App\Models\Brands;

class AdminproductsController extends Controller {

  public function onConstruct(){
    $this->view->setLayout('admin');
    $this->currentUser = Users::currentUser();
  }

  public function indexAction(){
    $this->view->products = Products::findByUserId($this->currentUser->id);
    $this->view->render('adminproducts/index');
  }

  public function addAction(){
    $product = new Products();
    $productImage = new ProductImages();
    if($this->request->isPost()){
      $this->request->csrfCheck();
      $files = $_FILES['productImages'];
      if($files['tmp_name'][0] == ''){
        $product->addErrorMessage('productImages','You must choose an image.');
      } else {
        $uploads = new Uploads($files);
        $uploads->runValidation();
        $imagesErrors = $uploads->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $product->addErrorMessage('productImages',trim($msg));
        }

      }
      $product->assign($this->request->get(),Products::blackList);
      $product->featured = ($this->request->get('featured') == 'on')? 1 : 0;
      $product->user_id = $this->currentUser->id;
      $product->save();
      if($product->validationPassed()){
        //upload images
        ProductImages::uploadProductImages($product->id,$uploads);
        //redirect
        Session::addMsg('success','Product Added!');
        Router::redirect('adminproducts');
      }
    }
    $this->view->product = $product;
    $this->view->brands = Brands::getOptionsForForm($this->currentUser->id);
    $this->view->formAction = PROOT.'adminproducts/add';
    $this->view->displayErrors = $product->getErrorMessages();
    $this->view->render('adminproducts/add');
  }

  public function deleteAction(){
    $resp = ['success'=>false,'msg'=>'Something went wrong...'];
    if($this->request->isPost()){
      $id = $this->request->get('id');
      $product = Products::findByIdAndUserId($id, $this->currentUser->id);
      if($product){
        ProductImages::deleteImages($id);
        $product->delete();
        $resp = ['success' => true, 'msg' => 'Product Deleted.','model_id' => $id];
      }
    }
    $this->jsonResponse($resp);
  }

  public function toggleFeaturedAction(){
    $resp = ['success'=>false,'msg'=>'Something went wrong...'];
    if($this->request->isPost()){
      $id = $this->request->get('id');
      $product = Products::findByIdAndUserId($id, $this->currentUser->id);
      if($product){
        $product->featured = ($product->featured == 1)? 0 : 1;
        $product->save();
        $msg = ($product->featured == 1)? "Product Now Featured" : "Product No Longer Featured";
        $resp = ['success' => true, 'msg' => $msg,'model_id' => $id,'featured'=>$product->featured];
      }
    }
    $this->jsonResponse($resp);
  }

  public function editAction($id){
    $user = Users::currentUser();
    $product = Products::findByIdAndUserId((int)$id,$user->id);
    if(!$product){
      Session::addMsg('danger','You do not have permission to edit that product');
      Router::redirect('adminproducts');
    }
    $images = ProductImages::findByProductId($product->id);
    if($this->request->isPost()){
      $this->request->csrfCheck();
      $files = $_FILES['productImages'];
      $isFiles = $files['tmp_name'][0] != '';
      if($isFiles){
        // $productImage = new ProductImages();
        $uploads = new Uploads($files);
        $uploads->runValidation();
        $imagesErrors = $uploads->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $product->addErrorMessage('productImages',trim($msg));
        }
      }
      $product->assign($this->request->get(),Products::blackList);
      $product->featured = ($this->request->get('featured') == 'on')? 1 : 0;
      $product->user_id = $this->currentUser->id;
      $product->save();
      if($product->validationPassed()){
        if($isFiles){
          //upload images
          ProductImages::uploadProductImages($product->id,$uploads);
        }
        $sortOrder = json_decode($_POST['images_sorted']);
        ProductImages::updateSortByProductId($product->id,$sortOrder);
        //redirect
        Session::addMsg('success','Product Updated!');
        Router::redirect('adminproducts');
      }
    }
    $this->view->brands = Brands::getOptionsForForm($user->id);
    $this->view->images = $images;
    $this->view->product = $product;
    $this->view->displayErrors = $product->getErrorMessages();
    $this->view->render('adminproducts/edit');
  }

  function deleteImageAction(){
    $resp = ['success'=>false];
    if($this->request->isPost()){
      $user = Users::currentUser();
      $id = $this->request->get('image_id');
      $image = ProductImages::findById($id);
      $product = Products::findByIdAndUserId($image->product_id,$user->id);
      if($product && $image){
        ProductImages::deleteById($image->id);
        $resp = ['success'=>true,'model_id'=>$image->id];
      }
    }
    $this->jsonResponse($resp);
  }
}
