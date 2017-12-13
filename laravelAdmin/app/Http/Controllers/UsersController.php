<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use App\Model\Appusers;
use Validator;
use File;
class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Appusers::where('userType','!=',0)->orderBy('id', 'desc')->get();
        //$users = Appusers::find(20);
        return view('users')->with('users',$users);
    }

    public function edituser(Request $request)
    {    
         $id = $request->input('id');
         $users = Appusers::where('id',$id)->orderBy('id', 'desc')->first();
   
      ?>

        <form class="form-horizontal" id="edit-form" method="post" action="updateuser" enctype="multipart/form-data">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
        aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="edit-modal-label">Edit User Details</h4>
        </div>
        <div class="modal-body">
        <input type="hidden" id="edit-id" value="" class="hidden">
        <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">Username</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="firstname"
        name="username" placeholder="Username" value="<?php echo $users['username']; ?>" >
        </div>
        </div>
        <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="firstname"
        name="name" placeholder="Name" value="<?php echo $users['name']; ?>" >
        </div>
        </div>
        <div class="form-group">
        <label for="email" class="col-sm-2 control-label">E-mail</label>
        <div class="col-sm-10">
        <input type="test" class="form-control" id="email"
        name="email" placeholder="E-mail address" value="<?php echo $users['email']; ?>" >
        </div>
        </div>
        <div class="form-group">
        <label for="mobile" class="col-sm-2 control-label">Profile Pic</label>
        <div class="col-sm-10">
        <img width='60px' height='60px' src='<?php echo $users['profilePic1']; ?>' />
        <input type="file" name="profilePic1" class="form-control" id="mobile"
        name="mobile" placeholder="Profile">
        </div>
        </div>
        <div class="form-group">
        <label for="mobile" class="col-sm-2 control-label">User Type</label>
        <div class="col-sm-10">
        <select name="userType" class="form-control">
        <option <?php if($users['userType']==1) echo "selected"; ?> value="1">User</option>
        <option <?php if($users['userType']==2) echo "selected"; ?> value="2">Localhost</option>
        </select>
        </div>
        </div>
        </div>
        <div class="modal-footer">
       <input type="hidden" class="form-control" id="userid"
        name="userid" value="<?php echo $users['id']; ?>">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>


     <?php
        
        // //$users = Appusers::find(20);
        // return view('users')->with('users',$users);
    }
    

    
         public function deleteuser(Request $request)
    {    
            $id = $request->input('id');
            Appusers::where('id', '=', $id)->delete();
             Session::flash('message','Deleted Successfully');
              return redirect('/users');
    } 

     public function updateuser(Request $request)
    {    
                $id = $request->input('userid');
                $username = $request->input('username');
                $name = $request->input('name');
                $email = $request->input('email');
                $userType = $request->input('userType');
                $fileName="";


         $validator = Validator::make($request->all(), [
             'username' => 'required|unique:tbl_users,username,'.$id,
             'email' => 'required|email|unique:tbl_users,email,'.$id,
             'userType' => 'required',
             'name' => 'required' // Not sure *why* you would do this, but, it's possible
        ]);

        if($validator->fails()) 
        {
           $validator=$validator->errors();
            return redirect('/users')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
               

              if(isset($_FILES['profilePic1']['name']) && $_FILES['profilePic1']['name']!="")
             {   
              $file = $_FILES['profilePic1'];
              $fileName = $request->file('profilePic1')->getClientOriginalName();
              $fileName = $fileName ?: $file['name'];
              $path = base_path().'/public/profilePic/';
              $fileName=uniqid('IMG_').$fileName;
              $result=$request->file('profilePic1')->move($path , $fileName);
             }

                $data=['username' => $username,
                   'name' => $name,
                   'email' => $email,
                   'userType' => $userType,
                  ];

            if(!empty($fileName))
            {
              $data['profilePic1']="profilePic/".$fileName;
            }       
        


              Appusers::where('id', $id)->update($data);
              Session::flash('message','Update Successfully');
              return redirect('/users');
        }         

   
     } 
    

    
}
