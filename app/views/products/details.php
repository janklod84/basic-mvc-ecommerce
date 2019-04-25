<?php $this->setSiteTitle($this->product->name);?>
<?php $this->start('body');?>
<div class="row">
  <div class="col col-md-6 product-details-slideshow">
    <p>
      <a class="back-to-results" href="<?=PROOT?>home"><i class="fas fa-arrow-left"></i> Back to results</a>
    </p>
    <!-- slideshow -->
    <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <?php for($i = 0; $i < sizeof($this->images); $i++):
          $active = ($i == 0)? "active" : "";
          ?>
          <li data-target="#carouselIndicators" data-slide-to="<?=$i?>" class="<?=$active?>" style="background-color:#101820;"></li>
        <?php endfor;?>
      </ol>
      <div class="carousel-inner">
        <?php for($i = 0; $i < sizeof($this->images); $i++):
          $active = ($i == 0)? " active" : "";
          ?>
          <div class="carousel-item<?=$active?>">
            <img src="<?= PROOT.$this->images[$i]->url?>" class="d-block image-fluid" style="max-height:500px;margin:0 auto;" alt="<?=$this->product->name?>">
          </div>
        <?php endfor;?>
      </div>
      <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>

  <div class="col col-md-6">
    <h3><?= $this->product->name?></h3>
    <p>by <?=$this->product->getBrandName()?></p>
    <hr />
    <div>
      <span class="product-details-label">Price: </span>
      <span class="product-details-price">$<?=$this->product->price?></span> &
      <?php if($this->product->shipping != 0):?>
        <span class="product-details-label">Shipping: </span>
      <?php endif;?>
      <?=$this->product->displayShipping()?>
    </div>
    <div class="product-details-body"><?= html_entity_decode($this->product->body)?></div>
    <div>
      <a href="<?=PROOT?>cart/addToCart/<?=$this->product->id?>" class="btn btn-info">
        <i class="fas fa-cart-plus"></i> Add To Shopping Cart
      </a>
    </div>
  </div>
</div>

<?php $this->end(); ?>
