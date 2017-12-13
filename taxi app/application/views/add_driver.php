<link href="../public/css/normalize.css" rel="stylesheet" type="text/css"/>
<link href="../public/css/datepicker.css" rel="stylesheet" type="text/css"/>
  <div class="loginbg">
    <div class="container">
      <h1 class="text-center mT100 white">Add Driver Information Here</h1>
      <div class="form-signin ">
      <?php 
      // echo validation_errors(); 
      ?>
       <label style="color:#4CA6EA"><?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?></label> 
<div class="bs-callout bs-callout-warning hidden">
  <h4>Oh snap!</h4>
  <p>This form seems to be invalid :(</p>
</div>

<div class="bs-callout bs-callout-info hidden">
  <h4>Yay!</h4>
  <p>Everything seems to be ok :)</p>
</div>
        <?php 
        // echo form_open_multipart('Driver/add_driver', array('onsubmit' => 'return ValidationEvent()')); 
        echo form_open_multipart('Driver/add_driver',array('id'=>"form")); 
        ?>    

         <div class="mT30"></div>
         <div class="box">
<div class="col-md-6 col-sm-6">
<div class="login-wrap">
<?php // if(isset($_POST[]==)  eho ?>
 
            <div class="form-group ">  
            <label>First Name</label>
              <input type="text" class="form-control"  value="<?php echo $this->input->post('fname'); ?>" id="fname" name="fname" placeholder="First Name" autofocus required="">
			  <div class='error'><?php echo form_error('fname'); ?></div>
            </div>
			<div class="form-group ">  
            <label>Last Name</label>
              <input type="text" class="form-control"  value="<?php echo $this->input->post('lname'); ?>" id="lname" name="lname" placeholder="Last Name" >
			  <div class='error'><?php echo form_error('lname'); ?></div>
            </div>
            <div class="form-group ">
              Select your gender: <input type="radio" <?php if($this->input->post('gender')==0) echo "Checked"; ?> name="gender" value="0"> Male
              <input type="radio" <?php if($this->input->post('gender')==1) echo "Checked"; ?> name="gender" value="1"> Female
        <div class='error'><?php echo form_error('gender'); ?></div>
            </div>
            <div class="form-group ">
            <label>Driver Profile Pic:</label>
              <input type="file" id="profile_pic" name="profile_pic" >
        <div class='error'><?php echo $error; ?></div>
            </div>

            <div class="form-group ">
              <label>phone number</label>
              <input type="text" class="form-control"  value="<?php echo $this->input->post('phone'); ?>" id="phone" name="phone" placeholder="phone number" required>
        <div class='error'><?php echo form_error('phone'); ?></div>
            </div>

            <div class="form-group ">
              <label> Cell Provider</label>
              <select name="cell_provider"  class="form-control">              
               <option <?php if($this->input->post('cell_provider')=="Ritetell") echo "selected"; ?> value="Ritetell">Ritetell</option>
               <option <?php if($this->input->post('cell_provider')=="Irancell") echo "selected"; ?> value="Irancell">Irancell</option>
               <option <?php if($this->input->post('cell_provider')=="Hamrah Aaval") echo "selected"; ?> value="Hamrah Aaval">Hamrah Aaval</option>
              </select> 
              <div class='error'><?php echo form_error('cell_provider'); ?></div>
            </div>

            <div class="form-group ">
              <input type="email" class="form-control"  value="<?php echo $this->input->post('email'); ?>" id="email" name="email" data-parsley-trigger="change" placeholder="Email ID">
        <div class='error'><?php //echo form_error('email'); ?></div>
            </div>

            <!-- address input-->
            <div class="control-group">
                <label class="control-label">Address</label>
                <div class="controls">
                    <input id="address" class="form-control" name="address" type="textarea" value="<?php echo $this->input->post('address'); ?>" placeholder="Apartment, City, Region, Country, etc."
                    class="input-xlarge" required>
                  <div class='error'><?php echo form_error('address'); ?></div>
                </div>
            </div>
            <!-- city input-->
            <!-- <div class="control-group">
                <label class="control-label">City / Town</label>
                <div class="controls">
                    <input id="city" class="form-control" name="city" type="text" value="<?php echo $this->input->post('city'); ?>" placeholder="city" class="input-xlarge">
                    <p class="help-block"></p>
                    <div class='error'><?php echo form_error('city'); ?></div>
                </div>
            </div> -->
            <!-- region input-->
            <!-- <div class="control-group">
                <label class="control-label">State / Province / Region</label>
                <div class="controls">
                    <input id="region" class="form-control" name="region" type="text" value="<?php echo $this->input->post('region'); ?>" placeholder="state / province / region"
                    class="input-xlarge">
                    <div class='error'><?php echo form_error('region'); ?></div>
                    <p class="help-block"></p>
                </div>
            </div> -->
            <!-- postal_code input-->
            <!-- <div class="control-group">
                <label class="control-label">Zip / Postal Code</label>
                <div class="controls">
                    <input id="postal_code" class="form-control" name="postal_code" type="text" value="<?php echo $this->input->post('postal_code'); ?>" placeholder="zip or postal code"
                    class="input-xlarge">
                    <div class='error'><?php echo form_error('postal_code'); ?></div>
                    <p class="help-block"></p>
                </div>
            </div> -->

           <!--  <div class="form-group ">
              <input type="password" class="form-control" value="<?php echo $password; ?>"  id="password" name="password" placeholder="Password">
			  <div class='error'><?php echo form_error('password'); ?></div> -->


