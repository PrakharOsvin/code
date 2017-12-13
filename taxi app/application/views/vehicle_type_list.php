<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
<?php 
    // echo "<pre>"; print_r($vehicle_type_list); echo "</pre>"; die; 
    ?>
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#All" aria-controls="All" role="tab" data-toggle="tab">All</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="All">
    <!-- page start-->
    
    <section class="panel">
      <header class="panel-heading">vehicle type List</header>
      <div class="panel-body">    	
        <div class="adv-table table-responsive">

          <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered hidden-table-info">
            <thead>
              <tr>
                <th style="width:4%">Sr No</th>
                <th style="width:6%">vehicle_type Id</th>
                <th>vehicle type</th>
                <th>Capacity</th>
                <th>Base rate</th>
                <th>per km</th>
                <th>per min</th>
                <th>waiting charges</th>
                <th>Traffic charges</th>
                <th>Restriction</th>
                <th>Status</th>    
                <th style="width:14%">Action</th>
              </tr>
            </thead>
            <tbody>
              
             <?php  
             $sid = 1;            
             foreach ($vehicle_type_list as $key) {
              
              ?>
              <tr>
                <td><?php echo $sid; ?></td>
                <td><?php echo $key->id; ?></td>
                <td><?php echo $key->vehicle_type;?></td>
                <td><?php echo $key->capacity;?></td>
                <td><?php echo $key->base_rate;?></td>
                <td><?php echo $key->per_km;?></td>
                <td><?php echo $key->per_min;?></td>
                <td><?php echo $key->waiting_charge;?></td>
                <td><?php echo $key->traffic_charges;?></td>
                <td><?php
                if ($key->restriction==1) {
                      echo "ON";
                    } else {
                      echo "OFF";
                    }
                 ?></td>
                <td>
                  <?php if($key->vehicle_status == '1'){ ?>
                    <button type="submit" value="<?php echo $user->id;?>" name="activate_user" class='btn btn-primary'>Activate</button>
                  <?php } elseif($key->vehicle_status == '0') { ?>
                    <button type="submit" value="<?php echo $user->id;?>" name="deactivate_user" class='btn btn-primary'>De-Activate</button>
                  <?php } ?>
                </td>
                <td>

                <a href="<?php echo base_url('Dashboard/edit_vehicle_type').'/'.$key->id; ?>" style="border-radius:4px !important;" name="edit" class='btn btn-success'>Edit</a>                                    
              <!--         -->                          
                
                </td>
              </tr>
              <?php  
              $sid++; }?>            
            </tbody>
          </table>
      </div>
    </div>
  </section>
  <!-- page end-->
  </div>

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
  $('.hidden-table-info').DataTable();
});
</script>