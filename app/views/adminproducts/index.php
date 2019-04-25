<?php $this->start('body')?>
<table class="table table-bordered table-hover table-striped table-sm">
  <thead>
    <th>Name</th>
    <th>Price</th>
    <th>Shipping</th>
    <th></th>
  </thead>
  <tbody>
    <?php foreach($this->products as $product): ?>
      <tr data-id="<?=$product->id?>">
        <td><?=$product->name ?></td>
        <td><?=$product->price ?></td>
        <td><?=$product->shipping ?></td>
        <td class="text-right">
          <a class="btn btn-sm btn-outline-info mr-1" onclick="toggleFeatured('<?=$product->id?>');return false;">
            <i data-id="<?=$product->id?>" class="<?=($product->featured == 1)? 'fas fa-star': 'far fa-star'?>"></i>
          </a>
          <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminproducts/edit/<?=$product->id?>"><i class="fas fa-edit"></i></a>
          <a class="btn btn-sm btn-danger" href="#" onclick="deleteProduct('<?=$product->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>


<script>
  function deleteProduct(id){
    if(window.confirm("Are you sure you want to delete this product. It cannot be reversed.")){
      jQuery.ajax({
        url : '<?=PROOT?>adminproducts/delete',
        method : "POST",
        data : {id : id},
        success: function(resp){
          var msgType = (resp.success)? 'success' : 'danger';
          if(resp.success){
            jQuery('tr[data-id="'+resp.model_id+'"]').remove();
          }
          alertMsg(resp.msg, msgType);
        }
      });
    }
  }

  function toggleFeatured(id){
    jQuery.ajax({
      url: '<?=PROOT?>adminproducts/toggleFeatured',
      method: "POST",
      data : {id : id},
      success : function(resp) {
        if(resp.success){
          var el = jQuery('i[data-id="'+resp.model_id+'"]');
          var klass = (resp.featured)? 'fas' : 'far';
          el.removeClass("fas far");
          el.addClass(klass);
          alertMsg(resp.msg,'success');
        } else {
          alertMsg(resp.msg, 'danger');
        }
      }
    });
  }
</script>
<?php $this->end(); ?>
