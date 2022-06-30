

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Items</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Items</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Add Product</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('products/item_create') ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="store">Stock Product</label>
                  <select class="form-control select_group" id="product_id" name="product_id" required>
                    <option value="">Select Product</option>
                    <?php foreach ($products as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="big_unit">Big Unit</label>
                  <select class="form-control select_group" id="big_unit" name="big_unit">
                    <option value="">Select Big Unit</option>
                    <?php foreach ($big_units as $k => $v): ?>
                      <option value="<?php echo $v['big_unit_id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="big_unit_qty">Big unit qty</label>
                  <input type="number" class="form-control" id="big_unit_qty" name="big_unit_qty" placeholder="Enter big unit qty" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="small_unit">Small Unit</label>
                  <select class="form-control select_group" id="small_unit" name="small_unit" required>
                    <option value="">Select Small Unit</option>
                    <?php foreach ($small_units as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="small_unit_qty">Small unit qty</label>
                  <input type="number" class="form-control" id="small_unit_qty" name="small_unit_qty" placeholder="Enter small unit qty" required autocomplete="off" />
                </div>
                
                <div class="form-group">
                  <label for="qty">Total Qty</label>
                  <input type="number" class="form-control" id="qty" name="qty" placeholder="Enter Qty" required readonly/>
                </div>

                <div class="form-group">
                  <label for="price">Unit Price</label>
                  <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" placeholder="Enter unit price" required autocomplete="off" onkeyup="priceCalculate()" />
                </div>

                <div class="form-group">
                  <label for="total_price">Total Price</label>
                  <input type="number" class="form-control" id="total_price" name="total_price" placeholder="Enter total price" required autocomplete="off" readonly="" />
                </div>


                <div class="form-group">
                  <label for="qty">Purchase Date</label>
                  <input type="text" class="form-control datepicker" id="purchase_date" name="purchase_date" required placeholder="Enter Date" autocomplete="off" />
                </div>

                <!-- <div class="form-group">
                  <label for="description">Description</label>
                  <textarea type="text" class="form-control" id="description" name="description" placeholder="Enter 
                  description" autocomplete="off">
                  </textarea>
                </div> -->

                

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('products/item_list/') ?>" class="btn btn-warning">Back</a>
              </div>
            </form>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
    

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $(".select_group").select2();
    $("#description").wysihtml5();

    $("#mainItemNav").addClass('active');
    $("#addItemNav").addClass('active');

    $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' });

    var base_url = '<?php echo base_url();?>';
    

    // Check product is changed, then set all the input and dropdown fields to null/empty
    $("#product_id").on('change', function() {

          var product_id  = $(this).val();
          // console.log(product_id);
          if(product_id){

            $('#big_unit').val("").trigger('change');
            $('#big_unit_qty').val("");

            $('#small_unit').val("").trigger('change');
            $('#small_unit_qty').val("");
            $('#qty').val("");
            $('#total_price').val("");

            // Check, id this big unit is related with the product selected above..
            var action_url = base_url+'products/getProductValueByIdForItem';

            $.ajax({
              url: action_url,
              type: 'post',
              data: {
                'product_id':product_id,
              }, 
              dataType: 'json',
              success:function(response) {
                console.log(response);

                if(response.price){
                  $('#price').val(response.price);
                }else{
                  $('#price').val(0);
                }
              }
            });

          }
        
      });

    $("#big_unit").on('change', function() {

        var big_unit_id  = $(this).val();
        var product_id  = $('#product_id').val();
        // console.log(big_unit_id);

        if(product_id && big_unit_id){

          $('#big_unit_qty').val("");
          $('#small_unit').val("").trigger('change');
          $('#small_unit_qty').val("");
          $('#qty').val("");

          // Check, id this big unit is related with the product selected above..
          var action_url = base_url+'products/check_big_unit';

          $.ajax({
            url: action_url,
            type: 'post',
            data: { 'big_unit_id':big_unit_id,'product_id':product_id }, 
            dataType: 'json',
            success:function(response) {
              //console.log(response);
              if(response == false){
                alert("The selected unit is not related with selected Stock Product !");
                $('#big_unit').val("").trigger("change");
              }
            }
          }); 
        }else{
            if(big_unit_id){
              alert("Select product first !");
              location.reload();
            }
        }
    });

    $("#big_unit_qty").on('keyup', function() {

        var big_unit_id  = $("#big_unit").val();
        var big_unit_qty  = $("#big_unit_qty").val();
        if(big_unit_id == ""){

          alert("First you need to select Big Unit !");
          $("#big_unit_qty").val("");

        }else{

          $('#small_unit').val("").trigger('change');
          $('#small_unit_qty').val("");

          var action_url = base_url+'products/product_qty_for_big_unit';

          $.ajax({
            url: action_url,
            type: 'post',
            data: { 'big_unit_id':big_unit_id,'big_unit_qty':big_unit_qty }, 
            dataType: 'json',
            success:function(response) {

              // console.log(response.total_qty);
              if(response.status == true){

                 $("#qty").val(response.total_qty);
                 priceCalculate();
              }
            }
          }); 
        }

    });

    $("#small_unit").on('change', function() {

        var small_unit_id  = $(this).val();
        var product_id  = $('#product_id').val();

        if(product_id && small_unit_id){

          $('#small_unit_qty').val("");

          // Check, id this big unit is related with the product selected above..
          var action_url = base_url+'products/check_small_unit_calc';

          $.ajax({
            url: action_url,
            type: 'post',
            data: { 
              'small_unit_id':small_unit_id,
              'product_id':product_id,
            }, 
            dataType: 'json',
            success:function(response) {
              //console.log(response);
              if(response == false){
                alert("The selected small unit is not related with selected Stock Product !");
                $('#small_unit').val("").trigger("change");
              }
            }
          }); 
        }else{
            if(small_unit_id){
              alert("Select product first !");
              location.reload();
            }
        }
    });

    $("#small_unit_qty").on('keyup', function() {

        var small_unit_id  = $("#small_unit").val();
        var small_unit_qty  = $("#small_unit_qty").val();

        var big_unit_id  = $("#big_unit").val();
        var big_unit_qty  = $('#big_unit_qty').val();

        if(small_unit_id == ""){

          alert("First you need to select Small Unit !");
          $("#small_unit_qty").val("");

        }else{

          var action_url = base_url+'products/product_qty_for_small_unit';

          $.ajax({
            url: action_url,
            type: 'post',
            data: { 
              'small_unit_id':small_unit_id,
              'small_unit_qty':small_unit_qty,
              'big_unit_id':big_unit_id,
              'big_unit_qty':big_unit_qty,
               }, 
            dataType: 'json',
            success:function(response) {

              // console.log(response);
              if(response){

                 $("#qty").val(response.total_qty);

                 priceCalculate();
              }
            }
          }); 
        }

    });

  });

    // Check , if total quantity is not available, then not allow to enter unit price...
    function priceCalculate() {

        var total_price = 0;
        var unit_price = 0;
        var total_qty = 0;

        var price = Number($("#price").val());
        //Set unit_price
        if(price){
            unit_price = price;
        }
        //Set total_qty
        var qty = Number($("#qty").val());
        if(qty){
            total_qty = qty;
        }
        //Now calculate total price
        total_price = (total_qty * unit_price).toFixed(2);
        // console.log(total_price);

        $("#total_price").val(total_price);

     }


</script>