<section id="main-content">
  <section class="wrapper">
<?php if($this->session->flashdata('msg')): ?>
  <div class="alert alert-success  alert-block fade in">
              <button data-dismiss="alert" class="close close-sm" type="button">
                <i class="fa fa-times"></i>
              </button>
              <h4>
                <i class="fa fa-ok-sign"></i>
                <?php echo $this->session->flashdata('msg'); ?>
              </h4>
        </div> 
<?php endif; ?>           
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading"> Update Password </header>            
          <div class="panel-body">
            <div class="form">
              <!-- <form class="cmxform form-horizontal tasi-form" id="AddJobType" name="AddJobType" method="post" action=""> -->
              <?php echo form_open('Updatepassword/Changepassword'); ?>
             <?php //echo validation_errors(); ?>
                <div class="form-group ">
                <div class="col-xs-12">
                <div class="row">
                  <label for="speciality" class="control-label col-lg-3 col-sm-3">Old Password</label>
                  <div class="col-lg-9 col-sm-9">
                    <input class="form-control"  id="id" name="id" value="<?php  echo $_SESSION['logged_in']['id']; ?>" type="hidden"/>
                    <input class="form-control width30per"  id="oldpassword" name="oldpassword" type="password"/>
                  <div class='error'><?php echo form_error('oldpassword'); ?></div> 
                 <div class='error'><?php echo form_error('check_database'); ?></div>
                    
                  </div>
                </div>   
                 </div>   
                   </div> 
                
                <div class="form-group ">
                 <div class="col-xs-12">
                <div class="row">
                  <label for="speciality" class="control-label col-lg-3 col-sm-3">New Password</label>
                  <div class="col-lg-9 col-sm-9">
                    <input class="form-control width30per" id="newpassword" name="newpassword" type="password"/>
                    <div class='error'><?php echo form_error('newpassword'); ?></div>
                  </div>
                </div>
                  </div>
                    </div>
               
                <div class="form-group ">
                  <div class="col-xs-12">
                <div class="row">
                  <label for="speciality" class="control-label col-lg-3 col-sm-3">Confirm New Password</label>
                  <div class="col-lg-9 col-sm-9">
                    <input class="form-control width30per" id="cnewpassword" name="cnewpassword" type="password"/>
                    <div class='error'><?php echo form_error('cnewpassword'); ?></div>
                  </div>
                </div>       
                   </div>
                </div>   
                                        
                <div class="form-group">
                  <div class="col-lg-offset-3 col-lg-1">
                    <button class="btn btn-danger" name="UpdatePassword" type="submit">Save</button>
                    <!--button class="btn btn-default" type="button">Cancel</button-->
                  </div>
                </div>
              </form>
            </div>
          </div>
        </section>
      </div>
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
<script src="<?php echo base_url();?>/public/js/count.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('#hidden-table-info').DataTable();
});
</script>