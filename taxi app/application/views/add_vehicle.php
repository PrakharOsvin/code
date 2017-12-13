<div class="loginbg">
    <div class="container">
   
      <h1 class="text-center mT100 white">Add Vehicle Information Here</h1>
      <div class="form-signin ">

        <?php// echo validation_errors(); ?>
        <?php echo form_open('Dashboard/add_vehicle'); ?>    
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
		<div class="control-group">
		    <label class="control-label">Add Vehicle Type</label>
		    <div class="controls">
		        <input id="vehicle_type" class="form-control" name="vehicle_type" type="text" value="<?php echo $this->input->post('vehicle_type'); ?>" placeholder="vehicle_type"
		        class="input-xlarge">
		        <div class='error'><?php echo form_error('vehicle_type'); ?></div>
		    </div>
		</div>

    <div class="control-group">
        <label class="control-label">capacity</label>
        <div class="controls">
            <input id="capacity" class="form-control" name="capacity" type="number" value="<?php echo $this->input->post('capacity'); ?>" placeholder="capacity"
            class="input-xlarge">
            <div class='error'><?php echo form_error('capacity'); ?></div>
        </div>
    </div>    

    <div class="control-group">
        <label class="control-label">Base Rate</label>
        <div class="controls">
            <input id="base_rate" class="form-control" name="base_rate" type="number" value="<?php echo $this->input->post('base_rate'); ?>" placeholder="Base Rate"
            class="input-xlarge">
            <div class='error'><?php echo form_error('base_rate'); ?></div>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Per Km</label>
        <div class="controls">
            <input id="per_km" class="form-control" name="per_km" type="number" value="<?php echo $this->input->post('per_km'); ?>" placeholder="Per Km"
            class="input-xlarge">
            <div class='error'><?php echo form_error('per_km'); ?></div>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Per Min</label>
        <div class="controls">
            <input id="per_min" class="form-control" name="per_min" type="number" value="<?php echo $this->input->post('per_min'); ?>" placeholder="Per Min"
            class="input-xlarge">
            <div class='error'><?php echo form_error('per_min'); ?></div>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Waiting Charge</label>
        <div class="controls">
            <input id="waiting_charge" class="form-control" name="waiting_charge" type="number" value="<?php echo $this->input->post('waiting_charge'); ?>" placeholder="Waiting Charge"
            class="input-xlarge">
            <div class='error'><?php echo form_error('waiting_charge'); ?></div>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Traffic Charges</label>
        <div class="controls">
            <input id="traffic_charges" class="form-control" name="traffic_charges" type="number" value="<?php echo $this->input->post('traffic_charges'); ?>" placeholder="Traffic Charges"
            class="input-xlarge">
            <div class='error'><?php echo form_error('traffic_charges'); ?></div>
        </div>
    </div>
		
		<div class="alert alert-block  fade in" style="display:none"></div>
        <button class="btn btn-lg btn-login btn-block" id="btnsignup" type="submit">Add Details</button>
    	</form>
    	<div class="form-group ">
              <select name="vehicle_list"  class="form-control">
              
               <option value="0" disabled selected>Available vehicle types:</option>
               <?php foreach($vehicle_type as $vehicle){?>
                <option value="<?php echo $vehicle->vehicle_type;?>"><?php echo $vehicle->vehicle_type;?></option>
               <?php }?>  
              </select> 
        <div class='error'><?php echo form_error('vehicle_list'); ?></div>
            </div>
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

<script type="text/javascript">
$(document).ready(function(){
  $('#hidden-table-info').DataTable();
});
</script>