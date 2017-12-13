<?php
// echo "<pre>";
// print_r($refferralValues);
// echo "</pre>";
?>
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
              <!-- page start-->
              <div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
                          <header class="panel-heading">
                              Advanced Table
                          </header>
                          <table class="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                                  <th><i class="fa fa-bullhorn"></i> Id</th>
                                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> Type</th>
                                  <th><i class="fa fa-bookmark"></i> Value</th>
                                  <th><i class=" fa fa-edit"></i> Action</th>
                              </tr>
                              </thead>
                              <tbody>
                                <?php
foreach ($refferralValues as $value) { ?>
                              <tr>
                                  <td><?php echo "$value->id";?></td>
                                  <td class="hidden-phone"><?php 
if ($value->cupon_type==1) {
                                  echo "Passenger Refferral";
} else {
                                  echo "Driver Refferral";
}
                                ?></td>
                                  <td>
                                    <input id="id-<?php echo "$value->id";?>" btnId="<?php echo "$value->id";?>" class="target" type="number" value="<?php echo "$value->value";?>"/>
                                  </td>

                                  <td>
                                    <button class="btn btn-success btn-xs" id="<?php echo "$value->id";?>" onclick="ChangeCouponVal(this)" disabled>Save</button>
                                  </td>
                                
                              </tr>
<?php }
                                ?>
                              </tbody>
                          </table>
                      </section>
                  </div>
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
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function(){
  $('.table').DataTable({
    stateSave: true
  });


});

$( ".target" ).change(function() {
  // console.log($(this).attr("value"));return false;
  var id=$(this).attr("btnId");
  $("#"+id).removeClass("btn btn-success");
  $("#"+id).addClass("btn btn-warning");
  $("#"+id).html("Save");
  $("#"+id).prop('disabled', false);
});

function ChangeCouponVal(e)
{
  var id=$(e).attr("id");
  var value=$("#id-"+id).attr("value");
// console.log(id+value);return false;
 $.ajax(
             {
                 type: 'post',
                 url: '<?php echo base_url();?>Dashboard/refferralValues',
                 data : {id : id, value : value},
                 cache:false,
                 success: function(data) 
                 {
                    // alert(data);
                     if(data!="") 
                     {
                        $("#id-"+id).val(data);
                        $(e).removeClass("btn btn-warning");
                        $(e).addClass("btn btn-success");
                        $(e).html("Saved");
                        $(e).prop('disabled', true);
                     }   
                 }

                 }); // end ajax  

}
</script>
<!--script for this page only-->
