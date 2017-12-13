<div id="responsecontainer">
<?php echo $map['js']; ?>

<section id="main-content">
    <section class="wrapper">
	 <?php  echo form_open('Dashboard/googlemaps'); ?> 
		 <input type="radio" name="sort" value="0"> All
         <input type="radio" name="sort" value="1"> Free
		 <input type="radio" name="sort" value="2"> On trip
		 <input type="submit" name="sortsub" value="Sort">
        <div class='error'><?php echo form_error('sort'); ?></div>
		<input type="text" name="message" placeholder="message">
		<input type="submit" name="pushsub" value="Send"><br><br>
        <div class='error'><?php echo form_error('pushsub'); ?></div>
	 </form>
	 
        <div class="state-overview">
			
            <?php echo $map['html']; ?>

        </div> 
        <div class="test" style="height:200px;width:200px; bacground:red;"></div>         
    </section>
</section>
</div>
</body>
</html>
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