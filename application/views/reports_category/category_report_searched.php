

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
                    <option value="<?php echo $v['id'] ?>" <?php if($v['id'] == $category){echo "selected";}?>><?php echo $v['name'] ?></option>
                  <?php endforeach ?>
                </select>

            </div>

            <!-- <div class="form-group">
              <label for="date">Start date: </label>
                
                <input type="text" name="start_date" class="form-control datepicker" placeholder="Enter start date" autocomplete="off" value="<?php //echo $start_date; ?>">

            </div>
            <div class="form-group">
              <label for="date">End date: </label>
                
                <input type="text" name="end_date" class="form-control datepicker" placeholder="Enter end date" autocomplete="off" value="<?php //echo $end_date; ?>">

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
                <tr>
                  <th width="10%">Serial</th>
                  <th width="20%">Product Name</th>
                  <th width="10%">Qty</th>
                  <th width="10%">Rate</th>
                  <th width="10%">Purchase</th>
                </tr>
                </thead>
                <tbody>

                  <?php

                  $total_purchase = 0.0;
                  $sl = 1;

                  if(!empty($purchases)){

                  foreach ($purchases as $k => $row): 

                    $total_price = 0.0;
                    $total_price = (float)$row->qty * (float)$row->price;

                    $total_purchase = $total_purchase + $total_price;

                  ?>
                    <tr>
                      <td><?php echo $sl; ?></td>
                      <td><?php echo $row->name; ?></td>
                      <td><?php echo $row->qty; ?></td>
                      <td><?php echo $row->price; ?></td>
                      <td><?php echo number_format((float)$total_price, 2, '.', ''); ?> tk</td>
                    </tr>

                  <?php 

                    $sl++;

                    endforeach ;
                  }else{

                  ?>

                  <tr>
                    <td colspan="4" style="text-align: center;"><b>No data available !</b></td>
                  </tr>

                  <?php } ?>
                  
                </tbody>

                <?php if($total_purchase > 0){ ?>

                <tbody>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td style="text-align: right;"><b>Total Purchase:</b></td>
                      <td colspan=""><b><?php echo number_format((float)$total_purchase, 2, '.', '');?></b> tk</td>
                    </tr>
                </tbody>

                <?php }?>

              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- /.box -->
<!--           <div class="box">
            <div class="box-header">
              <h3 class="box-title">Category wise paid sale - Report Data</h3>
            </div>
            <div class="box-body">
              <table id="datatables" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Product Name</th>
                  <th>sale</th>
                </tr>
                </thead>
                <tbody>

                  <?php

                  //$total_sale = 0.0;

                  //if(!empty($sales)){

                  //foreach ($sales as $k => $v): 

                    //$total_sale = $total_sale + (float)$v;

                    ?>
                    <tr>
                      <td><?php //echo $k; ?></td>
                      <td><?php //echo $v; ?></td>
                    </tr>

                  <?php //endforeach ;
                  //}//else{

                  ?>

                  <tr>
                    <td colspan="2" style="text-align: center;"><b>No paid order/sale available !</b></td>
                  </tr>

                  <?php //} ?>
                  
                </tbody>

                <?php //if($total_sale > 0){ ?>

                <tbody>
                    <tr>
                      <td><b>Total Sale:</b></td>
                      <td><b><?php //echo number_format((float)$total_sale, 2, '.', '');?></b> tk</td>
                    </tr>
                </tbody>

                <?php //}?>

              </table>
            </div>
          </div> -->
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