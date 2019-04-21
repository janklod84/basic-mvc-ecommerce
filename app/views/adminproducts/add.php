<?php $this->setSiteTitle('Add Product') ?>
<?php $this->start('body'); ?>
<h1>Add New Product</h1>
<div class="row">
	<div class="col-md-10 col-md-offset-1 well">
		<?php $this->partial('adminproducts', 'form'); ?>
	</div>
</div>
<?php $this->end(); ?>
