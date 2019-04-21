<?php $this->setSiteTitle($this->product->title); ?>

<?php $this->start('body'); ?>

<h1><?= $this->product->title ?></h1>

<div class="row">
	<!-- Column 1 -->
	<div class="col-md-6">
		 <div class="product_img_wrapper">
	 	   <img src="<?= PROOT ?>images/catan.jpg" alt="Catan">
	    </div>
	</div>

	<!-- Column 2 -->
	<div class="col-md-6">
		<p><span>List Price</span> $<?= $this->product->list_price ?></p>
		<p><span>Our Price</span> $<?= $this->product->price ?></p>
		<p><span>Shipping:</span> $<?= $this->product->shipping ?></p>
		<p><span>Total:</span> $<?= $this->product->price + $this->product->shipping ?></p>
		<p><span>Vendor:</span> J.J</p>
		<p><span>Brand:</span> Kosmos</p>

		<div class="text-right">
			<button class="btn btn-large btn-danger" onclick="console.log('add to cart')">
			<i class="glyphicon glyphicon-shopping-cart"></i> Add To Cart
		</button>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<h3>Product Description</h3>
		<p>
			<?= nl2br($this->product->description) ?>
		</p>
	</div>

	<div class="col-md-6">
		<h3>Customer Reviews</h3>
	</div>
</div>

<?php $this->end(); ?>

