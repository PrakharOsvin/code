<link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap-datepicker.min.css" />

<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">Ride Details</header>
     <!--  <h3>DateTimePicker</h3>
  <input type="text" value="" id="datetimepicker"/><br><br> -->
      <div class="panel-body">      
        <div class="adv-table table-responsive">
        
                        <form method="post" id="register-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
                          <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
                            <tbody>
                               <?php
                                foreach($ride_info as $record){
                                  // print_r($one_user_info);die;
                                  ?>
                                 <tr>
                                     <td>Ride Id:</td><td><?php echo $record->id; ?></td>
                                     <input type = "hidden" name="ride_id" value="<?php echo $record->id;?>">
                                </tr>
                                <tr>
                                  <td>Passenger Id:</td>
                                  <td><?php echo $record->user_id; ?></td> 
                                  <input type = "hidden" name="user_id" value="<?php echo $record->user_id;?>">
                                </tr>
                                <tr>
                                  <td>Driver Id:</td>
                                  <td><?php echo $record->address; echo $record->driver_id;?></td> 
                                  <input type = "hidden" name="driver_id" value="<?php echo $record->driver_id;?>">
                                </tr>
                                <tr>
                                  <td>Pickup Location:</td>
                                  <td><input type = "text" name="pickup_location" value="<?php echo $record->pickup_location;?>"></td> 
                                </tr>
                                <tr>
                                  <td>Dropoff Location:</td>
                                  <td><input type = "text" name="dropoff_location" value="<?php echo $record->dropoff_location;?>"></td> 
                                </tr>
                                <tr>
                                <tr>
                                  <td>Ride Status:</td>
                                  <td>
                                  <select name="status">
                                    <?php if($record->status==1) {?>
                                   
                                    <option value="2" <?php if ($record->status==1) {
                                      echo "selected disabled hidden";
                                    } ?> >Booked</option>
                                    <option value="3" <?php if ($record->status==3) {
                                      echo "selected='selected'";
                                    } ?> >Started</option>                                    
                                    <option value="51" <?php if ($record->status==50) {
                                      echo "selected='selected'";
                                    } ?> >Cancel Ride </option>                                  
                                  </select>

                                  <?php } if($record->status==2) {?>
                                   
                                    <option value="2" <?php if ($record->status==2) {
                                      echo "selected disabled hidden";
                                    } ?> >Arrived</option>
                                    <option value="3" <?php if ($record->status==3) {
                                      echo "selected='selected'";
                                    } ?> >Started</option>                                    
                                    <option value="51" <?php if ($record->status==50) {
                                      echo "selected='selected'";
                                    } ?> >Cancel Ride </option>                                  
                                  </select>

                                  <?php } if($record->status== 3){  ?>
                                  <option value="3" <?php if ($record->status==3) {
                                      echo "selected disabled hidden";
                                    } ?> >Started</option>
                                 <option value="4" <?php if ($record->status==4) {
                                      echo "selected='selected'";
                                    } ?> >Complete Ride</option>
                                    </select>
                                    <?php } 
                                    else{ ?>
                                     <option value="1" >None Selected</option>
                                    <?php }?>
                                  </td>
                                </tr>
                                <tr>
                                  <td>Accepted On:</td>
                                  <td>
                                    <input type = "text" name="accept_datetime" class="datetimepicker" value="<?php echo $record->accept_datetime; ?>">
                                    <input type = "hidden" name="accept_datetime" class="datetimepicker1" value="<?php echo $record->accept_datetime; ?>">
                                  </td> 
                                </tr>
                                <tr>
                                  <td>Arrived On:</td>
                                  <td>
                                    <input type = "text" name="arrived_datetime" class="datetimepicker" value="<?php echo $record->arrived_datetime; ?>">
                                    <input type = "hidden" name="arrived_datetime" class="datetimepicker1" value="<?php echo $record->arrived_datetime; ?>">
                                  </td> 
                                </tr>
                                <tr>
                                  <td>Started On:</td>
                                  <td>
                                    <input type = "text" name="job_start_datetime" class="datetimepicker" value="<?php echo $record->job_start_datetime;?>">
                                    <input type = "hidden" name="job_start_datetime" class="datetimepicker1" value="<?php echo $record->job_start_datetime;?>">
                                  </td> 
                                </tr>
                                <tr>
                                  <td>Completed On:</td>
                                  <td>
                                  <input type = "text" name="job_completed_datetime" class="datetimepicker" value="<?php echo $record->job_completed_datetime; ?>">
                                  <input type = "hidden" name="job_completed_datetime" class="datetimepicker1" value="<?php echo $record->job_completed_datetime; ?>">
                                </td> 
                                </tr>
                                <tr>
                                  <td>Estimate:</td>
                                  <td>
                                  <?php $estimate = explode(',', $record->estimate); ?>
                                    KM:<input type = "text" name="KM" value="<?php echo $estimate[0]; ?>"><br/>
                                    Time:<input type = "text" name="Time" value="<?php echo $estimate[1]; ?>"><br/>
                                    Fare:<input type = "text" name="Fare" value="<?php echo $estimate[2]; ?>">
                                  </td> 
                                </tr>
                                <tr>
                                  <td>Fare:</td>
                                  <td><input type = "text" name="fare" value="<?php echo $record->fare;?>"></td> 
                                </tr>
                                <tr>
                                  <td>Payable Amount:</td>
                                  <td>
                                  <input type = "text" name="payable_amount" value="<?php echo $record->payable_amount; ?>">
                                  </td> 
                                </tr>
                                <tr>
                                  <td>Payment Method:</td>
                                  <td>
                                  <select name="status1" id="target">
                                    <option value="1" <?php if ($record->payment_method==1) {
                                      echo "selected='selected'";
                                    } ?> >Zarinpal</option>
                                    <option value="2" <?php if ($record->payment_method==2) {
                                      echo "selected='selected'";
                                    } ?> >Wallet</option>
                                    <option value="3" <?php if ($record->payment_method==3) {
                                      echo "selected='selected'";
                                    } ?> >Jiring</option>
                                    <option value="0" <?php if ($record->payment_method==0) {
                                      echo "selected='selected'";
                                    } ?> >Cash</option>
                                  </select>
                                  </td> 
                                </tr>
                                <tr>
                                  <td>Payment Status:</td>
                                  <td><input type = "text" name="payment_status" value="<?php echo $record->payment_status;?>"></td> 
                                </tr>
                                <tr>
                                  <td>Payment RefID:</td>
                                  <td><input type = "text" name="payment_RefID" value="<?php echo $record->payment_RefID; ?>"></td> 
                                </tr>
                                <tr>
                                  <td>Booked On:</td>
                                  <td><?php echo $record->date_created; ?></td>
                                </tr>               
                             
                            </tbody>
                             <?php } ?>
                        </table>
                      

                        <br>
                        <input name="submit" type="submit" class="btn btn-primary" value="Submit">
                        <br>
                        <br>
                        <a href="<?php echo base_url('Users/users_list'); ?>" class="btn btn-success">Back to user List</a>
                        <a href="<?php echo base_url('Dashboard/profile').'/'.$one_user_info[0]->id; ?>" name="view" class='btn btn-success'>View profile</a>
                      </form>
                    </div>
                  </div>

                </section>
    </section>
