
<?php $this->start('body'); ?>
<h1 class="text-center text-secondary">Welcome to Ruah MVC Framework!</h1>
<main class="products-wrapper">
  <?php foreach($this->products as $product):
    $shipping = ($product->shipping == '0.00')? 'Free Shipping!' : 'Shipping: $'.$product->shipping;
    $list = ($product->list != '0.00')? '$'.$product->list : '';
   ?>
    <div class="card">
      <img src="<?= PROOT .$product->url?>" class="card-img-top" alt="<?=$product->name?>">
      <div class="card-body">
        <h5 class="card-title"><a href="<?=PROOT?>products/details/<?=$product->id?>"><?=$product->name?></a></h5>
        <p class="products-brand">By: <?=$product->brand?></p>
        <p class="card-text">$<?=$product->price?> <span class="list-price"><?=$list?></span></p>
        <p class="card-text"><?= $shipping?></p>
        <a href="<?=PROOT?>products/details/<?=$product->id?>" class="btn btn-primary">See Details</a>
      </div>
    </div>
  <?php endforeach; ?>
</main>
<?php $this->end(); ?>
