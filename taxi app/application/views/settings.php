<style type="text/css">
.material-switch > input[type="checkbox"] {
    display: none;   
}

.material-switch > label {
    cursor: pointer;
    height: 0px;
    position: relative; 
    width: 40px;  
}

.material-switch > label::before {
    background: rgb(0, 0, 0);
    box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
    border-radius: 8px;
    content: '';
    height: 16px;
    margin-top: -8px;
    position:absolute;
    opacity: 0.3;
    transition: all 0.4s ease-in-out;
    width: 40px;
}
.material-switch > label::after {
    background: rgb(255, 255, 255);
    border-radius: 16px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    content: '';
    height: 24px;
    left: -4px;
    margin-top: -8px;
    position: absolute;
    top: -4px;
    transition: all 0.3s ease-in-out;
    width: 24px;
}
.material-switch > input[type="checkbox"]:checked + label::before {
    background: inherit;
    opacity: 0.5;
}
.material-switch > input[type="checkbox"]:checked + label::after {
    background: inherit;
    left: 20px;
}

</style>
<section id="main-content">
    <section class="wrapper">

		<div class="container">
		    <h3 class="text-center">Enable Settings Here</h3>
		    <hr>
		</div>

		<div class="container">
		    <div class="row">
		        <div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
		        	<form method="GET" action="http://91.232.66.67/Admin/Dashboard/assign_permition">
		        		<input type="hidden" name="department_id" value="13">
		        		<input type="hidden" name="department_name" value="Super Admin">
		            <div class="panel panel-default">
		                <!-- Default panel contents -->
		                <div class="panel-heading">Setting&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspStatus</div>
		            
		                <!-- List group -->
		                <ul class="list-group">
		                	<?php
foreach ($settings as $value) { ?>
	                    	<li class="list-group-item">
		                        Female Profile Pic Privacy
		                        <div class="material-switch pull-right">
		                            <input id="<?php echo $value->id; ?>" name="Add_promo" type="checkbox" value="" onclick="ChangeSettingStatus(this,'<?php echo $value->id; ?>')"
		                            <?php 
if ($value->status==1) {
	echo "checked";
}
		                            ?> />
		                            <label for="<?php echo $value->id; ?>" class="label-primary"></label>
		                        </div>
		                    </li>

<?php } ?>
							
		                </ul>
		            </div>
		            </form>
		        </div>
		    </div>
		</div>
    </section>
</section>
<script src="<?php echo base_url();?>/public/js/jquery.js"></script>
<script src="<?php echo base_url();?>/public/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
function ChangeSettingStatus(e,id)
{
// console.log(value);
// console.log(e);
if($(e).is(":checked")){

    var status =1;
}
else if($(e).is(":not(:checked)")){

    var status =0;
}
$.ajax(
	{
		type: 'post',
		url: '<?php echo base_url();?>Dashboard/ChangeSettingStatus',
		data : {status : status, id : id},
		cache:false,
		success: function(data) 
		{
			console.log(data);
			/*if(data!="") 
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
			}   */
		}

	}); // end ajax  

}
</script>
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
