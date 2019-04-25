<?php
namespace App\Models;
use Core\{Model,Session,Cookie};
use App\Models\{Carts,Products};

class CartItems extends Model {

  public $id,$created_at,$updated_at,$cart_id,$product_id,$qty=0,$deleted=0;
  protected static $_table = 'cart_items';
  protected static $_softDelete = true;

  public function beforeSave(){
    $this->timeStamps();
  }

  public static function findByProductIdOrCreate($cart_id,$product_id){
    $item = self::findFirst([
      'conditions' => "cart_id = ? AND product_id = ?",
      'bind' => [$cart_id,$product_id]
    ]);
    if(!$item){
      $item = new self();
      $item->cart_id = $cart_id;
      $item->product_id = $product_id;
      $item->save();
    }
    return $item;
  }

  public static function addProductToCart($cart_id,$product_id){
    $product = Products::findById((int)$product_id);
    if($product){
      $item = self::findByProductIdOrCreate($cart_id,$product_id);
    }
    return $item;
  }

}
