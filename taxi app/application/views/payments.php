<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">

<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- pdate start-->
    <?php 
// echo"<pre>";print_r($payments);echo"</pre>";die;
    ?>
    <section class="panel">
      <header class="panel-heading"> All Transactions</header>
      <div class="panel-body">  
      
        <div class="adv-table table-responsive">
          <!-- <table border="0" cellspacing="5" cellpadding="5">
        <tbody><tr>
            <td>From date:</td>
            <td><input type="text" class="form-control datepicker" id="min" name="min"></td>
        </tr>
        <tr>
            <td>To date:</td>
            <td><input type="text" class="form-control datepicker" id="max" name="max"></td>
        </tr>
    </tbody></table> -->
          <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">
            <thead>
              <tr>
                <th>Sr No.</th>
                <th>Payment Id</th>
                <th>Job Id</th>    
                <th>User Id</th>                          
                <th>Amount</th>
                <th>Payment Type</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Payment RefID</th>
                <th>Date Created</th>
                <th style="display:none;">date</th>
              </tr>
            </thead>

            <tfoot>
              <tr>
                <th>Sr No.</th>
                <th>Payment Id</th>
                <th>Job Id</th>    
                <th>User Id</th>                          
                <th>Amount</th>
                <th>Payment Type</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Payment RefID</th>
                <th>Date Created</th>
                <th style="display:none;">date</th>
              </tr>
            </tfoot>
            <tbody>

              <?php      
              $count = 1;        
              foreach ($payments as $p) {
               
                ?>
                <tr>           
                  <td><?php echo $count; ?></td> 
                  <td><?php echo $p->id; ?></td>
                  <td><?php echo $p->job_id; ?></td>
                  <td><?php echo $p->user_id; ?></td>
                  <td><?php echo $p->amount; ?></td>
                  <td><?php if ($p->payment_type == 1) {
                    echo "Debited";
                  }elseif ($p->payment_type == 2) {
                    echo "Credited";
                  }else{
                    echo "Promo";
                  }?></td>  
                  <td><?php if ($p->payment_method == 1) {
                    echo "Added";
                  }elseif ($p->payment_method == 2) {
                    echo "Wallet";
                  }elseif ($p->payment_method==3) {
                    echo "Welcome Promo";
                  }elseif ($p->payment_method==4) {
                    echo "Referral";
                  } else{
                    echo "Cash";
                  }?></td>
                  <td><?php switch ($p->payment_status) {
                    case '333':
                      echo "Cancelled By User";
                      break;
                    case '101':
                      echo "Amount Doesn't match";
                      break;
                    case '100':
                      echo "Successful";
                      break;
                    
                    default:
                      echo "$p->payment_status";
                      break;
                  } ?></td>
                   
                  <td><?php echo $p->payment_RefID; ?></td>           
                  <td><?php echo date('d-M-Y g:i a', strtotime( $p->date_created)); ?></td>
                  <td style="display:none;"><?php echo date('d-m-Y', strtotime( $p->date_created)); ?></td>
      </tr>
      <?php
      $count++; 
      } 
      ?>            
    </tbody>
  </table>
</form>
</div>
</div>
</section>
<!-- pdate end-->
</section>
</section>
<script src="<?php echo base_url();?>/public/js/jquery.js"></script>
<script src="<?php echo base_url();?>/public/js/jquery-1.8.3.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
$(function() {
  $( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
});
</script>
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

<!--common script for all pdates-->
<script src="<?php echo base_url();?>/public/js/common-scripts.js"></script>

<!--script for this pdate-->
<script src="<?php echo base_url();?>/public/js/sparkline-chart.js"></script>
<script src="<?php echo base_url();?>/public/js/easy-pie-chart.js"></script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">

/* Custom filtering function which will search data in column four between two values */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min =  $('#min').val();
        var max = $('#max').val();
        var date = data[3]; // use data for the date column
 // console.log(min);
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && date <= max ) ||
             ( min <= date   && isNaN( max ) ) ||
             ( min <= date   && date <= max ) )
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

  $('#min, #max').keyup( function() {
      table.draw();
  });

});
</script>