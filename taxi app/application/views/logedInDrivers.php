<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">Logged In Driver List</header>
      <div class="panel-body">      
        <div class="adv-table table-responsive">
          
          <?php echo form_open('Dashboard/logedInDrivers'); ?> 
          <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">
            <thead>
              <tr>
                <th style="width:4%">Sr No</th>
                <th style="width:6%">driver Id</th>
                <th>Name</th>
                <th>Permit</th>
                <th>Email</th>
                <th>Phone</th>
                <th>On Ride</th>
                <th>Availability</th>
                <th>Logged In From</th>                    
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              
             <?php  
             $sid = 1;            
             foreach ($logedInDrivers as $driver) {
              
              ?>
              <tr>
                <td><?php echo $sid; ?></td>
                <td><?php echo $driver->user_id; ?></td>
                <td><?php echo $driver->first_name." ".$driver->last_name; ?></td>
                <td><?php echo $driver->vehicle_permit_type; ?></td>
                <td><?php echo $driver->email; ?></td>
                <td><?php echo $driver->phone; ?></td>
                <td><?php if($driver->on_ride == 1){
                    echo "No";

                  } else{
                    echo "Yes";
                    }
                    ?>
                </td>
                <td><?php if($driver->availability == 0){
                echo "Offline";

                } else{
                  echo "Online";
                  }
                  ?>
                </td>
                <td>
                  <?php
                   echo date('d-M-Y g:i a', strtotime($driver->date_created));
                   echo "<br>";
                   $date1 = date("Y-m-d H:i:s");
                   $date2 = $driver->date_created;
                   $datetime1 = new DateTime($date1);
                   $datetime2 = new DateTime($date2);
                   $dteDiff = $datetime1->diff($datetime2);
                   print $dteDiff->format("%H:%I:%S"); 
                  ?>
                </td>  
                
               
                <td>
                  <input type="hidden" name="user_id" value="<?php echo $driver->user_id; ?>"/>
                 <?php if($driver->on_ride ==1){ ?>
                      <button type="submit" value="<?php echo $driver->id;?>" name="logout"  class='btn btn-danger'>Log Out</button>
                      <?php } ?>
                    <a href="<?php echo base_url('Dashboard/profile').'/'.$driver->user_id; ?>"  name="view" class='btn btn-success'>View</a>
                    <a href="<?php echo base_url('Dashboard/trackUser').'/'.$driver->user_id; ?>"  name="track" class='btn btn-warning'>Track</a>
                </td>
              </tr>
              <?php  
              $sid++; }?>            
            </tbody>
          </table>
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
  $('#hidden-table-info').DataTable({
    stateSave: true
  });

});
</script>