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
                              Commission Levels Setting
                          </header>
                          <div class="btn-group">
                              <button id="editable-sample_new" class="btn green" onclick="addNew(this)">
                                  Add New <i class="fa fa-plus"></i>
                              </button>
                          </div>
                          <table id="myTable" class="table table-striped table-advance table-hover">
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
foreach ($commissionLevels as $value) { ?>
                              <tr trid="<?php echo "$value->id";?>">
                                  <td><?php echo "$value->id";?></td>
                                  <td class="hidden-phone"><?php echo "$value->commissionLevel";?></td>
                                  <td>
                                    <input id="id-<?php echo "$value->id";?>" btnId="<?php echo "$value->id";?>" class="target" type="number" value="<?php echo "$value->commissionPrcnt";?>"/>
                                  </td>

                                  <td>
                                    <button class="btn btn-success btn-xs" id="<?php echo "$value->id";?>" onclick="commissionLevels(this)" disabled>Save</button>
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
$( ".table").on("change",".target",function() {
  console.log($(this).attr("value"));
  var prcnt = $(this).attr("value");
  console.log(prcnt);
  if (parseInt(prcnt)>100||parseInt(prcnt)<0) {
    alert("Commision Percentage is not valid.");
    $("#"+id).prop('disabled', true);
  return false;
  };
  console.log("pass");
  var id=$(this).attr("btnId");
  $("#"+id).removeClass("btn btn-success");
  $("#"+id).addClass("btn btn-warning");
  $("#"+id).html("Save");
  $("#"+id).prop('disabled', false);
});

function addNew (e) {
  $(e).prop('disabled', true);
  var prew = $( "#myTable tr:last" ).attr("trid");
  
  console.log(prew);
  if (prew=== undefined) {
    var trid = 1;
  } else{
    var trid = parseInt(prew)+1;
  };
  console.log(trid);
  var val = $( "#myTable tr:last td:nth-child(2)" ).html();
  if (val=== undefined) {
    var alpha = "A";
  } else{
    var alpha = nextChar(val);
  };
  console.log(val);
  console.log(alpha);
  $tr = '<tr trid="'+trid+'"><td>'+trid+'</td><td class="hidden-phone">'+alpha+'</td><td><input id="id-'+trid+'" btnId="" class="target" type="number" value=""/></td><td><button class="btn btn-success btn-xs" id="'+trid+'" alpha="'+alpha+'" onclick="insertNew(this)" >Save</button></td></tr>';
  $('#myTable tbody tr:first').before($tr);
   $( "#id-"+trid+"" ).focus();
}

function nextChar(c) {
    return String.fromCharCode(c.charCodeAt(0) + 1);
}

function insertNew (e) {
  var id=$(e).attr("id");
  var value=$("#id-"+id).attr("value");
  var alpha=$(e).attr("alpha");
  console.log(id);
  console.log(alpha);
  console.log(value);

  $.ajax({
    type: 'post',
    url: '<?php echo base_url();?>Dashboard/addCommLevel',
    data : {commissionLevel : alpha, commissionPrcnt : value},
    cache:false,
    dataType: "json",
    success: function(data) 
    {
      console.log(data);
      // alert(data);
       if(data!="") 
       {
        $("#myTable tbody tr:first").remove();
        $tr = '<tr trid="'+data.id+'"><td>'+data.id+'</td><td class="hidden-phone">'+data.commissionLevel+'</td><td><input id="id-'+data.id+'" btnId="'+data.id+'" class="target" type="number" value="'+data.commissionPrcnt+'"/></td><td><button class="btn btn-success btn-xs" id="'+data.id+'" onclick="commissionLevels(this)" disabled>Save</button></td></tr>';
        $('#myTable tr:last').after($tr);
        $("#editable-sample_new").prop('disabled', false);
       }   
    }
  }); // end ajax 

}

function commissionLevels(e)
{
  var id=$(e).attr("id");
  var value=$("#id-"+id).attr("value");
// console.log(id+value);return false;
  $.ajax(
    {
      type: 'post',
      url: '<?php echo base_url();?>Dashboard/commissionLevels',
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
