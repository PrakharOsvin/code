
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap-datepicker.min.css" />
    
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
                   $dataS = explode(" ",$record->name);
                   // print_r($dataS['0']);
                    ?>
                   <tr>
                       <td dir="auto">User Id:</td><td dir="auto"><?php echo $record->id; ?></td>
                  </tr>
                  <tr>
                     <td dir="auto">First Name:</td> 
                     <td dir="rtl"><input type = "text" name="first_name" value="<?php echo $dataS['0']; ?>"></td>
                 </tr>
                 <tr>
                     <td dir="auto">Last Name:</td> 
                     <td dir="rtl"><input type = "text" name="last_name" value="<?php echo $dataS['1']; ?>"></td>
                 </tr>
                  <tr>
                    <td dir="auto">Phone:</td>
                    <td dir="rtl"><input id="phone" type = "text" name="phone" value="<?php echo $record->phone; ?>" maxlenght="11"></td> 
                  </tr>
                  <tr>
                    <td dir="auto">Address:</td>
                    <td dir="rtl"><input type = "text" name="address" value="<?php echo $record->address; ?>"></td> 
                  </tr>
                  <?php 
                    if($this->session->userdata['logged_in']['user_type']==1){?>
                  <tr>
                    <td dir="auto">Password:</td>
                    <td dir="rtl"><input type = "password" name="password" ></td> 
                  </tr>
                  <?php  }  ?>
                
                  <tr>
                    <td dir="auto">Profile Pic:</td>
                    <td dir="rtl"><img src="<?php echo $record->profile_pic; ?>" width='200'/></td>
                  </tr>
                  <tr>
                    <td dir="auto">Change Profile pic:</td>
                    <td dir="rtl"><input type="file" name="profile_pic"></td>
                  </tr>
                  <tr>
                       <td dir="auto">Wallet Balance:</td><td dir="auto"><?php echo $record->wallet_balance; ?></td>
                  </tr>
                  <tr>
                    <td dir="auto">Email:</td>
                    <td dir="auto"><?php echo $record->email; ?></td>
                  </tr>

                  <tr>
                    <td dir="auto">Registered From:</td>
                    <td dir="auto"><?php echo date('d-M-Y H:i:s', strtotime($record->date_created)); ?></td>
                  </tr>
               
               <?php } ?>
              </tbody>
          </table>
          <?php
                if ($record->user_type==2) { ?>
            <div class="bio-desk">
                <h4 class="terques">Contract</h4>
                <table>
                  <tbody cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
                    <tr>
                      <td dir="auto">
                        Exp. date : 
                      </td>
                      <td dir="rtl">
                        <input type = "text" name="contract_exp_date" class="datetimepicker" value="<?php echo $record->contract_exp_date;?>">
                        <input type = "hidden" name="contract_exp_date" class="datetimepicker1" value="<?php echo $record->contract_exp_date;?>">
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="bio-desk">
                <h4 class="terques">Permit A Validity</h4>
                <table>
                  <tbody cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
                    <tr>
                      <td dir="auto">
                        From : 
                      </td>
                      <td dir="rtl">
                        <input type = "text" name="from" class="datetimepicker" value="<?php echo $record->from;?>">
                        <input type = "hidden" name="from" class="datetimepicker1" value="<?php echo $record->from;?>">
                      </td>
                    </tr>
                    <tr>
                      <td dir="auto">
                        To : 
                      </td>
                      <td dir="rtl">
                        <input type = "text" name="to" class="datetimepicker" value="<?php echo $record->to;?>">
                        <input type = "hidden" name="to" class="datetimepicker1" value="<?php echo $record->to;?>">
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
            <div class="bio-desk">
                <h4 class="terques">Driver Commission</h4>
                <table>
                  <tbody cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
                    <?php
// echo "<pre>";
// print_r($commission[0]);die;
                    ?>
                    <tr>
                      <td dir="auto">
                        Commission : 
                      </td>
                      <td dir="auto">
                        <select id="commission" name="commission"  class="form-control">
                          <option value="0">select</option>
                            <?php foreach ($commissionLevels as $cl) { ?>
                                <option <?php if($commission[0]->commission==$cl->id) echo "selected"; ?> value="<?php echo $cl->id; ?>"><?php echo $cl->commissionLevel." -> ".$cl->commissionPrcnt."%"; ?></option>
                            <?php } ?>  
                        </select> 
                      </td>
                    </tr>
                    <tr>
                      <td dir="auto">
                        Commission From : 
                      </td>
                      <td dir="rtl">
                        <input type = "text" id="commission_from" name="commission_from" class="datetimepicker" value="<?php echo $commission[0]->commission_from;?>">
                        <input type = "hidden" id="commission_from" name="commission_from" class="datetimepicker1" value="<?php echo $commission[0]->commission_from;?>">
                      </td>
                    </tr>
                    <tr>
                      <td dir="auto">
                        Commission To : 
                      </td>
                      <td dir="rtl">
                        <input id="commission_to" type = "text" name="commission_to" class="datetimepicker" value="<?php echo $commission[0]->commission_to;?>">
                        <input id="commission_to" type = "hidden" name="commission_to" class="datetimepicker1" value="<?php echo $commission[0]->commission_to;?>">
                      </td>
                    </tr>
                    <tr>
                      <td dir="auto">
                        Forever : 
                      </td>
                      <td dir="auto">
                        <input type = "hidden" name="forever" value="0">
                        <input id="forever" type = "checkbox" name="forever" value="<?php echo $commission[0]->forever; ?>" <?php echo ($commission[0]->forever==1) ? "checked" : "" ; ?> >
                      </td>
                    </tr>
                    <tr>
                      <td dir="auto">
                        Default Commission : 
                      </td>
                      <td dir="auto">
                        <select id="dflt_commission" name="dflt_commission"  class="form-control">
                            <?php foreach ($commissionLevels as $cl) { ?>
                                <option <?php if($commission[1]->commission==$cl->id) echo "selected"; ?> value="<?php echo $cl->id; ?>"><?php echo $cl->commissionLevel." -> ".$cl->commissionPrcnt."%"; ?></option>
                            <?php } ?>  
                        </select> 
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
           <?php } ?>
          </div>
        <div class="col-md-6 col-xs-12 col-sm-6">
        <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1" style="float: right";>
              <tbody>
                <?php
                if ($record->user_type==2) {
                  foreach($one_user_info as $record){
                    // print_r($one_user_info);die;?>

                  <tr>
                    <td dir="auto">Vehicle Pic:</td>
                    <td dir="rtl"><img src="<?php echo $record->vehicle_image; ?>" width='200'/></td>
                  </tr>
                  <tr>
                    <td dir="auto">Change Vehicle pic:</td>
                    <td dir="rtl"><input type="file" name="vehicle_image"></td>
                  </tr>
                  <tr>
                    <td dir="auto">Vehicle Insurance Pic:</td>
                    <td dir="rtl"><img src="<?php echo $record->vehicle_insurance_image; ?>" width='200'/></td>
                  </tr>
                  <tr>
                    <td dir="auto">Change Vehicle Insurance pic:</td>
                    <td dir="rtl"><input type="file" name="vehicle_insurance_image"></td>
                  </tr>
                  <tr>
                    <td dir="auto">Insurance Number:</td>
                    <td dir="rtl"><input type = "text" name="insurance_number" value="<?php echo $record->insurance_number;?>"></td>
                  </tr>
                  <tr>
                    <td dir="auto">Insurance Exp. Date:</td>
                    <td dir="rtl">
                      <input type = "text" name="insurance_exp_date" class="datetimepicker" value="<?php echo $record->insurance_exp_date;?>">
                      <input type = "hidden" name="insurance_exp_date" class="datetimepicker1" value="<?php echo $record->insurance_exp_date;?>">
                    </td> 
                  </tr>
                  <tr>
                    <td dir="auto">Vehicle Registration Pic:</td>
                    <td dir="rtl"><img src="<?php echo $record->vehicle_registration_image; ?>" width='200'/></td>
                  </tr>
                  <tr>
                    <td dir="auto">Change Vehicle Registration pic:</td>
                    <td dir="rtl"><input type="file" name="vehicle_registration_image"></td>
                  </tr>
                  <tr>
                    <td dir="auto">Registration Number:</td>
                    <td dir="rtl"><input type = "text" name="registration_number" value="<?php echo $record->registration_number;?>"></td>
                  </tr>
                  <tr>
                    <td dir="auto">State Identifier:</td>
                    <td dir="rtl"><input type = "text" name="state_identifier" value="<?php echo $record->state_identifier;?>"></td>
                  </tr>
                  <tr>
                    <td dir="auto">Registration Exp. Date:</td>
                    <td dir="rtl">
                      <input type = "text" name="registration_exp_date" class="datetimepicker" value="<?php echo $record->registration_exp_date;?>">
                      <input type = "hidden" name="registration_exp_date" class="datetimepicker1" value="<?php echo $record->registration_exp_date;?>">
                    </td> 
                  </tr>
                  <tr>
                    <td dir="auto">Driver License Pic:</td>
                    <td dir="rtl"><img src="<?php echo $record->driver_license_image; ?>" width='200'/></td>
                  </tr>
                  <tr>
                    <td dir="auto">Change Driver License pic:</td>
                    <td dir="rtl"><input type="file" name="driver_license_image"></td>
                  </tr>
                  <tr>
                    <td dir="auto">License Number:</td>
                    <td dir="rtl"><input type = "text" name="driver_license_number" value="<?php echo $record->driver_license_number;?>"></td>
                  </tr>
                  <tr>
                    <td dir="auto">License Exp. Date:</td>
                    <td dir="rtl">
                      <input type = "text" name="driver_license_exp_date" class="datetimepicker" value="<?php echo $record->driver_license_exp_date;?>">
                      <input type = "hidden" name="driver_license_exp_date" class="datetimepicker1" value="<?php echo $record->driver_license_exp_date;?>">
                    </td> 
                  </tr>
                  <tr>
                    <td dir="auto">Document Pic:</td>
                    <td dir="rtl"><img src="<?php echo $record->document; ?>" width='200'/></td>
                  </tr>
                  <tr>
                    <td dir="auto">Change Document pic:</td>
                    <td dir="rtl"><input type="file" name="document"></td>
                  </tr>
                  <tr>
                     <td dir="auto">Cell Provider:</td> 
                     <td dir="auto">
                     <select name="cell_provider"  class="form-control">
                     <option <?php if($this->input->post('cell_provider')=="Ritetell") echo "selected"; ?> value="Ritetell">Ritetell</option>
                     <option <?php if($this->input->post('cell_provider')=="Irancell") echo "selected"; ?> value="Irancell">Irancell</option>
                     <option <?php if($this->input->post('cell_provider')=="Hamrah Aaval") echo "selected"; ?> value="Hamrah Aaval">Hamrah Aaval</option>
                    </select> 
                     </td>
                 </tr>
                  <tr>
                    <td dir="auto">Vehicle Type:</td>
                    <td dir="auto">
                      <select name="vehicle_type"  class="form-control">

                       <?php foreach($vehicle as $v){?>
                        <option <?php if($this->input->post('vehicle_type')==$v->id) echo "selected"; ?> value="<?php echo $v->id;?>"><?php echo $v->vehicle_type;?>
                        </option>
                       <?php }?>  
                      </select> 
                    </td> 
                  </tr>
                  <tr>
                    <td dir="auto">Vehicle Model:</td>
                    <td dir="auto">
                      <select name="vehicle_model"  class="form-control">
                          <?php foreach ($model_list as $model) { ?>
                              <option <?php if($this->input->post('vehicle_model')==$model->model_name) echo "selected"; ?> value="<?php echo $model->model_name; ?>"><?php echo $model->model_name; ?></option>
                          <?php } ?>  
                      </select> 
                    </td> 
                  </tr>
                  <tr>
                    <td dir="auto">Vehicle Permit Type:</td>
                    <td dir="rtl"><input type = "text" name="vehicle_permit_type" value="<?php echo $record->vehicle_permit_type;?>"></td> 
                  </tr>
               <?php 
                }
               } ?>
              </tbody>
          </table>
          </div>
          </div>

          <br>
          <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-6">
              <input name="submit" type="submit" onclick="return submitFrm(this)" class="btn btn-primary" value="Submit">
            </div>
            <div class="col-md-6 col-xs-12 col-sm-6">
              <a href="<?php echo base_url('Users/users_list') ?>" class="btn btn-success">Back to user List</a>
              <a href="<?php echo base_url('Dashboard/profile').'/'.$one_user_info[0]->id; ?>" name="view" class='btn btn-success'>View profile</a>
            </div>
          </div>
        </form>
        </div>
      </div>
    </section>
  </section>
