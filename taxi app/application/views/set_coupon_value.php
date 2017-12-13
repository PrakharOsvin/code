<head>
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">

</head>

<div class="loginbg">
    <div class="container">
      <h1 class="text-center mT100 white">Set Coupon Value Information Here</h1>
      <div class="form-signin ">

        <?php echo validation_errors(); ?>
        <?php echo form_open('Dashboard/set_coupon_value'); ?>    
         <div class="mT30"></div>
<?php 
foreach ($coupon_value as $key => $value) { ?>
    <div class="control-group">
      <label class="control-label"><?php echo ($value->cupon_type == 1) ? 'Passenger Refferal' : 'Driver Refferal' ;?></label>
      <div class="controls">
          <input id="" class="form-control" name="<?php echo ($value->cupon_type == 1) ? 'passenger_refferal' : 'driver_refferal' ;?>" type="number" value="<?php echo $value->value; ?>"
          class="input-xlarge" required>
          <div class='error'><?php //echo form_error('promo_code'); ?></div>
      </div>
    </div>
<?php }
?>

		<div class="alert alert-block  fade in" style="display:none"></div>
        <button class="btn btn-lg btn-login btn-block" id="btnsignup" name="submit" type="submit">Add Details</button>
    	</form>
 	 </div>
	</div>
</div>
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
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script type="text/javascript">
function myFunction() {
    document.getElementById("myCheck").disabled = true;
}
$(document).ready(function(){  
  $('#hidden-table-info').DataTable();
});
 $(function() {});
$(function(){});
</script>
