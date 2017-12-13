<!DOCTYPE html>
<html lang="en">
<body class="login-body">
  <div class="loginbg">
    <div class="container">
      <!--<h1 class="text-center mT100 white">Welcome to LUX Admin Dashboard</h1>-->
      <div class="text-center logo2"><img src="<?php echo base_url(); ?>public/images/logo.png" width="200px"/>  </div>
      <div class="form-signin ">
        <?php echo form_open('Login/forgot_password'); ?> 

         <div class="mT30"></div>

          <h2 class="form-signin-heading "> Forgot Password</h2>         
          <div class="login-wrap">
            <div class="form-group ">
              <input type="text" class="form-control"  value="" id="email" name="email" placeholder="Email ID" autofocus>
			  <div class='error'><?php echo form_error('email'); ?></div>

            </div>

            <div class="alert alert-block  fade in" style="display:none"></div>
            <button class="btn btn-lg btn-login btn-block" id="btnlogin" type="submit">Submit</button>
            <a href="<?php echo base_url('Login'); ?>">Login Here.</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
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
<script type="text/javascript">
  countUp(<?php echo $CountUser; ?>);
  countUp2(<?php echo $CountRatings; ?>);
  countUp3(<?php echo $CountTips; ?>);
  
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('#hidden-table-info').DataTable();
});
</script>