<head>
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">
<script type= "text/javascript">
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
<script type="text/javascript">
function fnc(value, min, max) 
{
    if(parseInt(value) < 0 || isNaN(value)) 
        return 0; 
    else if(parseInt(value) > 100) {
       alert('Number is greater than 100'); 
       return 0;
     }
    else return value;
}
</script>
</head>

<div class="loginbg">
    <div class="container">
      <h1 class="text-center mT100 white">Add Promo Information Here</h1>
      <div class="form-signin ">

        <?php echo validation_errors(); ?>
        <?php echo form_open('Dashboard/Add_promo'); ?>    
         <div class="mT30"></div>
    	<!-- address input-->
    <div class="check_boxx">
    <label class="checkbox-inline">
      <input type="radio" id="example_0" name="yes" value="1">Simple Promo
    </label>
    <label class="checkbox-inline">
      <input type="radio" id ="example_1" name="yes" onclick="myFunction()" value="2">Unique Code Generator
    </label>  
    </div>
    <div class="control-group">
    <label class="control-label">Add Promo Code</label>
    <div class="controls">
        <input id="field1" readonly class="form-control" name="promo_code" type="text" value="" placeholder="promo code"
        class="input-xlarge" required>
        <div class='error'><?php //echo form_error('promo_code'); ?></div>
    </div>
    </div>
    <div class="control-group">
        <label class="control-label">Start Date</label>
        <div class="datepicker">
            <input id="field2" readonly class="form-control avg" name="start_date" type="text" value="" placeholder="Start Date" class="input-xlarge" required>
            <div class='error'><?php echo form_error('start_date'); ?></div>
        </div>
    </div>
<div class="control-group">
        <label class="control-label">End Date  (if any—leave option for no expiration)</label>
        <div class="datepicker">
            <input id="field3" readonly class="form-control avg1" name="end_date" type="text" value="" placeholder="End Date"
            class="input-xlarge" >
            <div class='error'><?php //echo form_error('end_date'); ?></div>
        </div>
    </div>
<div class="row">
<div class="col-md-6 col-sm-6">
<div class="control-group">
<input type="radio" id="example_11"  checked ="checked" name="msg" value="1">
        <label class="control-label">Discount Amount (In Tooman)</label>
        <div class="controls">
            <input id="field4" readonly class="form-control" name="dis_amt" onkeypress="return isNumber(event)" type="text" value="" placeholder="Discount Amount"
            class="input-xlarge" required>
            <div class='error'><?php// echo form_error('dis_amt'); ?></div>
        </div>
    </div>
</div>
<div class="col-md-6 col-sm-6">
<div class="control-group">
<input type="radio" id ="example_12" name="msg" value="2">
        <label class="control-label">Discount Percentage</label>
        <div class="controls">
            <input id="field5" readonly  class="form-control" name="dis_percent" onkeypress="return isNumber(event)" type="text"  maxlength="5" onkeyup="this.value = fnc(this.value, 0, 100)" placeholder="Discount Percentage"
            class="input-xlarge" required>
            <div class='error'><?php// echo form_error('dis_percent'); ?></div>
        </div>
    </div>
</div>
</div>
<div class="row">
<div class="col-md-6 col-sm-6">
<div class="control-group">
        <label class="control-label">Number of codes to be Generated</label>
        <div class="controls">
            <input id="field6" readonly class="form-control" name="code_usage" onkeypress="return isNumber(event)" type="text" value="" placeholder="Enter Number" class="input-xlarge" required>
            <div class='error'><?php// echo form_error('code_usage'); ?></div>
        </div>
    </div>
  </div>
  <div class="col-md-6 col-sm-6">
  <div class="hallf_check">
      <label class="checkbox-inline">
      <input type="checkbox" name="myCheck" id="myCheck" value="1">For Multiple Uses
    </label>
    </div>
</div>
</div>
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
 $(function() {  
     var dateFormat = "mm/dd/yy",
      from = $( ".avg" )
        .datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 1
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( ".avg1" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1
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
$(function(){
    $("#example_0, #example_1,#example_11,#example_12").change(function(){
        $("#field1,#field4,#field5,#field6,#field2").attr("readonly",true);
       
        if($("#example_0").is(":checked")){           
            $("#field2").removeAttr("readonly");            
            $("#field3").removeAttr("readonly");                                 
            $("#field1").removeAttr("readonly");
            $("#field1").focus();
        if($("#example_11").is(":checked")){    
            $("#field5").val();
            $("#field4").removeAttr("readonly");
            $("#field4").focus();
            }
            if($("#example_12").is(":checked"))  {
            $("#field4").val();
            $("#field5").removeAttr("readonly");
            $("#field5").focus(); 
            }
        }
        else if($("#example_1").is(":checked")){

            $("#field6").removeAttr("readonly");         
            $("#field3").removeAttr("readonly");            
            $("#field2").removeAttr("readonly");          
            if($("#example_11").is(":checked")){    
            $("#field4").removeAttr("readonly");
            $("#field4").focus();            
        }
        if($("#example_12").is(":checked"))  {
            $("#field5").removeAttr("readonly");
            }
        }
        });
        });
</script>
