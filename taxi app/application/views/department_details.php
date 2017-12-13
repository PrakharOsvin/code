<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">User Details</header>
      <div class="panel-body">      
        <div class="adv-table table-responsive">


          <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
            <tbody>
             <?php
             // echo "<pre>"; print_r($one_user_info); echo "</pre>"; die;
             foreach($department_details as $record){?>
              <tr>
              <td>User Id:</td><td><?php echo $record->id; ?></td>
              </tr>
              <tr>
              <td>Name:</td> <td><?php echo $record->name; echo $record->first_name; ?></td>
              <tr>
              <td>Email:</td><td><?php echo $record->email; ?></td>
              </tr>
              <tr>
              <td>Phone:</td><td><?php echo $record->phone; ?></td>
              </tr>
              <tr>
              <td>Speak English:</td><td><?php echo $record->spk_english; ?></td>
              </tr>
              <tr>
              <td>Smoker:</td><td><?php echo $record->smoker; ?></td>
              </tr>
              <tr>
              <td>Email verified:</td><td><?php echo $record->email_verified == 0 ? "NO" : "YES" ; ?></td>
              </tr>
              <tr>
              <td>rating:</td><td><?php echo $record->rating; ?></td>
              </tr>
              <tr>
              <td>Promo code:</td><td><?php echo $record->promo_code; ?></td>
              </tr>
              <tr>
              <td>language:</td><td><?php echo $record->language ==  0 ? "Farsi" : "English" ; ?></td>
              </tr>
              <tr>
              <td>Address:</td><td><?php echo $record->address; echo $record->home_address; ?></td>
              </tr>
              <tr>
              <td>Work Address:</td><td><?php echo $record->work_address; ?></td>
              </tr>
              <tr>
              <td>Profile Pic:</td><td><img src="<?php echo $record->profile_pic; ?>" width='200'/></td>
              </tr>
              <tr>
              <td>Vehicle Type:</td><td><?php echo $record->vehicle_type; ?></td>
              </tr>
              <tr>
              <td>Added By:</td><td><?php echo $record->added_by; ?></td>
              </tr>
              <tr>
              <td>Registered From:</td><td><?php echo date('d-M-Y H:i:s', strtotime($record->date_created));?></td>
              </tr>
              <?php } ?>
              </tbody>
              </table>
              <br>
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