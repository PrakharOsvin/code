<?php
/*echo "<pre>";
// echo "string";
print_r($tehran);
die;*/
?>
<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
  <section id="main-content">
    <section class="wrapper site-min-height">
         <!-- page start-->
      <section class="panel">
        <header class="panel-heading">Location List</header>
          <div class="panel-body">      
            <div class="adv-table table-responsive">
                  <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered hidden-table-info">
                    <thead>
                      <tr>
                        <th>Sr No</th>
                        <th>City Id</th>
                        <th>Location Name</th>
                        <th>Farsi Name</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Traffic level</th>                      
                        <th>Action</th>
                      </tr>
                    </thead>
                  <tbody>
                
                   <?php  
                   $sid = 1;            
                   foreach ($tehran as $value) {
                    
                    ?>
                    <tr>
                      <td><?php echo $sid; ?></td>
                      <td><?php echo $value->id; ?></td>
                      <td><?php echo $value->location_name; ?></td>
                      <td><?php echo $value->farsi_name; ?></td>
                      <td><?php echo $value->latitude; ?></td>
                      <td><?php echo $value->longitude; ?></td>  
                      <td><?php 
                      	// echo $value->traffic_level; ?>
                      	<select class="selectpicker" id="traffic_level-<?php echo $value->id; ?>" onchange="getval(this);">
                      	  <option value="0" <?php if ($value->traffic_level==0) {
                      	  	echo "selected='selected'";
                      	  } else {
                      	  	echo "";
                      	  }
                      	   ?> >Select Level</option>
						  <option value="1" <?php if ($value->traffic_level==1) {
                      	  	echo "selected='selected'";
                      	  } else {
                      	  	echo "";
                      	  }
                      	   ?> >Very High</option>
						  <option value="2" <?php if ($value->traffic_level==2) {
                      	  	echo "selected='selected'";
                      	  } else {
                      	  	echo "";
                      	  }
                      	   ?> >High</option>
						  <option value="3"<?php if ($value->traffic_level==3) {
                      	  	echo "selected='selected'";
                      	  } else {
                      	  	echo "";
                      	  }
                      	   ?> >Medium</option>
						  <option value="4" <?php if ($value->traffic_level==4) {
                      	  	echo "selected";
                      	  } else {
                      	  	echo "";
                      	  }
                      	   ?> >Low</option>
						</select>

                      <?php ?></td> 
                     
                      <td>
                          <button type="button" id="update_traffic-<?php echo $value->id; ?>" value="<?php echo $value->id;?>" name="activate_user" onclick="update_traffic(this)"  class='btn btn-primary'>Update</button>
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
  $('.hidden-table-info').DataTable({
    stateSave: true,
  });
});

function update_traffic (elem) {
	// console.log(elem);
	var city_id = $(elem).val();
	// console.log(city_id);
	var e = document.getElementById("traffic_level-"+city_id);
	var traffic_level = e.options[e.selectedIndex].value;
	// console.log(traffic_level);
	$.ajax(
      {
     type: 'post',
     url: "<?php echo base_url() ?>Dashboard/update_traffic",
     data : {location_id : city_id, traffic_level : traffic_level},
     cache:false,
     success: function(data) 
     {
     	// console.log(data);
        $(elem).text("Updated");
     }
    }); // end ajax  
}

function getval(sel) {
	var city_id = $(sel).attr('id');
	var ar = city_id.split("-"); 
	// console.log(ar[1]);
	$("#update_traffic-"+ar[1]).text("Update");
}
</script>