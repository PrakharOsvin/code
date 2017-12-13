<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading"> trips List</header>
      <div class="panel-body">    	
        <div class="adv-table table-responsive">

          <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">
            <thead>
              <tr>
                <th style="width:4%">Sr No</th>
                <th style="width:4%">Trip Id</th>
                <th style="width:6%">Client Id</th>
                <th>Client Name</th>
                <th style="width:6%">Driver Id</th>
                <th>Driver Name</th>
                <th>Vehicle Type</th>
                <th>Client Phone</th>
                <th>Profile Pic</th>                    
                <th>reason</th>
				<th style="width:18%">Action</th>
              </tr>
            </thead>
            <tbody>
              
             <?php  
             $sid = 1;            
             foreach ($cancelled_trips_detail as $trip) {
              
              ?>
              <tr>
                <td><?php echo $sid; ?></td>
                <td><?php echo $trip->id; ?></td>
                <td><?php echo $trip->user_id; ?></td>
                <td><?php echo $trip->name; ?></td>
                <td><?php echo $trip->driver_id; ?></td>
                <td><?php echo $trip->driver; ?></td>
                <td><?php echo $trip->vehicle; ?></td>
                <td><?php echo $trip->phone; ?></td>
             
              <td><?php if ($trip->profile_pic){?><img src="<?php echo $trip->profile_pic; ?>" width='100'/><?php } else{ ?><img src="<?php echo base_url('public/images/mythumb.gif'); ?>" /><?php } ?></td>

                <td><?php echo $trip->reason; ?></td>
               
				<td>
                  <a href="<?php echo base_url('Dashboard/trip_info').'/'.$trip->id; ?>" style="border-radius:4px !important;" name="view" class='btn btn-primary'>View</a>
                  <a href="<?php echo base_url('Dashboard/cancelled_trips') ?>" class="btn btn-success">Back to Cancelled Trips</a>
                </td>
              </tr>
              <?php  
              $sid++; }?>            
            </tbody>
          </table>

        <br>
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