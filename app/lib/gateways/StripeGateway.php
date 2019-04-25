<?php
  namespace App\Lib\Gateways;
  use App\Lib\Gateways\AbstractGateway;
  use Stripe\{Stripe,Charge};

  class StripeGateway extends AbstractGateway{

    public function getView(){
      return 'card_forms/stripe';
    }

    public function processForm($post){
      $data = [
        'amount' => $this->grandTotal * 100,
        'currency' => 'usd',
        'description' => 'Ruah purchase: ' . $this->itemCount . 'items. Cart ID: ' . $this->cart_id,
        'source' => $post['stripeToken']
      ];

      $ch = $this->charge($data);
    }

    public function charge($data){
      Stripe::setApiKey(STRIPE_PRIVATE);
      $charge = Charge::create($data);
      return $charge;
    }

    public function handleChargeResp($ch){}
    public function createTransaction($ch){}
  }
