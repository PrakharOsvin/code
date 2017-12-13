<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">

<section id="main-content">
  <section class="wrapper site-min-height">

    <!-- page start-->
    <section class="panel">
      <header class="panel-heading"> trips List</header>
      <div class="panel-body">      
        <div class="adv-table table-responsive">
<?php
 // echo"<pre>";print_r($total);echo"</pre>";die; 
 ?>
          <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">
            <thead>
              <tr>
                <th style="width:4%">Sr No</th>
                
                <th style="width:6%">Driver Id</th>
                <th >Driver Name</th>

                <th>Driver email</th>

                <th style="width:12%">Driver Phone</th>
                <th>Profile Pic</th>                    
                <th style="width:10%">Cancelled Trips in row</th>
        <th style="width:18%">Action</th>
              </tr>
            </thead>
            <tbody>
              
             <?php  
             $sid = 1;            
             foreach ($total as $r) {
              
              ?>
              <tr>
                <td><?php echo $sid; ?></td>
                
                <td><?php echo $r['driver_id']; ?></td>
                <td><?php echo $r['name']; echo $r['first_name']; ?></td>

                <td><?php echo $r['email']; ?></td>

                <td><?php echo $r['phone']; ?></td>
             
              <td><?php if ($r['profile_pic']){?><img src="<?php echo $r['profile_pic']; ?>" width='100'/><?php } else{ ?><img src="<?php echo base_url('public/images/mythumb.gif'); ?>" /><?php } ?></td>
              <td><?php echo $r['count']; ?></td>
        <td>
                  <a href="<?php echo base_url('Dashboard/driver_refuses').'/'.$r['driver_id']; ?>" style="border-radius:4px !important;" name="Details" class='btn btn-primary'>Details</a>
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