<!--  -->
        <div class="form-group ">
              <label>Smoker ?: </label> <input type="radio" name="smoker" <?php if($this->input->post('smoker')==1) echo "Checked"; ?> value="1"> Yes
              <input type="radio" name="smoker" <?php if($this->input->post('smoker')==0) echo "Checked"; ?> value="0"> No
        <div class='error'><?php echo form_error('smoker'); ?></div>
            </div>
        <div class="form-group ">
              <label>Speaks English ?: </label>  
              <select name="spk_english"  class="form-control">
               <option value="0">Select proficiency:</option>
                <option <?php if($this->input->post('spk_english')==1) echo "selected"; ?> value="1">Full Professional Proficiency</option>
                <option <?php if($this->input->post('spk_english')==2) echo "selected"; ?> value="2">Somewhat Working Proficiency</option>
              </select> 
        <div class='error'><?php echo form_error('spk_english'); ?></div>
            </div>
        <div class="form-group ">
          <label class="fnt">background check received:</label> <input type="radio" name="bg_chk" <?php if($this->input->post('bg_chk')==1) echo "Checked"; ?> value="1"> Yes
                <input type="radio" name="bg_chk" <?php if($this->input->post('bg_chk')==0) echo "Checked"; ?> value="0"> No
          <div class='error'><?php echo form_error('bg_chk'); ?></div>
              <label>Document:</label>
                <input type="file" id="document" name="document" >
          <div class='error'><?php echo $error; ?></div>
        </div>

        <div class="form-group ">
          <input type="text" class="form-control"  value="<?php echo $this->input->post('referral_code'); ?>" id="referral_code" name="referral_code" placeholder="Referral Code">
          <div class='error'><?php echo form_error('referral_code'); ?></div>
        </div>

        <div class="control-group">
          <label class="control-label">Driver Commission</label>
            <select name="commission"  class="form-control">
                <?php foreach ($commissionLevels as $cl) { ?>
                    <option <?php if($this->input->post('commissionLevel')==$cl->commissionLevel) echo "selected"; ?> value="<?php echo $cl->id; ?>"><?php echo $cl->commissionLevel; ?></option>
                <?php } ?>  
            </select> 
        </div>
        <div class="control-group">
          <label class="control-label">Commission From</label>
          <div class="datepicker">
              <input id="field2" class="form-control avg" name="commission_from" type="text" value="" placeholder="Start Date" class="input-xlarge">
              <div class='error'><?php echo form_error('commission_from'); ?></div>
          </div>
        </div>
        <div class="control-group">
            <label class="control-label">Commission To</label>
            <div class="datepicker">
                <input id="field3" class="form-control avg1" name="commission_to" type="text" value="" placeholder="End Date"
                class="input-xlarge" >
                <div class='error'><?php //echo form_error('commission_to'); ?></div>
            </div>
        </div>

            <div class="alert alert-block  fade in" style="display:none"></div>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="login-wrap">

            
