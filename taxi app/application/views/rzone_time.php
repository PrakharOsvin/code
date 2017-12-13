<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/public/css/jquery.datetimepicker.css"/>
<section id="main-content">
  <section class="wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
      <header class="panel-heading">User Details</header>
      <div class="panel-body">      
        <div class="adv-table table-responsive" class="row">
        
          <form method="post" id="register-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
            <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-6">
            <table method = "post" cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
            <thead>
              <tr>
                <th style="width:4%">Sr No</th>
                <th style="width:6%">Day</th>
                <th>Zone</th>
                <th>From</th>
                <th>To</th>
              </tr>
            </thead>
              <tbody>
                 <?php
                 // echo "<pre>";
                 // print_r($one_user_info);
                 // echo "</pre>";
                 // die;  
                 $i = 0;

                  foreach($rzone_time as $record){
                    ?>
                   <tr>
                    <td><?php echo $record->id; ?></td>
                    <td><?php echo $record->day; ?></td>
                    <td><?php echo $record->zone; ?></td>
                    <td><input type = "text" name="start[<?php echo $record->id; ?>]" class="datetimepicker" value="<?php echo $record->start_time;?>"></td>
                    <td><input type = "text" name="end[<?php echo $record->id; ?>]" class="datetimepicker" value="<?php echo $record->end_time;?>"></td>
                   <!--  <td><input type = "hidden" name="hidden<?php// echo $i; ?>" value="<?php //echo $record->id; ?>"></td> -->
                  </tr>                  
               
               <?php $i++;
               } ?>
              </tbody>
          </table>
          <?php
                if ($record->user_type==2) { ?>
            <div class="bio-desk">
                <h4 class="terques">Permit A Validity</h4>
                <table>
                  <tbody cellpadding="0" cellspacing="0" border="1" class="display table-bordered" id="hidden-table-info1">
                    <tr>
                      <td>
                        From : 
                      </td>
                      <td>
                        <input type = "text" name="from" class="datetimepicker" value="<?php echo $record->from;?>">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        To : 
                      </td>
                      <td>
                        <input type = "text" name="to" class="datetimepicker" value="<?php echo $record->to;?>">
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
           <?php } ?>
          </div>
          </div>

          <br>
          <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-6">
              <input name="Submit" type="submit" class="btn btn-primary" value="Submit">
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
  format:'H:i:00'
  });
  // $('.datetimepicker').datetimepicker({value:'2015/04/15 05:03',step:10});
</script>
