

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

        <?php //echo base_url('products/item_edit/'.$id); ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Update Big Unit</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('units_setup/big_unit_edit/'.$id) ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="unit_id">Small Unit</label>
                  <select class="form-control select_group" id="unit_id" name="unit_id" disabled="true">
                    <option value="">Select Unit</option>
                    <?php foreach ($units as $k => $v): ?>
                      <option value="<?php echo $v['id'] ?>" <?php if($big_unit_info->unit_id == $v['id']) { echo "selected='selected'"; } ?> ><?php echo $v['name'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>

                <input type="hidden" name="big_unit_id" value="<?php echo $id;?>">

                <div class="form-group">
                  <label for="qty">Qty</label>
                  <input type="text" class="form-control" id="qty" name="qty" placeholder="Enter Qty" value="<?php echo $big_unit_info->qty; ?>" autocomplete="off" />
                </div>

                <div class="form-group">
                  <label for="name">Big Unit Name</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="<?php echo $big_unit_info->name; ?>" autocomplete="off" />
                </div>


              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('units_setup/') ?>" class="btn btn-warning">Back</a>
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

    $("#unitsetupNav").addClass('active');

  });
</script>