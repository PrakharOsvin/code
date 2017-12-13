<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
<?php 
    // echo "<pre>"; print_r($department_list); echo "</pre>"; die; 
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
      <header class="panel-heading"> Department List</header>
      <div class="panel-body">    	
        <div class="adv-table table-responsive">

          <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered hidden-table-info">
            <thead>
              <tr>
                <th style="width:4%">Sr No</th>
                <th style="width:6%">Department Id</th>
                <th>Name</th>
                <th>Department description</th>   
                <th>Department Users</th>    
                <th style="width:14%">Action</th>
              </tr>
            </thead>
            <tbody>
              
             <?php  
             $sid = 1;            
             foreach ($department_list as $key) {
              
              ?>
              <tr>
                <td><?php echo $sid; ?></td>
                <td><?php echo $key->department_id; ?></td>
                <td><?php echo $key->department_name;?></td> 
                <td><?php echo $key->department_description;?></td> 
                <td>
                  <a href="<?php echo base_url('Dashboard/assign_department').'/'.$key->department_id; ?>" style="border-radius:4px !important;" name="Users" class='btn btn-danger'>Assign Users</a>
                </td>
                <td>
<!--                   <a href="<?php echo base_url('Dashboard/department_details').'/'.$key->id; ?>" style="border-radius:4px !important;" name="view" class='btn btn-primary'>View</a>
 -->                  <a href="<?php echo base_url('Dashboard/edit_department').'/'.$key->department_id; ?>" style="border-radius:4px !important;" name="edit" class='btn btn-primary'>Edit</a>                  
                  <a href="<?php echo base_url('Dashboard/department_permission').'/'.$key->department_id; ?>" style="border-radius:4px !important;" name="Permissions" class='btn btn-success'>Permissions</a>                                   
                  <!-- <a href="<?php echo base_url('Dashboard/deleteall').'/'.$key->id.'?action=users_list'; ?>" name="delete" onclick="return confirm('Are You Sure?')" class="btn btn-success">Delete</a>  -->
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