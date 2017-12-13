<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
<style type="text/css">
tfoot {
    display: table-header-group;
}

tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>
    <section class="panel">
      <header class="panel-heading"> trips List</header>
      <div class="panel-body">    	
        <div class="adv-table table-responsive">
          <?php  echo form_open('trips/acttrip'); ?> 
          <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">
            <thead>
              <tr>
                <th>Sr No</th>
                <th>Trip Id</th>
                <th>Client Id</th>
                <th>Client Name</th>
                <th>Driver Id</th>
                <th>Driver Name</th>
                <th>Vehicle Number</th>
                <th>Vehicle Type</th>
                <!-- <th>Driver Phone</th>
                <th>Profile Pic</th>       -->              
                <th>Rating</th>
                <!-- <th>Feedback</th> -->
                <th>Price</th>
                <th>Payment Method</th>
				        <th>Action</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>Trip Id</th>
                <th>Client Id</th>
                <th>Client Name</th>
                <th>Driver Id</th>
                <th>Driver Name</th>
                <th>Vehicle Number</th>
                <th>Vehicle Type</th>
                <!-- <th>Driver Phone</th>
                <th>Profile Pic</th>  -->                   
                <th>Rating</th>
                <!-- <th>Feedback</th> -->
                <th>Price</th>
                <th>Payment Method</th>
                <th>Action</th>
              </tr>
            </tfoot>
            <tbody>
              
             <?php  
             $sid = 1;            
             foreach ($archive as $trip) {
              
              ?>
              <tr>
               
                <td><?php echo $sid; ?></td>
                <td><?php echo $trip->id; ?></td>
                <td><?php echo $trip->user_id; ?></td>
                <td><?php echo "<a href='profile/$trip->user_id'>".$trip->cleint."</a>"; ?></td>
                <td><?php echo $trip->driver_id; ?></td>
                <td><?php echo "<a href='profile/$trip->driver_id'>".$trip->name."</a>"; ?></td>
                <td><?php echo $trip->vehicle_number; ?></td>
                <td><?php echo $trip->vehicle; ?></td>
                <!-- <td><?php echo $trip->phone; ?></td>
             
              <td><?php if ($trip->profile_pic){?><img src="<?php echo $trip->profile_pic; ?>" width='100'/><?php } else{ ?><img src="<?php echo base_url('public/images/mythumb.gif'); ?>" /><?php } ?></td> -->

                <td><?php echo $trip->rating; ?></td>
                <!-- <td><?php if(strlen($trip->feedback)<50){
                  echo ucfirst($trip->feedback);            
                }
                else{
                    echo "<span id='more'>".ucfirst(substr($trip->feedback,0,50))."....<a href='' class='mymore' style='color:red;' data-attr='1'>More</a></span>";
                    echo "<span style='display:none;' id='less'>".ucfirst($trip->feedback)."....<a href='' class='mymore' style='color:red;' data-attr='0'>Less</a></span>";
                } ?></td> -->
                <td><?php echo $trip->price; ?></td>              
                 <td><?php if ($trip->payment_method==1) {
                                     echo "Zarinpal";
                                    } if ($trip->payment_method==2) {
                                     echo "Wallet";
                                    } 
                                    if ($trip->payment_method==3) {
                                     echo "Jiring";
                                    }
                                if ($trip->payment_method==0) {
                                     echo "Cash";
                                    } ?>
                               </td>


				<td>
                  <a href="<?php echo base_url('Dashboard/trip_info').'/'.$trip->id; ?>" style="border-radius:4px !important;" name="view" class='btn btn-primary'>View</a>
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
  
  // Setup - add a text input to each footer cell
    $('#hidden-table-info tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    var table = $('#hidden-table-info').DataTable({
    stateSave: true
  });
 
    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
});
</script>

