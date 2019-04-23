<?php $this->start('body'); ?>
<table class="table table-bordered table-hover table-striped table-condensed">
	<thead>
		<tr>
			<th>Name</th>
			<th>Price</th>
			<th>Shipping</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->products as $product): ?>
            <tr data-id="<?= $product->id ?>">
            	<td><?= $product->name ?></td>
            	<td><?= $product->price ?></td>
            	<td><?= $product->shipping ?></td>
            	<td class="text-right">
                <a class="btn btn-sm btn-default" onclick="toggleFeatured('<?= $product->id ?>'); return false;">
                  <i data-id="<?= $product->id ?>" class="<?= ($product->featured == 1) ? "fa fa-star": "fa fa-star-o" ?>"></i>
                </a>
            		<a href="<?= PROOT ?>adminproducts/edit/<?= $product->id ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>

            		<a href="" class="btn btn-danger btn-sm" onclick="deleteProduct('<?= $product->id ?>');return false;"><i class="fa fa-trash"></i></a>
            	</td>
            </tr>
	    <?php endforeach; ?>
	</tbody>
</table>


<!-- scripts -->
<script>
	function deleteProduct(id)
	{
          if(window.confirm('Are you sure you want to delete this product. It cannot be reversed'))
          {
               jQuery.ajax({
                  url : '<?= PROOT ?>adminproducts/delete',
                  method: "POST",
                  data : {id : id},
                  success: function(resp) {
                      
                      /* console.log(resp); */

                      var msgType = (resp.success) ? 'success' : 'danger';
                      if(resp.success)
                      {
                          jQuery('tr[data-id="' + resp.model_id+'"]').remove();
                      }
                       
                      alertMsg(resp.msg, msgType);
                      // alertMsg(resp.msg, msgType, 3000); // 3s
                  }
               });
          }
	}


  function toggleFeatured(id)
  {
      jQuery.ajax({
        url: '<?= PROOT ?>adminproducts/toggleFeatured',
        method: "POST",
        data: {id : id},
        success: function (resp) {
            if(resp.success)
            {
                // console.log(resp);

                var el = jQuery('i[data-id="'+ resp.model_id +'"]');
                var klass = (resp.featured) ? 'fa-star' : 'fa-star-o';
                el.removeClass("fa-star fa-star-o");
                el.addClass(klass);
                alertMsg(resp.msg, 'success');

            }else{
                
                 // alertMsg(resp.msg, 'danger');
            }
        }
      });
  }
</script>
<?php $this->end(); ?>
