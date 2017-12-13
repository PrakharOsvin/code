<?php 
// print_r($one_user_info);die; ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/css/jquery.datetimepicker.css"/>
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">User Details</header>

      <div class="panel-body">      
        <div class="adv-table table-responsive" class="row">
        
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
            <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
              <tbody>
                 <?php
                 // echo "<pre>";
                 // print_r($one_user_info);
                 // echo "</pre>";
                 // die;
                  foreach($one_user_info as $record){
                   // $dataS = explode(" ",$record->name);
                   // print_r($dataS['0']);
                    ?>
                   <tr>
                       <td>User Id:</td><td><?php echo $record->id; ?></td>
                  </tr>
                  <tr>
                     <td>First Name:</td> 
                     <td><input type = "text" name="first_name" value="<?php echo $record->first_name; ?>"></td>
                 </tr>
                 <tr>
                     <td>Last Name:</td> 
                     <td><input type = "text" name="last_name" value="<?php echo $record->last_name; ?>"></td>
                 </tr>
                  <tr>
                    <td>Phone:</td>
                    <td><input type = "text" name="phone" value="<?php echo $record->phone; ?>"></td> 
                  </tr>
                  <tr>
                    <td>Address:</td>
                    <td><input type = "text" name="address" value="<?php echo $record->address; ?>"></td> 
                  </tr>
                  <?php 
                    if($this->session->userdata['logged_in']['user_type']==1){?>
                  <tr>
                    <td>Password:</td>
                    <td><input type = "password" name="password" ></td> 
                  </tr>
                  <?php  }  ?>
                
                  <tr>
                    <td>Profile Pic:</td>
                    <td><img src="<?php echo $record->profile_pic; ?>" width='200'/></td>
                  </tr>
                  <tr>
                    <td>Change Profile pic:</td>
                    <td><input type="file" name="profile_pic"></td>
                  </tr>
                  <tr>
                       <td>Wallet Balance:</td><td><?php echo $record->wallet_balance; ?></td>
                  </tr>
                  <tr>
                    <td>Email:</td>
                    <td><?php echo $record->email; ?></td>
                  </tr>

                  <tr>
                    <td>Registered From:</td>
                    <td><?php echo date('d-M-Y H:i:s', strtotime($record->date_created)); ?></td>
                  </tr>                  
               
               <?php } ?>
              </tbody>
          </table>
          </div>
          </div>

          <br>
          <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-6">
              <input name="submit" type="submit" class="btn btn-primary" value="Submit">
            </div>
            <div class="col-md-6 col-xs-12 col-sm-6">
              <a href="<?php echo base_url('Users/manager_list') ?>" class="btn btn-success">Back to manager List</a>
              <a href="<?php echo base_url('Dashboard/mprofile').'/'.$one_user_info[0]->id; ?>" name="view" class='btn btn-success'>View profile</a>
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
