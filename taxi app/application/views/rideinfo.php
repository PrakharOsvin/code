      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
<?php
// echo "<pre>";
// print_r($user_info);
// echo "</pre>";
?>
              <!-- page start-->
              <div class="row">
                  <aside class="profile-nav col-lg-3">
                      <section class="panel">
                          <div class="user-heading round">
                              <a href="#">
                                  <img src="<?php echo $user_info[0]->profile_pic; ?>" alt="">
                              </a>
                              <h1><?php echo $user_info[0]->name; ?></h1>
                              <p><?php echo $user_info[0]->email; ?></p></p>
                          </div>

                          <ul class="nav nav-pills nav-stacked">
                               <div class="row">
                                  <div class="bio-row">
                                      <p><span>Name </span>: <b><?php echo $user_info[0]->name; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Email </span>: <b><?php echo $user_info[0]->email; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Address </span>: <b><?php echo $user_info[0]->address; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Home Address</span>: <b><?php echo $user_info[0]->home_address; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Work address </span>: <b><?php echo $user_info[0]->work_address; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Wallet balance </span>: <b><?php echo $user_info[0]->wallet_balance; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Mobile </span>: <b><?php echo $user_info[0]->phone; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Added By </span>: <b><?php echo $user_info[0]->added_by; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Added On </span>: <b><?php echo $user_info[0]->date_created; ?></b></p>
                                  </div>
                              </div>
                          </ul>

                      </section>
                  </aside>
                  <aside class="profile-info col-lg-9">
                      <section class="panel">
                          <div class="bio-graph-heading">
                              MASIR | Profile View | Information about user is displayed below:
                          </div>
                          <div class="panel-body bio-graph-info">
                              <h1>Profile Info</h1>
                              <div class="row">
                                  <div class="bio-row">
                                      <p><span>Name </span>: <b><?php echo $user_info[0]->name; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Email </span>: <b><?php echo $user_info[0]->email; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Address </span>: <b><?php echo $user_info[0]->address; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Home Address</span>: <b><?php echo $user_info[0]->home_address; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Work address </span>: <b><?php echo $user_info[0]->work_address; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Wallet balance </span>: <b><?php echo $user_info[0]->wallet_balance; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Mobile </span>: <b><?php echo $user_info[0]->phone; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Added By </span>: <b><?php echo $user_info[0]->added_by; ?></b></p>
                                  </div>
                                  <div class="bio-row">
                                      <p><span>Added On </span>: <b><?php echo $user_info[0]->date_created; ?></b></p>
                                  </div>
                              </div>
                          </div>
                      </section>
<?php
if ($user_info[0]->user_type==2) { ?>
                      <section>
                          <div class="row">
                              <div class="col-lg-6">
                                  <div class="panel">
                                      <div class="panel-body">
                                          <div class="bio-chart">
                                              <img src="<?php echo $user_info[0]->vehicle_image; ?>" alt="" width="100px" hight="100px">
                                          </div>
                                          <div class="bio-desk">
                                              <h4 class="red">Vehicle</h4>
                                              <p>Type : <b><?php echo $user_info[0]->vehicle_type; ?></b></p>
                                              <p>Model : <b><?php echo $user_info[0]->vehicle_model; ?></b></p>
                                              <p>Permit : <b><?php echo $user_info[0]->vehicle_permit_type; ?></b></p>
                                              <p>Exp. Date : <b><?php echo $user_info[0]->date_created; ?></b></p>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="panel">
                                      <div class="panel-body">
                                          <div class="bio-chart">
                                              <img src="<?php echo $user_info[0]->vehicle_insurance_image; ?>" alt="" width="100px" hight="100px">
                                          </div>
                                          <div class="bio-desk">
                                              <h4 class="terques">Vehicle Insurance</h4>
                                              <p>Number : <b><?php echo $user_info[0]->insurance_number; ?></b></p>
                                              <p>Exp. Date : <b><?php echo $user_info[0]->insurance_exp_date; ?></b></p>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="panel">
                                      <div class="panel-body">
                                          <div class="bio-chart">
                                              <img src="<?php echo $user_info[0]->vehicle_registration_image; ?>" alt="" width="100px" hight="100px">
                                          </div>
                                          <div class="bio-desk">
                                              <h4 class="green">Vehicle Registration</h4>
                                              <p>Number : <b><?php echo $user_info[0]->registration_number; ?></b></p>
                                              <p>Exp. Date : <b><?php echo $user_info[0]->registration_exp_date; ?></b></p>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="panel">
                                      <div class="panel-body">
                                          <div class="bio-chart">
                                              <img src="<?php echo $user_info[0]->driver_license_image; ?>" alt="" width="100px" hight="100px">
                                          </div>
                                          <div class="bio-desk">
                                              <h4 class="purple">Driver License</h4>
                                              <p>Number : <b><?php echo $user_info[0]->driver_license_number; ?></b></p>
                                              <p>Exp. Date : <b><?php echo $user_info[0]->driver_license_exp_date; ?></b></p>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="panel">
                                      <div class="panel-body">
                                          <div class="bio-chart">
                                              <img src="<?php echo $user_info[0]->document; ?>" alt="" width="100px" hight="100px">
                                          </div>
                                          <div class="bio-desk">
                                              <h4 class="green">Document</h4>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </section>
<?php } else {
  # code...
}
?>
                  </aside>
              </div>

              <!-- page end-->
          </section>
      </section>
      <!--main content end-->
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