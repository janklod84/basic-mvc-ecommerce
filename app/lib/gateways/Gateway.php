<?php
  namespace App\Lib\Gateways;
  use App\Lib\Gateways\{StripeGateway};

  class Gateway {
    public static function build($cart_id){
      if(GATEWAY == 'stripe'){
        return new StripeGateway($cart_id);
      }
    }
  }
