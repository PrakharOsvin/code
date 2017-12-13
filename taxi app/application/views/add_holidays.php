<link href="../public/css/normalize.css" rel="stylesheet" type="text/css"/>
<link href="../public/css/datepicker.css" rel="stylesheet" type="text/css"/>
<div class="loginbg">
    <div class="container">
      <h1 class="text-center mT100 white">Add Holidays Here</h1>
      <div class="form-signin ">
   
<form name="add_holidays" method="GET" action="add_holidays">
         <div class="mT30"></div>
		<!-- address input-->
		<div class="control-group">
		    <label class="control-label">Add Holidays</label>
		    <div class="controls">
		        <input id="add_holiday" class="form-control datepicker" name="add_holiday" type="text" placeholder="DD-MM-YYYY">
		        <div class='error'><?php echo form_error('add_holiday'); ?></div>
		    </div>
		</div>
		<div class="alert alert-block  fade in" style="display:none"></div>
        <button class="btn btn-lg btn-login btn-block" id="btnsignup" type="submit">Add Details</button>
    	</form>
 	 </div>
	</div>
</div>
<script src="<?php echo base_url();?>/public/js/jquery.js"></script>
<script src="<?php echo base_url();?>/public/js/jquery-1.8.3.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
  $(function() {
    $( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
  });
  </script>

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

<script type="text/javascript">
$(document).ready(function(){
  $('#hidden-table-info').DataTable();
});
</script>