

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Big Units</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Big Units</li>
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

        <?php //if(in_array('createProduct', $user_permission)): ?>
          <a href="<?php echo base_url('units_setup/big_unit_create') ?>" class="btn btn-primary">Add Big Unit</a>
          <br /> <br />
        <?php //endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Big Units</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageItems" class="table table-striped table-bordered table-hover">
              <thead>
              <tr>
                <th>Unit Name</th>
                <th>Small Unit</th>
                <th>Qty</th>
                <th>Action</th>
              </tr>
              </thead>

              <tbody>
                
                <?php if(!empty($results)){

                  foreach ($results as $row) {

                ?>
                <tr>
                  <td><?php echo $row->name;?></td>
                  <td><?php echo $row->small_unit_name;?></td>
                  <td><?php echo $row->qty;?></td>
                  <td><?php echo date('m/d/Y',strtotime($row->create_date));?></td>
                  <td>

                    <a href="<?php echo base_url("units_setup/big_unit_edit/$row->big_unit_id") ?>" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a> 

                    <a href="<?php echo base_url("units_setup/remove_big_unit/$row->big_unit_id") ?>" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-close"></i></a>

                  </td>

                </tr>

                  <?php 

                  }

                }

                ?>

              </tbody>

            </table>
          </div>
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
var manageItems;
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function() {

  $("#unitsetupNav").addClass('active');

  // initialize the datatable 
  manageItems = $('#manageItems').DataTable({
    // 'ajax': base_url + 'products/fetchProductData',
    // 'order': []
  });

});



</script>
