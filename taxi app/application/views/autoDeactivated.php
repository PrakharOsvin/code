<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">Driver List</header>
      <div class="panel-body">    	
        <div class="adv-table table-responsive">
          
          <?php  echo form_open('Users/actuser/2'); ?> 
          <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">
            <thead>
              <tr>
                <th style="width:4%">Sr No</th>
                <th style="width:6%">driver Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Profile Pic</th>
                <th>Registered From</th>
                <th>Status</th>                      
                <th style="width:14%">Action</th>
              </tr>
            </thead>
            <tbody>
              
             <?php  
             $sid = 1;            
             foreach ($driver_list as $driver) {
              
              ?>
              <tr>
                <td><?php echo $sid; ?></td>
                <td><?php echo $driver->id; ?></td>
                <td><?php echo $driver->name; ?></td>
                <td><?php echo $driver->email; ?></td>
                <td><?php echo $driver->phone; ?></td>
             
              <td><?php if ($driver->profile_pic){?><img src="<?php echo $driver->profile_pic; ?>" width='100'/><?php } else{ ?><img src="<?php echo base_url('public/images/mythumb.gif'); ?>" /><?php } ?></td>

                
                <td><?php echo date('d-M-Y g:i a', strtotime($driver->date_created)); ?></td>  
                <td> 
                     <?php if($driver->status == '0'){ ?>
                    <button type="submit" value="<?php echo $driver->id;?>" name="activate_user"  class='btn btn-primary'>Activate</button>
                    <?php } elseif($driver->status == '1') { ?>
                      <button type="submit" value="<?php echo $driver->id;?>" name="deactivate_user"  class='btn btn-primary'>Suspend</button>
                    <?php } ?>  

               </td> 
               
                <td>
                  <a href="<?php echo base_url('Users/info').'/'.$driver->id; ?>" style="border-radius:4px !important;" name="view" class='btn btn-primary'>View</a>
                  <a href="<?php echo base_url('Users/edit').'/'.$driver->id; ?>" style="border-radius:4px !important;" name="edit" class='btn btn-success'>Edit</a>                                    
                  <!-- <a href="<?php echo base_url('drivers/deleteall').'/'.$driver->id.'?action=drivers_list'; ?>" name="delete" onclick="return confirm('Are You Sure?')" class="btn btn-success">Delete</a>  -->
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
/* Custom filtering function which will search data in column four between two values */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseInt( $('#min').val(), 10 );
        var max = parseInt( $('#max').val(), 10 );
        var age = parseFloat( data[3] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && age <= max ) ||
             ( min <= age   && isNaN( max ) ) ||
             ( min <= age   && age <= max ) )
        {
            return true;
        }
        return false;
    }
);

$(document).ready(function(){
  $('#hidden-table-info').DataTable({
    stateSave: true
  });

});
</script>