<!-- Vehicle Type Details -->
<!--         <div class="form-group ">
        <label>vehicle number</label>
              <input type="text" class="form-control"  value="<?php echo $this->input->post('vehicle_number'); ?>" id="vehicle_number" name="vehicle_number" placeholder="vehicle number">
        <div class='error'><?php echo form_error('vehicle_number'); ?></div>
            </div> -->

        

        
<?php 
// foreach ($vehicle as $k) {
//   echo"$k->id"."$k->vehicle_type"."<br>";
// }
// echo "<pre>"; print_r($vehicle); echo "</pre>";die; 

?>
        

<!-- Driver License Details -->
            <div class="form-group ">
            <label>license number: </label><input type="text" class="form-control"  value="<?php echo $this->input->post('license_number'); ?>" id="license_number" name="license_number" placeholder="license number">
            <div class='error'><?php echo form_error('license_number'); ?></div>
            </div>
            <div class="form-group ">
            <label>License Exp. Date: </label><input type="text" class="form-control datepickers"  value="<?php echo $this->input->post('license_exp_date'); ?>" id="license_exp_date" name="license_exp_date" placeholder="License Exp. Date:" >
            <div class='error'><?php echo form_error('license_exp_date'); ?></div>
            </div>
            <div class="form-group ">
            <label>Driver license Image:</label>
            <input type="file" id="license_pic" name="license_pic">
            <div class='error'><?php echo $error; ?></div>
            </div>

<!-- Vehicle insurance details -->
            <div class="form-group ">
            <input type="text" class="form-control"  value="<?php echo $this->input->post('insurance_number'); ?>" id="insurance_number" name="insurance_number" placeholder="insurance number">
            <div class='error'><?php echo form_error('insurance_number'); ?></div>
            </div>
            <div class="form-group ">
              <input type="text" class="form-control datepickers"  value="<?php echo $this->input->post('insurance_exp_date'); ?>" id="insurance_exp_date" name="insurance_exp_date" placeholder="insurance Exp. Date">
            <div class='error'><?php echo form_error('insurance_exp_date'); ?></div>
            </div>
            <div class="form-group ">
            <label>Vehicle insurance Image:</label>
              <input type="file" id="insurance_pic" name="insurance_pic" >
        <div class='error'><?php echo $error; ?></div>
            </div>
            
