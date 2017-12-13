<div id="responsecontainer">
<?php echo $map['js']; ?>

<section id="main-content">
    <section class="wrapper map-OUtER">

	 
        <div class="state-overview">
			
            <?php echo $map['html']; ?>

        </div> 
        <div class="test" >
          <h3 class="text-center">Stats</h3>
          <div class="leftsect">
            <ul class="list-unstyled">
              <li><i class="glyphicon glyphicon-map-marker"></i>Marker</li>
              <!-- <li><i class="glyphicon glyphicon-map-marker"></i> lorem</li>
              <li><i class="glyphicon glyphicon-map-marker"></i> lorem</li>
              <li><i class="glyphicon glyphicon-map-marker"></i> lorem</li> -->
            </ul>
          </div>
          <div class="rightsect text-right">
            <ul class="list-unstyled">
              <li>Online Driver</li>
              <!-- <li>Lorem ipsum dolor sit amet</li>
              <li>Lorem ipsum dolor sit amet</li>
              <li>Lorem ipsum dolor sit amet</li> -->
            </ul>
          </div>
        <div class="leftsect">
            <ul class="list-unstyled">
              <li><hr></li>
              <li><hr></li>
            </ul>
          </div>
          <div class="rightsect text-right">
            <ul class="list-unstyled">
              <li>Zone A-restricted area</li>
              <li>odd/even zone</li>
           </ul>
          </div>
        </div>  
    </section>
</section>
</div>
<!-- <script>
 $(document).ready(function() {
     $("#responsecontainer").load("Dashboard/googlemaps");
   var refreshId = setInterval(function() {
      $("#responsecontainer").load('Dashboard/googlemaps');
   }, 10);
   $.ajaxSetup({ cache: false });
}); -->
</script>
<script src="<?php echo base_url(); ?>/public/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?php echo base_url(); ?>/public/js/owl.carousel.js" ></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.customSelect.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/respond.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>

<!--common script for all pages-->
<script src="<?php echo base_url(); ?>/public/js/common-scripts.js"></script>

<!--script for this page-->
<script src="<?php echo base_url(); ?>/public/js/sparkline-chart.js"></script>
<script src="<?php echo base_url(); ?>/public/js/easy-pie-chart.js"></script>
<script src="<?php echo base_url(); ?>/public/js/count.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#hidden-table-info').DataTable();
    });
</script>