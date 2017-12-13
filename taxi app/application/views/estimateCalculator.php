<style type="text/css">
.form-signin .control-label {
  padding: 12px 0 !important ;
  width: 100% !important;  
}
</style>

<div class="loginbg">
    <div class="container">
 <h1 class="text-center mT100 white">Add Information Here</h1>
        <div class="form-signin ">
            <label class="successmessage"><?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?></label>
            <?php // echo validation_errors(); ?>
            <?php echo form_open('Dashboard/estimateCalculator'); ?>    
             
            <div class="mT30"></div>
            <!-- address input-->
            <div class="control-group">
                <label class="control-label" >Pickup Location</label>
                <div class="controls">
                    <input class="form-control"   id="search-box" name="pickup_location" required type="text" value= "<?php echo $pickup_location; ?>" placeholder="Pickup Location"
                           class="input-xlarge">
                    <div class='error'><?php echo form_error('name'); ?></div>
                </div>
                           <select name='pickup_locations' id="suggesstion-test"></select>
                 <label class="control-label" >Dropoff Location</label>
                <div class="controls">
                    <input  id="search-box1" class="form-control" name="dropoff_location" required type="text" placeholder="Dropoff Location" value= "<?php echo $dropoff_location; ?>" 
                           class="input-xlarge">
                    <div class='error'><?php echo form_error('name'); ?></div>
                </div>
             
                          <select name='dropoff_locations' id="suggesstion-test2"></select>
                <label class="control-label">Car Type</label>
                <div class="controls">
                     <select name = "data"> 
                 
                  <?php   
                  foreach($car_type as $data){
                        ?>  <option value="<?php echo $data->id;?>"><?php echo $data->vehicle_type;?></option>
                        <?php } ?>
                        </select>
                    <div class='error'><?php echo form_error('name'); ?></div>
                </div>
                <br>
                <label class="control-label">Estimated Fare :</label>
                <div class="controls">
                  <p><?php if(!empty($estimated_fare))
                   echo $estimated_fare." Tooman";
                   ?></p>
                  <!--     <input id="email" class="form-control" name="email" type="text" value="" placeholder="email"
                           class="input-xlarge"> 
                    <div class='error'><?php //echo form_error('email'); ?></div>-->
                </div>        

                <label class="control-label">Distance :</label>
                <div class="controls">
                  <p><?php if(!empty($distance))
                   echo $distance;
                   ?></p>
                </div>        

                <label class="control-label">Estimated Time :</label>
                <div class="controls">
                  <p><?php if(!empty($duration))
                   echo $duration;
                   ?></p>
                </div>            
              
            </div>
            <br>
            <div class="controls">
                <div class="alert alert-block  fade in" style="display:none"></div>
                <input class="btn btn-lg btn-login btn-block" name= "submit" id="btnsignup" type="submit"></input>
            </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>/public/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?php echo base_url(); ?>/public/js/owl.carousel.js" ></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.customSelect.min.js" ></script>
<script src="<?php echo base_url(); ?>/public/js/respond.min.js" ></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>

<!--common script for all pages-->
<script src="<?php echo base_url(); ?>/public/js/common-scripts.js"></script>

<!--script for this page-->
<script src="<?php echo base_url(); ?>/public/js/sparkline-chart.js"></script>
<script src="<?php echo base_url(); ?>/public/js/easy-pie-chart.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
  $("#search-box1").keyup(function(){
    $.ajax({
    type: "POST", 
   url: "<?php echo base_url() ?>Dashboard/getDropLocation", 
    data:'dropoff_loc='+$(this).val(),
    beforeSend: function(){
     /* $("#search-box1").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");*/
    },
    success: function(data){
    	// alert(data);
      $("#suggesstion-test2").html(data);
     /* $("#search-box1").css("background","#FFF");*/
    }
    });
  });
});
//To select country name
function selectCountry(val) {
$("#search-box1").val(val);
//$("#suggesstion-test1").hide();
}
$('#suggesstion-test2').on('change',function() {
    $('#search-box1').val($(this).val());
})
</script>
<script type="text/javascript">
  $(document).ready(function(){
  $("#search-box").keyup(function(){
    $.ajax({
    type: "POST",
    url: "<?php echo base_url() ?>Dashboard/getPickupLocation", 
     data:'pickup_loc='+$(this).val(),
    beforeSend: function(){
      // $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
    },
    success: function(data){
      // console.log(data.var);
      // $("#suggesstion-test").show();      
      $("#suggesstion-test").html(data);
      // $("#search-box").css("background","#FFF");
   
    }
    });
  });
});
//To select country name
function selectCountry(val) {
$("#search-box").val(val);
// $("#suggesstion-box").hide();
}
$('#suggesstion-test').on('change',function() {
    $('#search-box').val($(this).val());
})
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#hidden-table-info').DataTable();
    });
</script>


