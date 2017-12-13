<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
  <section id="main-content">
    <section class="wrapper site-min-height">
         <!-- page start-->
      <section class="panel">
        <header class="panel-heading">Departments</header>
          <div class="panel-body">      
            <div class="adv-table table-responsive">
                  <?php  echo form_open('Users/actuser/3'); ?> 
                  <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered hidden-table-info">
                    <thead>
                      <tr>
                        <th>Sr No</th>
                        <th>Department Id</th>
                        <th>Department Name</th>
                        <th>Department Description</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Sr No</th>
                        <th>Department Id</th>
                        <th>Department Name</th>
                        <th>Department Description</th>
                      </tr>
                    </tfoot>
                  <tbody>
                
                   <?php  
                   $sid = 1;            
                   foreach ($department as $value) {
                    
                    ?>
                    <tr>
                      <td><?php echo $sid; ?></td>
                      <td><?php echo $value->department_id; ?></td>
                      <td><?php echo $value->department_name; ?></td>
                      <td><?php echo $value->department_description; ?></td>
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
      <!-- page start-->
      <section class="panel">
        <header class="panel-heading">Permissions</header>
          <div class="panel-body">      
            <div class="adv-table table-responsive">
                  <?php  echo form_open('Users/actuser/3'); ?> 
                  <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered hidden-table-info">
                    <thead>
                      <tr>
                        <th>Sr No</th>
                        <th>Permission Id</th>
                        <th>Permission Name</th>
                        <th>Permission Description</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Sr No</th>
                        <th>Permission Id</th>
                        <th>Permission Name</th>
                        <th>Permission Description</th>
                      </tr>
                    </tfoot>
                  <tbody>
                
                   <?php  
                   $sid = 1;            
                   foreach ($permission as $value) {
                    
                    ?>
                    <tr>
                      <td><?php echo $sid; ?></td>
                      <td><?php echo $value->permission_id; ?></td>
                      <td><?php echo $value->permission_name; ?></td>
                      <td><?php echo $value->permission_description; ?></td>
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
      <!-- page start-->
      <section class="panel">
        <header class="panel-heading">Department Permission</header>
          <div class="panel-body">      
            <div class="adv-table table-responsive">
                  <?php  echo form_open('Users/actuser/3'); ?> 
                  <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered hidden-table-info">
                    <thead>
                      <tr>
                        <th>Sr No</th>
                        <th>Department Id</th>
                        <th>Permission Id</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Sr No</th>
                        <th>Department Id</th>
                        <th>Permission Id</th>
                      </tr>
                    </tfoot>
                  <tbody>
                
                   <?php  
                   $sid = 1;            
                   foreach ($department_permission as $value) {
                    
                    ?>
                    <tr>
                      <td><?php echo $sid; ?></td>
                      <td><?php echo $value->department_id; ?></td>
                      <td><?php echo $value->permission_id; ?></td>
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
      <!-- page start-->
      <section class="panel">
        <header class="panel-heading">User departments</header>
          <div class="panel-body">      
            <div class="adv-table table-responsive">
                  <?php  echo form_open('Users/actuser/3'); ?> 
                  <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered hidden-table-info">
                    <thead>
                      <tr>
                        <th>Sr No</th>
                        <th>User Id</th>
                        <th>department_id</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Sr No</th>
                        <th>User Id</th>
                        <th>department ID</th>
                      </tr>
                    </tfoot>
                  <tbody>
                
                   <?php  
                   $sid = 1;            
                   foreach ($users_department as $value) {
                    
                    ?>
                    <tr>
                      <td><?php echo $sid; ?></td>
                      <td><?php echo $value->user_id; ?></td>
                      <td><?php echo $value->department_id; ?></td>
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
  $('table.display').DataTable({
    stateSave: true,
    "scrollY":        "400px",
    "scrollCollapse": true,
    "paging":         false
  });
});
</script>