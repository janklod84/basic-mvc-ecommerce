<?php $this->setSiteTitle('Add Product') ?>

<?php $this->start('head'); ?>
<script src="<?= PROOT ?>vendor/tinymce/tinymce/tinymce.min.js"></script>
<script>
 /*
  - selector: check body textarea by id = "body"
  - branding: remove Brand of the footer named 'Powered by Tiny'
 */
  tinymce.init({
    selector: '#body',
    branding: false
  });
</script>
<?php $this->end(); ?>

<?php $this->start('body'); ?>
<h1>Add New Product</h1>
<div class="row">
	<div class="col-md-10 col-md-offset-1 well">
		<?php $this->partial('adminproducts', 'form'); ?>
	</div>
</div>
<?php $this->end(); ?>
