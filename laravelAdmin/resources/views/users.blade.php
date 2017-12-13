@extends('layouts.app')
@extends('layouts.sidebar')
@section('content')
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css">
<section id="main-content">
  <section class="wrapper site-min-height">
 
 @if(Session::has('message'))
 <div class="alert alert-success  success-block fade in">
        <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
        <h4 style="">
          <i class="fa fa-ok-sign"></i>
            <div>{{ Session::get('message') }}</div>
        </h4>
      </div> 
@endif


@if($errors->has())
       <div class="alert alert-danger  danger-block fade in">
        <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
        <h4 style="">
          <i class="fa fa-ok-sign"></i>
         @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
        </h4>
      </div> 
 @endif


    <!-- page start-->
    <section class="panel">
    <header class="panel-heading"> User List</header>
    <div class="panel-body">
      <div class="adv-table table-responsive">
        <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">
          <thead>
            <tr>
              <th>userId</th>
              <th>profilePic1</th>
              <th>username</th>
              <th>name</th>
              <th>email</th>
              <th>userType</th>
              <th>date_created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
                
           <?php foreach ($users as $usersvalue) {
           
           ?>
                  <tr id="UserRowId<?php echo $usersvalue['id'];?>" class="gradeX">
                    <td><?php echo $usersvalue['id']; ?></td>
                      <td><?php if(empty($usersvalue['profilePic1'])) echo "N/A"; else echo "<img width='60px' height='60px' src='$usersvalue[profilePic1]' />"; ?></td>
                   
                    <td><?php echo $usersvalue['username']; ?></td>
                    <td><?php echo $usersvalue['name']; ?></td>
                    <td><?php echo $usersvalue['email']; ?></td>
                    <td><?php if($usersvalue['userType']==1) echo "User"; if($usersvalue['userType']==2) echo "Localhost"; ?></td>
                    <td><?php echo $usersvalue['date_created']; ?></td>
                    <td>
                      <a onclick="EditUser(<?php echo $usersvalue['id']; ?>)" style="color:white"><span class="btn btn-shadow btn-danger">Edit</span></a>
                      <a onclick="DeleteUser(<?php echo $usersvalue['id']; ?>)"   style="color:white"><span class="btn btn-shadow btn-danger">Delete</span></a>
                    </td>
                  </tr>
              <?php } ?>    
          </tbody>
        </table>
      </div>
    </div>
    </section>
    <!-- page end-->
  </section>
</section>


<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
<div class="modal-dialog" role="document">
 <input type="hidden" id="crf_token" name="_token" value="{{ csrf_token() }}">
<div class="modal-content" id="modal-content">
</div>
</div>
</div>



  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>
  <script>
 $(document).ready(function() {
  var oTable = $('#hidden-table-info').dataTable( {
              
               "aaSorting": []
          });

});


function EditUser(id)
{

var crf_token=$('#crf_token').val();

 $.ajax(
               {
                 type: 'post',
                 url: '{{ url('/edituser') }}',
                 data : { id : id, _token:crf_token},
                 cache:false,
                 success: function(data) {
                    //alert(data);
                     if(data!="") {
                         $('#modal-content').html(data);
                         $('#edit-modal').modal('show');
                     }   
                   
                 }
                 }); // end ajax  

}

function DeleteUser(id)
{

      var Conf= confirm("Are you sure?");
       if(Conf==true)
          {
          var fullurlpath=window.location.href; 
           var spliturl=fullurlpath.split("/");
           var pop_element=spliturl.pop();
           var newpathurl=spliturl.join("/");
           var newurl_redirect=newpathurl+"/deleteuser?id="+id;
              window.location=newurl_redirect;  
          }
       else
          {
       return false;
          }

}

</script>
@endsection
