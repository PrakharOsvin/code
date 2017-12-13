<div class="loginbg">
    <div class="container">
      <h1 class="text-center mT100 white">Add permission Information Here</h1>
      <div class="form-signin ">

        <?php// echo validation_errors(); ?>
        <?php echo form_open('Dashboard/add_permission'); ?>
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
		    <label class="control-label">Add permission Name</label>
		    <div class="controls">
		        <input id="permission_name" class="form-control" name="permission_name" type="text" value="" placeholder="permission name"
		        class="input-xlarge">
		        <div class='error'><?php echo form_error('permission_name'); ?></div>
		    </div>
		</div>
    <div class="control-group">
        <label class="control-label">Add permission Description</label>
        <div class="controls">
            <input id="permission_description" class="form-control" name="permission_description" type="text" value="" placeholder="permission Description"
            class="input-xlarge">
            <div class='error'><?php echo form_error('permission_description'); ?></div>
        </div>
    </div>
		<div class="alert alert-block  fade in" style="display:none"></div>
        <button class="btn btn-lg btn-login btn-block" id="btnsignup" type="submit">Add Details</button>
    	</form>
      <div class="control-group">
        
    	    <div class="controls">
            <select name="permission"  class="form-control">
                <option value="">Available permissions:</option>
                <?php foreach ($permission_list as $permission) { ?>
                    <option value="<?php echo $permission->id; ?>"><?php echo $permission->permission_name; ?></option>
                <?php } ?>  
            </select> 
            <div class='error'><?php echo form_error('permission'); ?></div>
          </div>
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