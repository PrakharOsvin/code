<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">

  <section id="main-content">
    <section class="wrapper site-min-height">


         <!-- page start-->
      <section class="panel">
        <header class="panel-heading">Client List</header>
          <div class="panel-body">      
            <div class="adv-table table-responsive">
                  <?php  echo form_open('Users/actuser/0'); ?> 
                  <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered hidden-table-info">
                    <thead>
                      <tr>
                        <th style="width:4%">Sr No</th>
                        <th style="width:6%">User Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th style="width:14%">Profile Pic</th>
                        <th>Registered From</th>
                        <th>Status</th>                      
                        <th style="width:14%">Action</th>
                      </tr>
                    </thead>
                  <tbody>
                
                   <?php  
                   $sid = 1;            
                   foreach ($client_list as $user) {
                    
                    ?>
                    <tr>
                      <td><?php echo $sid; ?></td>
                      <td><?php echo $user->id; ?></td>
                      <td><?php if ($user->name!="") {
                      echo $user->name;
                      } else {
                        echo $user->first_name; echo $user->last_name;
                      }
                      ?></td>
                      <td><?php echo $user->email; ?></td>
                      <td><?php echo $user->phone; ?></td>
                      <td><?php if ($user->profile_pic){?><img src="<?php echo $user->profile_pic; ?>" width='100'/><?php } else{ ?><img src="<?php echo base_url('public/images/mythumb.gif'); ?>" /><?php } ?></td>
                      <td><?php echo date('d-M-Y g:i a', strtotime($user->date_created)); ?></td>  
                      <td> 
                           <?php if($user->status == '0'){ ?>
                            <a onclick="ChangeUserStatus(this,'<?php echo $user->id; ?>')" statusid="1" style="border-radius:4px !important;"  class='activate-<?php echo $user->id; ?> btn btn-warning'>Activate</a> 
                          <?php } elseif($user->status == '1') { ?>
                            <a onclick="ChangeUserStatus(this,'<?php echo $user->id; ?>')" statusid="0"  style="border-radius:4px !important;"  class='activate-<?php echo $user->id; ?> btn btn-danger'>Suspend</a> 
                          <?php } ?>
                          <img src="<?php echo base_url();?>/public/images/rolling.gif" id="loader<?php echo $user->id; ?>" alt="Loading..." height="10" width="10" style="display:none;" /> 

                     </td> 
                     
                      <td>
                        <a href="<?php echo base_url('Dashboard/profile').'/'.$user->id; ?>" style="border-radius:4px !important;" name="view" class='btn btn-primary'>View</a>
                        <a href="<?php echo base_url('Users/edit').'/'.$user->id; ?>" style="border-radius:4px !important;" name="edit" class='btn btn-success'>Edit</a>                                    
                        <!-- <a href="<?php echo base_url('Users/deleteall').'/'.$user->id.'?action=users_list'; ?>" name="delete" onclick="return confirm('Are You Sure?')" class="btn btn-success">Delete</a>  -->
                      </td>
                    </tr>
                    <?php  
                    $sid++; }?>            
                  </tbody>
                </table>
              </form>
            </div>
          </div>
      </section>
      <!-- page end-->
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
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
  $('.hidden-table-info').DataTable({
    stateSave: true,
  });
});
function ChangeUserStatus(e,id)
{
var statusid=$(e).attr("statusid");
 $.ajax(
             {
                 type: 'post',
                 url: '<?php echo base_url();?>Dashboard/ChangeUserStatus',
                 data : {statusid : statusid, id : id},
                 cache:false,
                 beforeSend: function(){
                    $('#loader'+id).css("display", "block");
                  },
                 success: function(data) 
                 {
                    //alert(data);
                     if(data!="") 
                     {
                      if(data.trim()=="1")
                      {
                          $(e).attr( 'statusid','0');
                         $(e).removeClass("btn btn-warning");
                          $(e).addClass("btn btn-danger");
                           $(e).html("Suspend");
                           
                      }
                      if(data.trim()=="0")
                      {
                        $(e).attr( 'statusid','1');
                        $(e).removeClass("btn btn-danger");
                        $(e).addClass("btn btn-warning");
                         $(e).html("Activate");
                      }
                     }   
                 },
                 complete: function(){
                    $('#loader'+id).css("display", "none");
                  }

                 }); // end ajax  

}
</script>