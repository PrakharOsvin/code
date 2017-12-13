<div class="loginbg">
    <div class="container">
<?php

// echo "<pre>"; print_r($department_list); echo "</pre>";die;

?>   
        <h1 class="text-center mT100 white">Send Notification Here</h1>
        <div class="form-signin ">
            <label class="successmessage">
<?php
if ($this->session->flashdata('message'))
{
    echo $this->session->flashdata('message');
} 
?>
            </label>
            <?php echo form_open_multipart('Dashboard/notificationSend');

if ($this->session->flashdata('msg')): ?>     
            <div class="alert alert-success alert-block fade in">
                <button data-dismiss="alert" class="close close-sm" type="button">
                    <i class="fa fa-times"></i>
                </button>
                <h4>
                    <i class="fa fa-ok-sign"></i>
             <?php echo $this->session->flashdata('msg'); ?>
                </h4>
            </div>     
            <?php
endif;
if ($this->session->flashdata('err')): ?>     
            <div class="alert alert-danger alert-block fade in">
                <button data-dismiss="alert" class="close close-sm" type="button">
                    <i class="fa fa-times"></i>
                </button>
                <h4>
                    <i class="fa fa-ok-sign"></i>
             <?php echo $this->session->flashdata('err'); ?>
                </h4>                            
            </div>     
            <?php
endif; ?>  
            <div class="mT30"></div>
            <!-- address input-->
            <div class="control-group">
                <label class="control-label">Message</label>
                <div class="controls">
                    <input id="message" class="form-control" name="message" type="text" value="" placeholder="Message"
                           class="input-xlarge" required maxlength="100">
                    <div class='error'><?php echo form_error('message'); ?></div>
                </div>
                <label class="control-label">Description</label>
                <div class="controls">
                    <input id="description" class="form-control" name="description" type="text" value="" placeholder="description" class="input-xlarge" required>
                    <div class='error'><?php echo form_error('description'); ?></div>
                </div>
                <label class="control-label">Notification Image</label>
                <div class="controls">
                    <input id="notifImage" class="form-control" name="notifImage" type="file" required>
                    <div class='error'><?php echo form_error('notifImage'); ?></div>
                </div>
<div class="checkbox-inline">
    <label><input type="checkbox" name="alluser" class="allUser" value="100">To All Users</label>
</div>
<br />
<label>OR</label>
<br/>
 <div class="checkbox-inline">
  <label><input type="checkbox" name="allmanagers" class="groupUser" value="3">To All Managers</label>
</div>
<div class="checkbox-inline">
  <label><input type="checkbox" name="alldrivers" class="groupUser" value="2">To All Drivers</label>
</div>
<div class="checkbox-inline">
  <label><input type="checkbox" name="allpassengers" class="groupUser" value="0">To All Passengers</label>
</div>
<br />
<label>OR</label>
<br/>
<div class="checkbox-inline">
  <label><input type="checkbox" name="user" id="specificUser" value="">To Specific User</label>
</div>
<br />
<div id="searchUser" style="display: none">
                 <label class="control-label">User Phone</label>
                <div class="controls">
                    <input id="search-box" class="form-control" name="phone" type="text" value="" placeholder="User Phone" class="input-xlarge">
                    <div class='error'><?php echo form_error('phone'); ?></div>
                </div>
                <div id="suggesstion-box"></div>
                
            </div>
</div>
            <br />
            <div class="controls">
                <div class="alert alert-block  fade in" style="display:none"></div>
                <button class="btn btn-lg btn-login btn-block" id="btnsubmit" name="Send" type="submit">Send Message</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>/public/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
// AJAX call for autocomplete 
$(document).ready(function(){
    $("#search-box").keyup(function(){
        $.ajax({
        type: "POST",
        url: "findUser",
        data:'keyword='+$(this).val(),
        beforeSend: function(){
            $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function(data){
            $("#suggesstion-box").show();
            $("#suggesstion-box").html(data);
            $("#search-box").css("background","#FFF");
        }
        });
    });
});
$('#btnsubmit').click(function () {
  var atLeastOneIsChecked = $('input:checkbox').is(':checked');
  // Do something with atLeastOneIsChecked
  if (atLeastOneIsChecked==false) {
    // event.preventDefault()
      alert("Select atleast one option.");
      return false;
  };
});
//To select country name
function selectCountry(val,id) {
$("#specificUser").val(id);  
$("#search-box").val(val);
$("#suggesstion-box").hide();
}
$('.allUser').change(function () {
    if ($(this).attr("checked")) {

        // checked

        console.log("checked");
        $('#searchUser').hide();

        // alert("checked");

        $('.groupUser').attr('checked', true);
        // $('#specificPassenger').attr('checked', false);
        $('#specificUser').attr('checked', false);
        return;
    }

    // if ($('.groupUser:checked').length != $('.groupUser').length) {
    //    //do something

       $('.groupUser').attr('checked', false);

    // }
    // not checked

});
$('.groupUser').change(function () {
    if ($('.groupUser:checked').length == $('.groupUser').length) {

       // do something

       $('.allUser').attr('checked', true);
    }
    if ($(this).attr("checked")) {

        // checked

        console.log("checked");

        // alert("checked");

        $('#searchUser').hide();
        $('#specificUser').attr('checked', false);
        return;
    }
        $('.allUser').attr('checked', false);

    // not checked

});
$('#specificUser').change(function () {
    if ($(this).attr("checked")) {

        // checked

        console.log("checked");

        // alert("checked");

        $('#searchUser').show();
        $('.allUser').attr('checked', false);
        $('.groupUser').attr('checked', false);
        return;
    }

    // not checked

});
$('#specificPassenger').change(function () {
    if ($(this).attr("checked")) {

        // checked

        console.log("checked");

        // alert("checked");

        $('#searchUser').show();
        $('.allUser').attr('checked', false);
        $('.groupUser').attr('checked', false);
        $('#specificUser').attr('checked', false);
        return;
    }

    // not checked

});
</script>
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
<script src="<?php
echo base_url(); ?>/public/js/common-scripts.js"></script>

<!--script for this page-->
<script src="<?php
echo base_url(); ?>/public/js/sparkline-chart.js"></script>
<script src="<?php
echo base_url(); ?>/public/js/easy-pie-chart.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#hidden-table-info').DataTable();
    });
</script>