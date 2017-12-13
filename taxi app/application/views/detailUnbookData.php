
<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">User Info</header>
      <div class="panel-body">    	
        <div class="adv-table table-responsive">
       
           <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
		<tbody>		
		  <tr>
				 <td>User Id:</td><td><?php echo $userInfo['0']->user_id; ?></td>
			</tr>
			<tr>
			   <td>Client Name:</td> <td><?php echo $userInfo['0']->name; ?></td>
			<tr>
			<tr>
				 <td>Pickup Location:</td><td><?php echo $userInfo['0']->start_address; ?></td>
			</tr>
      <?php if ($userInfo['0']->reason != 0) {?>
      <tr>
         <td>Pickup Latitude & Longitude:</td><td><?php echo $userInfo['0']->pickup_lat;
         echo ", ".$userInfo['0']->pickup_long; ?></td>
      </tr>
      <?php } else {?>
        <tr>
         <td>Distance:</td><td><?php $Distance= $userInfo['0']->distance;
         $disInKm = $Distance/1000;
         echo $disInKm." KM";
         ?></td>
      </tr>
      <?php } ?>
			<tr>
			   <td>Dropoff Location:</td><td>
			    <?php echo $userInfo['0']->end_address; ?></td> 
			    </tr>	
           <?php if ($userInfo['0']->reason != 0) {?>
          <tr>

         <td>Dropoff Latitude & Longtitude :</td><td><?php echo $userInfo['0']->dropoff_lat;
         echo ", ".$userInfo['0']->dropoff_long; ?></td>
      </tr>
       <?php }?>
          <tr>
         <td>Reason :</td><td><?php if($userInfo['0']->reason== 0){
          echo "Fare Check";
          } else{
            echo  "No Driver Available";
            } ?></td>
      </tr>
       <tr>
         <td>Estimated Price:</td><td> <?php
          if(empty($userInfo['0']->estimated_price)){
          $est = explode(',', $userInfo['0']->estimate);
          print_r($est[2]);  ?> Tooman</td>
          <?php }
          else{
          echo  $userInfo['0']->estimated_price;
          echo ' Tooman';
          }?>
      </tr>  
       <?php echo '<tr>'; 
       if(!empty($userInfo['0']->modified_date)){ 
        $date_modified = strtotime($userInfo['0']->modified_date);
       $date_created = strtotime($userInfo['0']->date_create);
        $diff = $date_modified -  $date_created ;   
        ?>
         <td>Time Taken:</td><td> <?php echo $diff; ?> Seconds</td>
      </tr>  <?php }?> 		
			<tr>
				  <td>Date :</td><td><?php echo date('d-M-Y g:i a', strtotime($userInfo['0']->addedOn)); ?></td>
			</tr>
		</tbody>
	</table>
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

 