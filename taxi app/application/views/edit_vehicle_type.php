<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">User Details</header>
      <div class="panel-body">      
        <div class="adv-table table-responsive">

          <form method="post" id="register-form" action="">
             <?php if($this->session->flashdata('msg')): ?>     
        <div class="alert alert-success alert-block fade in">
                <button data-dismiss="alert" class="close close-sm" type="button">
                    <i class="fa fa-times"></i>
                </button>
                <h4>
                    <i class="fa fa-ok-sign"></i>
             <?php echo $this->session->flashdata('msg'); ?>
                </h4>                            
          </div>     
            <?php endif; ?>   
          <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
            <tbody>
             <?php
             // echo "<pre>"; print_r($one_user_info); echo "</pre>"; die;
             foreach($vehicle_type_details as $record){?>
              <tr>
                <td>Id:</td><td><?php echo $record->id; ?></td>
              </tr>
              <tr>
                <td>Name:</td> <td><input type = "text" name="vehicle_type" value="<?php echo $record->vehicle_type; ?>"></td>
             </tr>
             <tr>
                <td>Base rate:</td> <td><input type = "number" name="base_rate" value="<?php echo $record->base_rate; ?>"></td>
             </tr>
             <tr>
                <td>per km:</td> <td><input type = "number" name="per_km" value="<?php echo $record->per_km; ?>"></td>
             </tr>
             <tr>
                <td>per min:</td> <td><input type = "number" name="per_min" value="<?php echo $record->per_min; ?>"></td>
             </tr>
             <tr>
                <td>waiting charges:</td> <td><input type = "number" name="waiting_charge" value="<?php echo $record->waiting_charge; ?>"></td>
             </tr>
             <tr>
                <td>Traffic charges:</td> <td><input type = "number" name="traffic_charges" value="<?php echo $record->traffic_charges; ?>"></td>
             </tr>
             <tr>
                <td>Higher Car Restriction:</td> <td>
                <?php
                  if ($record->restriction==1) {
                    echo '<input type="radio" name="restriction" value="1" checked> On<br>
                    <input type="radio" name="restriction" value="0"> Off<br>';
                  } else {
                    echo '<input type="radio" name="restriction" value="1" checked> On<br>
                    <input type="radio" name="restriction" value="0" checked> Off<br>';
                  }
                  
                 ?></td>
             </tr>
             <tr>
              <td>Registered From:</td><td><?php echo date('d-M-Y H:i:s', strtotime($record->date_created));?></td>
              </tr>
              <?php } ?>
              </tbody>
              </table>
            
            <br>
            <input name="submit" type="submit" class="btn btn-primary" value="Submit">
            <a href="<?php echo base_url('Dashboard/vehicle_type_list') ?>" class="btn btn-success">Back to Vehicle Type List</a>
            </form>
        </div>
      </div>

    </section>
  </section>
</section>
<script src="<?php echo base_url();?>/public/js/jquery.js"></script>
<script src="<?php echo base_url();?>/public/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url();?>/public/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url();?>/public/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url();?>/public/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url();?>/public/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/public/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/public/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?php echo base_url();?>/public/js/owl.carousel.js" ></script>
<script src="<?php echo base_url();?>/public/js/jquery.customSelect.min.js" ></script>
<script src="<?php echo base_url();?>/public/js/respond.min.js" ></script>
<script class="include" type="text/javascript" src="<?php echo base_url();?>/public/js/jquery.dcjqaccordion.2.7.js"></script>

<!--common script for all pages-->
<script src="<?php echo base_url();?>/public/js/common-scripts.js"></script>

<!--script for this page-->
<script src="<?php echo base_url();?>/public/js/sparkline-chart.js"></script>
<script src="<?php echo base_url();?>/public/js/easy-pie-chart.js"></script>
<script src="<?php echo base_url();?>/public/js/count.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('#hidden-table-info').DataTable();
});
</script>