</section>

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


<script src="<?php echo base_url();?>public/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>public/js/bootstrap-datepicker.fa.min.js"></script>

<script src="<?php echo base_url();?>/public/js/convertCal.js"></script>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
      var dt = $('.datetimepicker');
      // console.log(dt.length);
      // console.log(dt);
      for (var i = 0; i < dt.length; i++) {
        var gg = $('.datetimepicker')[i].value;
        // console.log(gg);
        var result1 = gg.split(' ');
        var result = result1[0].split('-');
        console.log(result);
        // console.log(parseInt(result[1]));
        var j = toJalaali(parseInt(result[0]),parseInt(result[1]),parseInt(result[2]));
           // console.log(p);
        // var parray = p.split(',');
        // console.log(j);
        // return false;
        var nd = j.jy+"-"+j.jm+"-"+j.jd;
        // console.log(nd);
        // return false;

        $('.datetimepicker')[i].value = nd+' '+result1[1];
        // console.log($('.datetimepicker')[i].value);
      }
    });

  /*==============*/
  $(function() {
    console.log( "ready!" );
    $( "#target" ).change(function() {
      alert( "Handler for .change() called." );
    });
  });
</script>
<script>
    $(document).ready(function() {
        $(".datetimepicker").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: "yy-mm-dd H:i:s"
        });
    
        $("#datepicker1").datepicker();
        $("#datepicker1btn").click(function(event) {
            event.preventDefault();
            $("#datepicker1").focus();
        })
    
        $("#datepicker2").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true
        });
    
        $("#datepicker3").datepicker({
            numberOfMonths: 3,
            showButtonPanel: true
        });
    
        $("#datepicker4").datepicker({
            changeMonth: true,
            changeYear: true
        });
    
        $("#datepicker5").datepicker({
            minDate: 0,
            maxDate: "+14D"
        });
    
        $("#datepicker6").datepicker({
            isRTL: true,
            dateFormat: "d/m/yy"
        });
    });
</script>
