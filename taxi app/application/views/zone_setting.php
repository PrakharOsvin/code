<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/css/jquery.datetimepicker.css"/>
<div class="loginbg">
    <div class="container">
      <h1 class="text-center mT100 white">Set Smog scenario Here</h1>
      <div class="form-signin ">

        <?php// echo validation_errors(); ?>
        <?php echo form_open('Dashboard/zone_setting'); ?>    
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

        <div class="mT30"></div>
    		<!-- address input-->
    		<div class="control-group setting_box">
  		    <label class="radio-inline"><input type="radio" name="scenario" value="A" <?php if ($record->scenario=='A') {
            echo "checked";
          }?> >Scenario A</label>
          <label class="radio-inline"><input type="radio" name="scenario" value="B" <?php if ($record->scenario=='B') {
            echo "checked";
          }?> >Scenario B</label>
          <br>
          <label>From</label>
          <input type = "text" name="start_date" class="datetimepicker" value="<?php echo $record->start_date;?>">
          <label>To</label>
          <input type = "text" name="end_date" class="datetimepicker" value="<?php echo $record->end_date;?>">
    		</div>
        <br>
    		<div class="alert alert-block  fade in" style="display:none"></div>
            <button class="btn btn-lg btn-login btn-block" id="btnsignup" name="submit" value="submit" type="submit">Submit</button>
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
<script class="include" type="text/javascript" src="<?php echo base_url();?>/public/js/jquery.datetimepicker.full.js"></script>
<script type="text/javascript">
  var d = new Date();
  $.datetimepicker.setLocale('en');
  $('.datetimepicker').datetimepicker({
  dayOfWeekStart : 1,
  lang:'en',
  startDate: d,
  format:'Y-m-d H:i'
  });
  // $('.datetimepicker').datetimepicker({value:'2015/04/15 05:03',step:10});
</script>

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