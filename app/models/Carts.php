<?php
namespace App\Models;
use Core\{Model,Session,Cookie,DB};

class Carts extends Model {

  public $id,$created_at,$updated_at,$purchased=0,$deleted=0;
  protected static $_table = 'carts';
  protected static $_softDelete = true;

  public function beforeSave(){
    $this->timeStamps();
  }

  public static function findCurrentCartOrCreateNew(){
    if(!Cookie::exists(CART_COOKIE_NAME)){
      $cart = new Carts();
      $cart->save();
    } else {
      $cart_id = Cookie::get(CART_COOKIE_NAME);
      $cart = self::findById((int)$cart_id);
    }
    Cookie::set(CART_COOKIE_NAME,$cart->id,CART_COOKIE_EXPIRY);
    return $cart;
  }

  public static function findAllItemsByCartId($cart_id){
    $sql = "SELECT items.*, p.name, p.price, p.shipping, pi.url, brands.name as brand
      FROM cart_items as items
      JOIN products as p ON p.id = items.product_id
      JOIN product_images as pi ON p.id = pi.product_id
      LEFT JOIN brands ON brands.id = p.brand_id
      WHERE items.cart_id = ? AND pi.sort = 0 AND items.deleted = 0";
    $db = DB::getInstance();
    return $db->query($sql,[(int)$cart_id])->results();
  }

}
