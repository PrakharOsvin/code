<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">Ride Info</header>
      <div class="panel-body">    	
        <div class="adv-table table-responsive">

           <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
		<tbody>
		  
<?php 
// echo "<pre>"; print_r($ride_info); echo "</pre>"; die;
?>
			<tr>
				 <td>Trip Id:</td><td><?php echo $ride_info['0']->id; ?></td>
			</tr>
		  <tr>
				 <td>User Id:</td><td><?php echo $ride_info['0']->user_id; ?></td>
			</tr>
			<tr>
			   <td>Client Name:</td> <td> <a href="<?php echo base_url('Dashboard/profile')."/".$ride_info['0']->user_id;
			   ?>"><?php echo $ride_info['0']->cleint; ?></a></td>
			<tr>
			<tr>
				 <td>Driver Id:</td><td><?php echo $ride_info['0']->driver_id; ?></td>
			</tr>
			<tr>
			   <td>Driver Name:</td> <td><a href="<?php echo base_url('Dashboard/profile')."/".$ride_info['0']->driver_id;
			   ?>"><?php echo $ride_info['0']->name; ?></a></td>
			<tr>
				 <td>Driver Email:</td><td><?php echo $ride_info['0']->email; ?></td>
			</tr>
			<tr>
				 <td>Vehicle:</td><td><?php echo $ride_info['0']->vehicle; ?></td>
			</tr>
			<tr>
				 <td>Driver Phone:</td><td><?php echo $ride_info['0']->phone; ?></td>
			</tr>
			<tr>
				 <td>Pickup Location:</td><td><?php echo $ride_info['0']->pickup_location; ?></td>
			</tr>
			<tr>
				 <td>Dropoff Location:</td><td><?php echo $ride_info['0']->dropoff_location; ?></td>
			</tr>
			<tr>
				 <td>Way Points:</td><td><?php echo $ride_info['0']->way_points; ?></td>
			</tr>
			<tr>
				  <td>Date Created:</td><td><?php echo date('d-M-Y g:i a', strtotime($ride_info['0']->date_created)); ?></td>
			</tr>
			<tr>
				  <td>Ride Accepted:</td><td><?php echo date('d-M-Y g:i a', strtotime($ride_info['0']->accept_datetime)); ?></td>
			</tr>
			<tr>
				  <td>Ride Started:</td><td><?php echo date('d-M-Y g:i a', strtotime($ride_info['0']->job_start_datetime)); ?></td>
			</tr>
			<tr>
				  <td>Ride Completed:</td><td><?php echo date('d-M-Y g:i a', strtotime($ride_info['0']->job_completed_datetime)); ?></td>
			</tr>
			<tr>
				 <td>Rating:</td><td><?php echo $ride_info['0']->rating; ?></td>
			</tr>
			<tr>
				 <td>Feedback:</td><td><?php if(strlen($ride_info['0']->feedback)<50){
                  echo $ride_info['0']->feedback;            
                }
                else{
                    echo "<span id='more'>".ucfirst(substr($ride_info['0']->feedback,0,50))."....<a href='' class='mymore' style='color:red;' data-attr='1'>More</a></span>";
                    echo "<span style='display:none;' id='less'>".$ride_info['0']->feedback."....<a href='' class='mymore' style='color:red;' data-attr='0'>Less</a></span>";
                } ?></td>
			</tr>
			<tr>
				 <td>Price:</td><td><?php echo $ride_info['0']->price; ?></td>
			</tr>
			<tr>
				 <td>Payment:</td><td><?php echo $ride_info['0']->payment_method; ?></td>
			</tr>
		 
	
		</tbody>
	</table>

	<br>
	<a href="<?php echo base_url('Dashboard/archive') ?>" class="btn btn-success">Back to Archive/history</a>
	<a href="<?php echo base_url('Dashboard/cancelled_trips') ?>" class="btn btn-success">Back to Cancelled Trips</a>
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

<script type="text/javascript">
$(document).ready(function(){
  $('#hidden-table-info').DataTable();
});
</script>

 