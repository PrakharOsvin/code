<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">User Details</header>
      <div class="panel-body">      
        <div class="adv-table table-responsive">

          <form method="post"id="register-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
            <tbody>
             <?php
             // echo "<pre>"; print_r($one_user_info); echo "</pre>"; die;
             foreach($department_details as $record){?>
              <tr>
                <td>Department Id:</td><td><?php echo $record->department_id; ?></td>
              </tr>
              <tr>
                <td>Name:</td> <td><input type = "text" name="department_name" value="<?php echo $record->department_name; ?>"></td>
             </tr>
              <td>Department Description:</td><td><input type = "text" name="department_description" value="<?php echo $record->department_description; ?>"></td>
              </tr>
              <?php } ?>
              </tbody>
              </table>
            
            <br>
            <input name="submit" type="submit" class="btn btn-primary" value="Submit">
            <a href="<?php echo base_url('Dashboard/department_list') ?>" class="btn btn-success">Back to Department List</a>
            </form>
        </div>
      </div>

    </section>
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
<script src="<?php echo base_url();?>/public/js/count.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('#hidden-table-info').DataTable();
});
</script>