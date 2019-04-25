<?php
  namespace App\Models;
  use Core\{Model};
  use Core\Validators\{RequiredValidator};

  class Transactions extends Model{
    protected static $_table = 'transactions';
    protected static $_softDelete = true;

    public $id, $created_at, $updated_at, $cart_id, $gateway, $type, $amount, $success = 0;
    public $charge_id, $reason, $card_brand, $last4, $name, $shipping_address1, $shipping_address2;
    public $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $deleted = 0;

    public function beforeSave(){
      $this->timestamps();
    }

    public function validateShipping(){
      $this->runValidation(new RequiredValidator($this,['field'=>'name','msg'=>'Name is required.']));
      $this->runValidation(new RequiredValidator($this,['field'=>'shipping_address1','msg'=>'Address is required.']));
      $this->runValidation(new RequiredValidator($this,['field'=>'shipping_city','msg'=>'City is required.']));
      $this->runValidation(new RequiredValidator($this,['field'=>'shipping_state','msg'=>'State is required.']));
      $this->runValidation(new RequiredValidator($this,['field'=>'shipping_zip','msg'=>'Zip Code is required.']));
    }
  }
