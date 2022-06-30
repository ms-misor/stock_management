

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Category Reports
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reports</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class="col-md-12 col-xs-12">
          <form class="form-inline" action="<?php echo base_url('category_reports/category_report_search') ?>" method="POST">

            <div class="form-group">
              <label for="date">Category: </label>
                
                <select class="form-control select_group" id="category" name="category" required>
                  <option value="">Select Category</option>
                  <?php foreach ($categories as $k => $v): ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>

            </div>

            <!-- <div class="form-group">
              <label for="date">Start date: </label>
                
                <input type="text" name="start_date" class="form-control datepicker" placeholder="Enter start date" autocomplete="off" required>

            </div>
            <div class="form-group">
              <label for="date">End date: </label>
                
                <input type="text" name="end_date" class="form-control datepicker" placeholder="Enter end date" autocomplete="off" required>

            </div> -->

            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        </div>

        <br /> <br />


        <div class="col-md-12 col-xs-12">

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

          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Category wise stock available purchase - Report Data</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="datatables" class="table table-bordered table-striped">
                <thead>
                  <tr style="text-align: center;">
                    <td>Enter filter input to get reports.</td>
                  </tr>
                </thead>

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
  
  $(document).ready(function() {

    $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' });

    $("#catagoryReportNav").addClass('active');
  }); 

</script>