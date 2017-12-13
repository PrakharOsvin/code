  <link href="http://www.malot.fr/bootstrap-datetimepicker/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<link rel="stylesheet" href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css">
<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<style type="text/css">
  
  .dropdown-menu {
  background-clip: padding-box;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.176);
  display: none;
  float: left;
  font-size: 14px;
  left: 724px !important;
  list-style: outside none none;
  margin: 2px 0 0;
  min-width: 160px;
  padding: 5px 0;
  position: absolute;
  top: 25%;
  z-index: 1000;
}

</style>


<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">User Info</header>
      <div class="panel-body">    	
        <div class="adv-table table-responsive">
<form method="post" id="register-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
           <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
		<tbody>		
    <tr>
         <td>Res #:</td><td><?php echo $userBookedInfo['0']->trip_id; ?></td>
      </tr>
		
			<tr>
			   <td>Driver On Location Time:</td> 
       <td>                
         <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" required name="driverLocationTime" value="<?php echo $userBookedInfo['0']->arrived_datetime; ?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div></td> 
			<tr>
			<tr>
				 <td>Passenger in Car Time:</td><td>
                <div class='input-group date' id='datetimepicker3'>
                    <input type='text' class="form-control" required name="inCar_time" value="<?php echo $userBookedInfo['0']->job_start_datetime; ?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
         </td>
			</tr>
        <tr>
         <td>Passenger DropOff Time :</td>
         <td>
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" required name="dropoff_time" value="<?php echo $userBookedInfo['0']->job_completed_datetime; ?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>


         </td>
      </tr>
			<tr>
			   <td>Time (Booked):</td><td><?php echo $userBookedInfo['0']->accept_datetime; ?></td>
			    </tr>	
            <tr>
         <td>Rate/Price:</td><td> <input type= "text" required name = "type" value="<?php echo $userBookedInfo['0']->fare; ?>" ></td>
         <input type= "hidden" name = "hidden_id" value="<?php echo $userBookedInfo['0']->trip_id; ?>" >
      </tr>
        <tr>
         <td>Type Of Vehicle:</td><td><?php echo $userBookedInfo['0']->vehicle_type; ?></td>
      </tr>	
        <tr>
         <td>Payment Methods:</td>
         <td> 
          <select name="status1">
            <option value="1" <?php if ($userBookedInfo['0']->payment_method==2) {
              echo "selected='selected'";
            } ?> >Wallet</option>
            <option value="0" <?php if ($userBookedInfo['0']->payment_method==0) {
              echo "selected='selected'";
            } ?> >Cash</option>
          </select>      
        </td>
      </tr>	
        <tr>
         <td>Driver :</td><td><a href="<?php echo base_url('Dashboard/profile').'/';
         echo $userBookedInfo['0']->driver_id;
          ?>"><?php echo $userBookedInfo['0']->driver; ?></a> </td>
      </tr>
        <tr>
         <td>Passenger :</td><td><a href="<?php echo base_url('Dashboard/profile').'/';
         echo $userBookedInfo['0']->client_id;
          ?>"><?php echo $userBookedInfo['0']->client; ?></a></td>
      </tr>
        <tr>
         <td> from :</td><td><?php echo $userBookedInfo['0']->pickup_location; ?></td>
      </tr>
        <tr>
         <td>To  :</td><td><?php echo $userBookedInfo['0']->dropoff_location; ?></td>
      </tr>
        <tr>
         <td>Ratings :</td><td><?php echo $userBookedInfo['0']->rating; ?></td>
      </tr>
			<tr>

				  <td>passenger dropped off time  :</td><td><?php echo $userBookedInfo['0']->job_completed_datetime; ?></td>
			</tr>
		</tbody>
	</table>
  <br>
<input name="submit" type="submit" class="btn btn-primary" value="Submit">
<br>
<br>
<a href="<?php echo base_url('Dashboard/bookedRides') ?>" class="btn btn-success">Back to Booked List</a>
  </form>
      </div>
    </div>
  </section>


  <!-- page end-->
</section>
</section>
<script src="<?php echo base_url();?>/public/js/jquery.js"></script>
<script src="<?php echo base_url();?>/public/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url();?>/public/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url();?>/public/js/jquery.dcjqaccordion.2.7.js"></script>






<script>
  
$(document).ready(function(){

    $(".mymore").click(function(e){
    e.preventDefault();
    $val= $(this).data("attr");
    if($val=="1"){
      $("#more").hide(); 
      $("#less").show(500);
    }else{
      $("#less").hide(500);
      $("#more").show();      
    }
  });
});
</script>
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

<script src="http://www.higrit.com/public/front-end/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('#hidden-table-info').DataTable();
});
</script>


        <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker(); 
            });

            $(function () {
                $('#datetimepicker2').datetimepicker();
            });

            $(function () {
                $('#datetimepicker3').datetimepicker();
            });


        </script>

 