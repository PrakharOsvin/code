<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/css/jquery.datetimepicker.css"/>
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">Set Commission</header>

      <div class="panel-body">      
        <!-- <div class="adv-table table-responsive" class="row">
        
          <form method="post" id="register-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
           <?php if($this->session->flashdata('msg')): ?>     
        <div class="alert alert-success alert-block fade in">
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
            <div class="col-md-6 col-xs-12 col-sm-6">
            <div class="bio-desk">
                <h4 class="terques">Default Driver Commission</h4>
                <table>
                  <tbody cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
                    <tr>
                      <td>
                        Commission : 
                      </td>
                      <td>
                        <input type = "text" name="commission" value="<?php echo $record->commission;?>">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        Commission From : 
                      </td>
                      <td>
                        <input type = "text" name="commission_from" class="datetimepicker" value="<?php echo $record->commission_from;?>">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        Commission To : 
                      </td>
                      <td>
                        <input type = "text" name="commission_to" class="datetimepicker" value="<?php echo $record->commission_to;?>">
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
          </div>
          </div>

          <br>
          <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-6">
              <input name="submit" type="submit" class="btn btn-primary" value="Submit">
            </div>
          </div>
        </form>
        </div> -->
        <hr>
        <div class="adv-table table-responsive" class="row">
        
          <form method="post" id="register-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
           <?php if($this->session->flashdata('msg2')): ?>     
        <div class="alert alert-success alert-block fade in">
                <button data-dismiss="alert" class="close close-sm" type="button">
                    <i class="fa fa-times"></i>
                </button>
                <h4>
                    <i class="fa fa-ok-sign"></i>
                  <?php echo $this->session->flashdata('msg2'); ?>
                </h4>                            
          </div>     
            <?php endif; ?>
            <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-6">
            <div class="bio-desk">
                <h4 class="terques">Default Driver Commission</h4>
                <table>
                  <tbody cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
                    <tr>
                      <td>
                        Default Commission : 
                      </td>
                      <td>
                        <input type = "text" name="commission" value="<?php echo $default->commission;?>">
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
          </div>
          </div>

          <br>
          <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-6">
              <input name="submitDefault" type="submit" class="btn btn-primary" value="Submit">
            </div>
          </div>
        </form>
        </div>
      </div>
    </section>
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
<script class="include" type="text/javascript" src="<?php echo base_url();?>/public/js/jquery.datetimepicker.full.js"></script>

<script src="<?php echo base_url();?>/public/js/sparkline-chart.js"></script>
<script src="<?php echo base_url();?>/public/js/easy-pie-chart.js"></script>
<script src="<?php echo base_url();?>/public/js/count.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script type="text/javascript">
  var d = new Date();
  $.datetimepicker.setLocale('en');
  $('.datetimepicker').datetimepicker({
  dayOfWeekStart : 1,
  lang:'en',
  startDate: d,
  format:'Y-m-d'
  });
  // $('.datetimepicker').datetimepicker({value:'2015/04/15 05:03',step:10});
</script>
