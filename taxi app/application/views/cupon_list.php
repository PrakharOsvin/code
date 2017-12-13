<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
<?php 
    // echo "<pre>"; print_r($promo_list);   echo "</pre>"; die; 
    ?>
<div id="printableArea">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#All" aria-controls="All" role="tab" data-toggle="tab">All</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="All">
    <!-- page start-->
    
    <section class="panel">
      <header class="panel-heading">Coupon List</header>
      <div class="panel-body" >      
        <div class="adv-table table-responsive">

  <form method="post" id="register-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
          <input type="submit" name="submit" value="Export To CSV"> </input>
          <input type="button" onclick="printDiv('printableArea')" value="Print Data" />
          </form>
          <table cellpadding="0"  cellspacing="0" border="0" class="display table table-bordered hidden-table-info">
        
            <thead>
              <tr>
                <th style="width:4%">Sr No</th>
                <th style="width:6%">Coupon Id</th>
                <th>Promo Type</th>
                <th>Promo Code</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Discount </th>    
                <th>Single/Multi Use</th>            
                <th style="width:14%">Action</th>
              </tr>
            </thead>
            <tbody>
             <?php  
             $sid = 1;            
             foreach ($cupon_list as $key) {              
              ?>
              <tr>
                <td><?php echo $sid; ?></td>
                <td><?php echo $key->id; ?></td>
                <td><?php if ( $key->promo_type==1) {
                 echo "Simple Promo";
                } else {
                 echo "Unique Code Promo";
               }  ?></td>
                <td><?php echo $key->promo_code;?></td> 
                <td><?php echo $key->start_date;?></td>
                <td><?php if ( $key->end_date=='2098-10-10') {
                 echo 'Date Not Mentioned';
                } else {
                 echo $key->end_date;
               }  ?></td>
                <td><?php if ( $key->amt_type==1) {
                 echo $key->disc_amt;
                } else {
                 echo $key->disc_percent."%";
               }  ?></td>
               <td>
               <?php if ( $key->promo_use==1) {
                 echo "Multi Use";
                } else {
                 echo "Single Use";
               }  ?>
                 
               </td>
                <td>
                <a href="<?php echo base_url('Dashboard/edit_cupon').'/'.$key->id; ?>" style="border-radius:4px !important;" name="edit" class='btn btn-success'>Edit</a>                                    
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
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

<script type="text/javascript">
$(document).ready(function(){
  $('.hidden-table-info').DataTable();
});
</script>