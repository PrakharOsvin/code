<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
  <section id="main-content">
    <section class="wrapper site-min-height">
         <!-- page start-->
      <section class="panel">
        <header class="panel-heading">Calling Mode</header>
          <div class="panel-body">      
            <form action="<?php echo base_url('Dashboard/callingMode'); ?>" method="post">
              <label class="radio-inline"><input type="radio"  id="example_1" name="callingMode" value="normal" <?php   if ($callingMode->callingMode=='normal') {
                echo "checked='checked'";
              } ?> >Normal</label>
              <label class="radio-inline"><input type="radio"  id="example_0"  name="callingMode" value="secure" <?php if ($callingMode->callingMode=='secure') {
                echo "checked='checked'";
              } ?> >Secure</label>
              </br>
              </br>
              <b>Freeline Number :</b></br>

              <input type="text"  name="call" id="field1" value="<?php echo $callingMode->number ?>"> </input>
              <br><br>
              <b>Call Time :</b></br>

              <input type="number"  name="CallTime" id="CallTime" value="<?php echo $callingMode->CallTime/60 ?>"> </input>
              <br><br>
              <b>Beep Time :</b></br>
              <b>Min :</b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>Sec :</b></br>
              <input type="number"  name="BeepTimeMin" id="BeepTimeMin" value="<?php echo ($callingMode->BeepTime/60)%60 ?>"> </input>
              <input type="number"  name="BeepTimeSec" id="BeepTimeSec" value="<?php echo $callingMode->BeepTime%60 ?>"> </input>
              <br><br>
              <button name="sub" type="submit">Submit</button>
            </form>
          </div>
      </section>
      <!-- page end-->
    </section>
  </section>
<script src="<?php echo base_url();?>/public/js/jquery.js"></script>
<script type="text/javascript">
 $('#CallTime').on('input',function(e){
     var CallTime = $('#CallTime').val();
     var sec = (CallTime/2)*60;
     var min = Math.floor(sec/60);
     $('#BeepTimeMin').val(min);
     $('#BeepTimeSec').val(sec%60);
     // alert(CallTime);
    });
</script>
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
$(function(){
    $("#example_0, #example_1").change(function(){
        $("#field1").val("").attr("readonly",true);
        if($("#example_0").is(":checked")){
            $("#field1").removeAttr("readonly");
            $("#field1").focus();
        }       
    });
});
$(document).ready(function(){
  $('.hidden-table-info').DataTable({
    stateSave: true,
  });
});
</script>