<!-- Vehicle Registration details -->
            <div class="form-group ">
              <input type="text" class="form-control"  value="<?php echo $this->input->post('registration_number'); ?>" id="registration_number" name="registration_number" placeholder="Registration number">
        <div class='error'><?php echo form_error('registration_number'); ?></div>
            </div>

            <div class="form-group ">
              <input type="number" class="form-control"  value="<?php echo $this->input->post('state_identifier'); ?>" id="state_identifier" name="state_identifier" placeholder="State Identifier">
            <div class='error'><?php echo form_error('state_identifier'); ?></div>
            </div>

            <div class="form-group ">
              <input type="text" class="form-control datepickers"  value="<?php echo $this->input->post('registration_exp_date'); ?>" id="registration_exp_date" name="registration_exp_date" placeholder="registration exp date">
        <div class='error'><?php echo form_error('registration_exp_date'); ?></div>
            </div>
            <div class="form-group ">
              <label>Vehicle Registration Image:</label>
                <input type="file" id="registration_pic" name="registration_pic" >
              <div class='error'><?php echo $error; ?></div>
            </div>

            <div class="form-group ">
              <label>Vehicle Registration Plate Image:</label>
                <input type="file" id="registration_plate_pic" name="registration_plate_pic" >
              <div class='error'><?php echo $error; ?></div>
            </div>

        <div class="form-group ">
          <label>Vehicle Type:</label>
              <select name="vehicle_type"  class="form-control">
              
               <!-- <option value="0" disabled selected>Select vehicle type:</option> -->

               <?php foreach($vehicle as $v){?>
                <option <?php if($this->input->post('vehicle_type')==$v->id) echo "selected"; ?> value="<?php echo $v->id;?>"><?php echo $v->vehicle_type;?></option>
               <?php }?>  
              </select> 
            <div class='error'><?php echo form_error('vehicle_type'); ?></div>
            </div>

            <!-- <div class="form-group ">
              <label>Vehicle Model:</label>
              <input type="text" class="form-control"  value="<?php echo $this->input->post('vehicle_model;'); ?>" id="vehicle_model" name="vehicle_model" placeholder="Vehicle Model">
        <div class='error'><?php echo form_error('vehicle_model'); ?></div>
            </div>
 -->
            <div class="control-group">
        <label class="control-label">Vehicle Models</label>
            <div class="controls">
            <select name="vehicle_model"  class="form-control">
                <?php foreach ($model_list as $model) { ?>
                    <option <?php if($this->input->post('vehicle_model')==$model->model_name) echo "selected"; ?> value="<?php echo $model->model_name; ?>"><?php echo $model->model_name; ?></option>
                <?php } ?>  
            </select> 
            <div class='error'><?php echo form_error('vehicle_model'); ?></div>
          </div>
      </div>

            <div class="form-group ">
              <label>Vehicle Image:</label>
                <input type="file" id="vehicle_pic" name="vehicle_pic" >
              <div class='error'><?php echo $error; ?></div>
            </div>



            <div class="form-group ">
              <label>Is Driver having permit type A ?</label>
              <input type="checkbox" name="vehicle_permit_type" value="A">
              <div class='error'><?php echo form_error('vehicle_permit_type'); ?></div>
            </div>
             
             <div class="control-group">
        <label class="control-label">Start Date</label>
        <div class="datepicker">
            <input id="field4" class="form-control avg" name="from" type="text" value="" placeholder="Start Date" class="input-xlarge">
            <div class='error'><?php echo form_error('from'); ?></div>
        </div>
    </div>
<div class="control-group">
        <label class="control-label">End Date</label>
        <div class="datepicker">
            <input id="field5" class="form-control avg1" name="to" type="text" value="" placeholder="End Date"
            class="input-xlarge" >
            <div class='error'><?php //echo form_error('to'); ?></div>
        </div>
    </div>

            
<!--  -->
            <div class="alert alert-block  fade in" style="display:none"></div>
            

</div>
</div>
        <button class="btn btn-lg btn-login btn-block" id="btnsignup" type="submit">Add Details</button>
        </form>
      </div>
    </div>
  </div>
<script src="<?php echo base_url();?>/public/js/jquery.js"></script>

<script src="<?php echo base_url();?>/public/js/jquery-1.8.3.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="<?php echo base_url();?>/public/js/parsley.min.js"></script>
<script type="text/javascript">
  // $(function () {
  //   $('#form').parsley().on('field:validated', function() {
  //     var ok = $('.parsley-error').length === 0;
  //     $('.bs-callout-info').toggleClass('hidden', !ok);
  //     $('.bs-callout-warning').toggleClass('hidden', ok);
  //   })
  //   .on('form:submit', function() {
  //     var otherInstance = $('#fname').parsley({
  //       minlength: 10
  //     });
  //     console.log(otherInstance.options);
  //     return false; // Don't submit form for this demo
  //   });
  // });
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

$(function() {
  $( ".datepickers" ).datepicker({ dateFormat: 'dd-mm-yy' });
});

 $(function() {  
     var dateFormat = "yy-mm-dd",
      from = $( ".avg" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 1,
          dateFormat: 'yy-mm-dd'
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( ".avg1" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'yy-mm-dd'
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      } 
      return date;
    }
});
</script>