</section>
<script src="<?php echo base_url(); ?>/public/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?php echo base_url(); ?>/public/js/owl.carousel.js" ></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.customSelect.min.js" ></script>
<script src="<?php echo base_url(); ?>/public/js/respond.min.js" ></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>

<!--common script for all pages-->
<script src="<?php echo base_url(); ?>/public/js/common-scripts.js"></script>

<!--script for this page-->
<script src="<?php echo base_url(); ?>/public/js/sparkline-chart.js"></script>
<script src="<?php echo base_url(); ?>/public/js/easy-pie-chart.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>


<script src="<?php echo base_url();?>public/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>public/js/bootstrap-datepicker.fa.min.js"></script>

<script src="<?php echo base_url();?>/public/js/convertCal.js"></script>
<script type="text/javascript">
// request permission on page load
    document.addEventListener('DOMContentLoaded', function () {
      var dt = $('.datetimepicker');
      // console.log(dt.length);
      // console.log(dt);
      for (var i = 0; i < dt.length; i++) {
        var gg = $('.datetimepicker')[i].value;
        console.log(gg);
        if (gg=='0000-00-00'||gg=="") {
          console.log(gg);
        } else{
          var result = gg.split('-');
          console.log(gg);
          var j = toJalaali(parseInt(result[0]),parseInt(result[1]),parseInt(result[2]));
             // console.log(p);
          // var parray = p.split(',');
          // console.log(j);
          // return false;
          var nd = j.jy+"-"+j.jm+"-"+j.jd;
          // console.log(nd);
          // return false;

          $('.datetimepicker')[i].value = nd;
        };          
        // console.log($('.datetimepicker')[i].value);
      }
    });

  $('#forever').change(function() {
      if($(this).is(":checked")) {
        // alert("chk");
        $(this).val('1');
      }else{
        // alert("uchk");
        console.log($('#commission_to').val());
        var cto = $('#commission_to').val();
        if (cto=="0000-00-00") {
          alert("please select end date");
        };
        $(this).removeAttr('checked');
        $(this).val('0');
      }        
  });

  function submitFrm (e) {
    // return false;
    var phone = $('#phone').val();
    if (phone.length>11||phone.length<11) {
      alert("Please enter 11 degit number.");
        // e.preventDefault();
      return false;
    };
    var cfrm = $('#commission_from').val();
    var cto = $('#commission_to').val();
    var cmsn = $('#commission').val();
    if (cmsn>0) {
      if (cto=="0000-00-00"||cto==""||cfrm=="0000-00-00"||cfrm=="") {
        alert("Please select date range.");
        // e.preventDefault();
        return false;
      };
    };
    var dt = $('.datetimepicker');
    // console.log(dt.length);
    // console.log(dt);
    for (var i = 0; i < dt.length; i++) {
      var gg = $('.datetimepicker')[i].value;
      // console.log(gg);
      if (gg=='0000-00-00'||gg=="") {
        console.log(gg);
      } else{
        var result = gg.split('-');
        // console.log(parseInt(result[1]));
        var p = toGregorian(parseInt(result[0]),parseInt(result[1]),parseInt(result[2]));
           // console.log(p);
        // var parray = p.split(',');
        // console.log(p);
        // return false;
        var nd = p.gy+"-"+p.gm+"-"+p.gd;
        // console.log(nd);
        // return false;

        $('.datetimepicker1')[i].value = nd;
      };
      // console.log($('.datetimepicker1')[i].value);
    }
  }
</script>

<script>
    $(document).ready(function() {
        $(".datetimepicker").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: "yy-mm-dd"
        });
    
        $("#datepicker1").datepicker();
        $("#datepicker1btn").click(function(event) {
            event.preventDefault();
            $("#datepicker1").focus();
        })
    
        $("#datepicker2").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true
        });
    
        $("#datepicker3").datepicker({
            numberOfMonths: 3,
            showButtonPanel: true
        });
    
        $("#datepicker4").datepicker({
            changeMonth: true,
            changeYear: true
        });
    
        $("#datepicker5").datepicker({
            minDate: 0,
            maxDate: "+14D"
        });
    
        $("#datepicker6").datepicker({
            isRTL: true,
            dateFormat: "d/m/yy"
        });
    });
</script>