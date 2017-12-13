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
                <th>Client Name</th>
                <th>Lost place</th>
                 <th>Estimated Price</th>     
                  <th>Reason</th>   
                 <th>Date And Time</th>      
                 
                <th>Action</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                 <th>Sr No</th>                             
                <th>Client Name</th>
                <th>Lost place</th>
                <th>Estimated Price</th>  
                <th>Reason</th>  
                <th>Date And Time</th>   
                <th>Action</th>
              </tr>
            </tfoot>
            <tbody>
              
             <?php  
             $sid = 1;            
             foreach ($unbook as $unbooked) {
              
              ?>
              <tr>
                <td><?php echo $sid; ?></td>                
                <td><?php echo "<a href='profile/$unbooked->user_id'>";echo $unbooked->first_name; ?></a></td>
                <td><?php echo $unbooked->end_address; ?></td> 
                
                <?php
                if($unbooked->reason== '0'){ ?> 
                 <td><?php echo $unbooked->estimated_price; ?></td><td>
                 <?php 
                  echo "Fare Check";
                  echo '</td>';
                  // print_r($userInfo['0']->reason);die;
                } else{
                    echo '<td>';
                    $ext=explode(',',$unbooked->estimate);
                    print_r($ext[2]); 
                    ?>
                    </td>
                    <td>
                    <?php 
                    echo  "No Driver Found";
                    echo '</td>';   
                } ?> 
                <td><?php echo $unbooked->addedOn; ?></td>             
				<td>
                  <a href="<?php echo base_url('Dashboard/detailUnbookData').'/'.$unbooked->id; ?>" style="border-radius:4px !important;" name="view" class='btn btn-primary'>View</a>
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
    // "sAjaxSource": "sources/arrays.txt",
    "bDeferRender": true,
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