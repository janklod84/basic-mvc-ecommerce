<?php 
use Core\FH; 
?>
<form action="<?= $this->formAction ?>" method="POST" enctype="multipart/form-data">
	<?= FH::csrfInput(); ?>
	<?= FH::displayErrors($this->displayErrors); ?>
	<?= FH::inputBlock('text', 'Name', 'name', $this->product->name, ['class' => 'form-control input-sm'], [
         'class' => 'form-group col-md-6'
	]); ?>
	<?= FH::inputBlock('text', 'Price', 'price', $this->product->price, ['class' => 'form-control input-sm'], [
         'class' => 'form-group col-md-2'
	]); ?>
	<?= FH::inputBlock('text', 'List Price', 'list', $this->product->list, ['class' => 'form-control input-sm'], [
         'class' => 'form-group col-md-2'
	]); ?>
	<?= FH::inputBlock('text', 'Shipping', 'shipping', $this->product->shipping, ['class' => 'form-control input-sm'], [
         'class' => 'form-group col-md-2'
	]); ?>
	

	<?= FH::submitBlock('Save', ['class' => 'btn btn-large btn-primary'], ['class' => 'text-right col-md-12']); ?>
</form>







