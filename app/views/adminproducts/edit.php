<?php use Core\FH; ?>
<?php $this->setSiteTitle("Edit ".$this->product->name);?>
<?php $this->start('head') ?>
  <script src='<?=PROOT?>vendor/tinymce/tinymce/tinymce.min.js'></script>
  <script>
    tinymce.init({
      selector: '#body',
      branding: false
    });
  </script>
<?php $this->end() ?>

<?php $this->start('body')?>
<div class="row align-items-center justify-content-center">
  <div class="col-md-8 bg-light p-3">
    <h1 class="text-center">Edit <?=$this->product->name?></h1>
    <form action="" method="POST" enctype="multipart/form-data">
      <?= FH::csrfInput();?>
      <div class="row">
        <input type="hidden" id="images_sorted" name="images_sorted" value="" />
        <?= FH::inputBlock('text','Name','name',$this->product->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
        <?= FH::inputBlock('text','Price','price',$this->product->price,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
        <?= FH::inputBlock('text','List Price','list',$this->product->list,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
        <?= FH::inputBlock('text','Shipping','shipping',$this->product->shipping,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
        <?= FH::selectBlock('Brand','brand_id',$this->product->brand_id,$this->brands,['class'=>'form-control input-sm'],['class'=>'form-group col-md-3'],$this->displayErrors) ?>

      </div>

      <div class="row">
        <?= FH::textareaBlock('Body','body',$this->product->body,['class'=>'form-control','rows'=>'6'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
        <?= FH::checkboxBlock('Featured','featured',$this->product->isChecked(),['class'=>'form-controll'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
      </div>

      <?php $this->partial('adminproducts','editImages')?>

      <div class="row">
        <?= FH::inputBlock('file',"Upload ProductImages:",'productImages[]','',['class'=>'form-control','multiple'=>'multiple'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
      </div>
      <div class="row">
        <div class="col-md-12 text-right">
          <a href="<?=PROOT?>adminproducts" class="btn btn-large btn-secondary">Cancel</a>
          <?= FH::submitTag('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-right col-md-12']); ?>
        </div>
      </div>
    </form>
  </div>
</div>
<?php $this->end()?>
