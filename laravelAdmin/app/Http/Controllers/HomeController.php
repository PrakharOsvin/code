<?php

namespace App\Http\Controllers;
use App\Model\Appusers;
use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
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
        $usersAll = Appusers::orderBy('id', 'desc')->count();
        $users['total']=$usersAll;

        $usersLocalhost = Appusers::where('userType','=',2)->orderBy('id', 'desc')->count();
        $users['localhost']=$usersLocalhost;

        $usersCount = Appusers::where('userType','=',1)->orderBy('id', 'desc')->count();
        $users['user']=$usersCount;

      //  print_r($users);die;
        return view('home')->with('users',$users);;
    }
}
