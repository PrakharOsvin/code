<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <?php
// echo "<pre>";
// print_r($manager_list);
    ?>
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">user List</header>
      <div class="panel-body">      
        <div class="adv-table table-responsive">
          
          <?php  echo form_open('Users/actuser/3'); ?> 
          <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">
            <thead>
              <tr>
                <th style="width:4%">Sr No</th>
                <th style="width:6%">user Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered From</th>
                <th>Department</th>                      
                <th style="width:14%">Action</th>
              </tr>
            </thead>
            <tbody>
              
             <?php  
             $sid = 1;            
             foreach ($manager_list as $user) {
              
              ?>
              <tr>
                <td><?php echo $sid; ?></td>
                <td><?php echo $user->id; ?></td>
                <td><?php echo $user->name; ?></td>
                <td><?php echo $user->email; ?></td>
                <td><?php echo date('d-M-Y g:i a', strtotime($user->date_created)); ?></td>  
                <td> 
                       <select class="selectpicker" id="department-<?php echo $user->id; ?>" onchange="getval(this);">
                        <option value='0' >Select department</option>
                     <?php
                     foreach ($department_list as $key => $value) { 
                          echo "<option value='$value->department_id'";
                          if ($user->department_id==$value->department_id) {
                            echo "selected='selected'>";
                          } else {
                            echo ">";
                          }
                          echo $value->department_name;
                          echo '</option>';
                      }
                     ?>  
               </td> 
               
                <td>
                  <button type="button" id="assign_department-<?php echo $user->id; ?>" value="<?php echo $user->id;?>" name="activate_user" onclick="assign_department(this)"  class='btn btn-primary'>Update</button>
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

$(document).ready(function(){
  $('#hidden-table-info').DataTable({
    stateSave: true
  });

});

function assign_department (elem) {
  // console.log(elem);
  var user_id = $(elem).val();
  console.log(user_id);
  var e = document.getElementById("department-"+user_id);
  var department_id = e.options[e.selectedIndex].value;
  console.log(department_id);
  $.ajax(
      {
     type: 'post',
     url: "<?php echo base_url() ?>Dashboard/update_department",
     data : {user_id : user_id, department_id : department_id},
     cache:false,
     success: function(data) 
     {
      // console.log(data);
        $(elem).text("Updated");
     }
    }); // end ajax  
}

function getval(sel) {
  var user_id = $(sel).attr('id');
  var ar = user_id.split("-"); 
  // console.log(ar[1]);
  $("#assign_department-"+ar[1]).text("Update");
}
</script>