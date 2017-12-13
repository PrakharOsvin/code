<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">User Details</header>
      <div class="panel-body">      
        <div class="adv-table table-responsive">
        
                         <form method="post" id="register-form" action="<?php echo base_url(); ?>Dashboard/addSubtractFundEdit/<?php echo $one_user_info[0]->id; ?>">
                        <table cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
                            <tbody>
                               <?php
                                foreach($one_user_info as $record){
                                  // print_r($one_user_info);die;?>


                                 <tr>
                                     <td>User Id:</td><td><?php echo $record->id; ?></td>
                                </tr>
                                <tr>
                                   <td>Name:</td> <td><input type = "text" name="first_name" value="<?php echo $record->first_name; ?>"></td>
                               </tr>
                                  <tr>
                                <td>Phone:</td><td><input type = "text" name="phone" value="<?php echo $record->phone; ?>"></td> 
                                </tr>
                                <tr>
                                <td>Home Address:</td><td><input type = "text" name="address" value="<?php echo $record->address; echo $record->home_address;?>"></td> 
                                </tr>
                                <tr>
                                <td>Work Address:</td><td><input type = "text" name="work_address" value="<?php echo $record->work_address;?>"></td> 
                                </tr>
                                <tr>
                                <td>Wallet Balance:</td>
                                <td>
                                  <?php echo $record->wallet_balance;?>
                                </td> 
                                </tr>
                                <tr>
                                <td>Add Funds:</td><td><input type = "number" name="addFunds" value=""></td> 
                                </tr>
                                <tr>
                                <td>Subtract Funds:</td><td><input type = "number" name="subtractFunds" value=""></td> 
                                </tr>
                                <tr>
                                  <td>Profile Pic:</td><td><img src="<?php echo $record->profile_pic; ?>" width='200'/></td>
                                </tr>
                                <td>Change Profile pic:</td><td><input type = "file" name="profile_pic"></td>
                                </tr>
                                 <tr>
                                     <td>Email:</td><td><?php echo $record->email; ?></td>
                                </tr>
             
                                <tr>
                                      <td>Registered From:</td><td><?php echo date('d-M-Y H:i:s', strtotime($record->date_created)); ?></td>
                                </tr>                  
                             
                             <?php } ?>
                            </tbody>
                        </table>
                      

                        <br>
                        <input name="submit" type="submit" class="btn btn-primary" value="Submit">
                        <br>
                        <br>
                        <a href="<?php echo base_url('Dashboard/addSubtractFund').'/'.$record->user_type ?>" class="btn btn-success">Back to Add/Substract Funds</a>
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
<script src="<?php echo base_url();?>/public/js/sparkline-chart.js"></script>
<script src="<?php echo base_url();?>/public/js/easy-pie-chart.js"></script>
<script src="<?php echo base_url();?>/public/js/count.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script type="text/javascript">

var vlogin = $("#register-form").validate({
        rules: {
            cname: {
                required: true
            }
        },
        messages: {
            cname: {
                required: "Please enter name"
            }
    
        }
    });


</script>
