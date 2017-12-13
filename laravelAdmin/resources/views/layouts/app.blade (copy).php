<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
      <link href="{{URL::asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/css/bootstrap-reset.css')}}" rel="stylesheet">
    <!--external css-->
    <link rel="stylesheet" href="{{URL::asset('assets/css/owl.carousel.css')}}"  type="text/css">
    
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{URL::asset('assets/css/style.css')}}"  type="text/css">
    <link rel="stylesheet" href="{{URL::asset('assets/css/style-responsive.css')}}"  type="text/css">
    


    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Laravel
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Home</a></li>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Dashboard-TravelFlix</title>
  </head>
  <body>
  <section id="container" >
  
      <!--header start-->
      <header class="header white-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.php" class="logo" style="margin-top: 7px;"><img src="img/logo@3x.png" width="160px;" /></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu" style="margin-top: 10px;">
                <ul class="nav top-menu">
                <h4 style="margin-left: -15%;width: 100%;">Welcome to Twipply App Dashboard</h4>
                </ul>
            </div>
                   
            
            <div class="top-nav ">
                <!--search & user info start-->
                <ul class="nav pull-right top-menu">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <img alt="" src="img/avatar1_small.jpg" width="30px;">
                            <span class="username"><?php //echo $user_getdata['email'];?></span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li><a href="logout.php"><i class="fa fa-key1"></i> Log Out</a></li>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                </ul>
                <!--search & user info end-->
            </div>
        </header>
      <!--header end-->


     <!--sidebar start-->
<aside>
<div id="sidebar"  class="nav-collapse ">
  <!-- sidebar menu start-->
  <ul class="sidebar-menu" id="nav-accordion">
    <li>
      <a class="active" href="index.php">
        <i class="fa fa-dashboard"></i>
        <span>Dashboard</span>
      </a>
    </li>
  
    <li class="sub-menu">
      <a href="javascript:;" >
        <i class="fa fa-users"></i>
        <span>Users</span>
      </a>
      <ul class="sub">
        <li><a  href="allUsers.php">All Users</a></li>
        <li><a  href="activeUsersList.php">Active Users</a></li>
        <li><a  href="suspendedUsersList.php">Suspended Users</a></li>
         <li><a  href="businessUsersList.php">All Business Users</a></li>
          <li><a  href="un_businessUsersList.php">Unverified Business Users</a></li>
         <li class="sub-menu">
          <a href="javascript:;" >
            <span>Ratings</span>
          </a>
          <ul class="sub">
            <li><a  href="ratingsList.php">Rated Users</a></li>
            <li><a  href="unratedUsers.php">Un-Rated Users</a></li>
          </ul>
       </li>
        <li>
      <a href="userBalance.php">
        <span>User Balance</span>
      </a>
    </li>
        <li class="sub-menu">
            <a href="javascript:;" >
            <span>Sub-Admin</span>
            </a>
            <ul class="sub">
            <li><a  href="addSubAdmin.php">Add New</a></li>
            <li><a  href="subAdminList.php">List</a></li>
          </ul>
       </li>
      </ul>
    </li>
  <li class="sub-menu">
      <a href="javascript:;" >
        <i class="fa fa-users"></i>
        <span>Business</span>
      </a>
      <ul class="sub">
         <li><a href="EditBroadcastConstant.php">BroadCast Constant</a></li>
       <li class="sub-menu">
      <a href="javascript:;" >
        <span>Push Rating Cost</span>
      </a>
      <ul class="sub">
        <li><a  href="AddPushRating.php">Add</a></li>
        <li><a  href="ListPushRating.php">List</a></li>
      </ul>
    </li>
      </ul>

    </li>
     <li class="sub-menu">
      <a href="javascript:;" >
        <i class="fa fa-users"></i>
        <span>Coins</span>
      </a>
      <ul class="sub">
        
     <li class="sub-menu"><a href="javascript:;" ><span>Tips History</span></a>
         <ul class="sub">
         <li><a  href="tipsHistory.php">Tipped Users</a></li>
         <li><a  href="notTippedUsers.php">Un-Tipped Users</a></li>
        </ul>
    </li>
    <li>
      <a href="ManageCurrencyConversion.php">
        <span>Currency Conversion</span>
      </a>
    </li>
      <li>
      <a href="AddShareAmount.php">
        <span>Share Amount</span>
      </a>
    </li>
      
    
    <li>
      <a href="ManageFIxedFees.php">
        <span>Fixed Fees</span>
      </a>
    </li>
       <li>
      <a href="signUp_amount.php">
        <span>SignUp Amount</span>
      </a>
    </li>
    
   
       
      </ul>
    </li> 
    <li class="sub-menu">
      <a href="javascript:;" >
        <i class="fa fa-users"></i>
        <span>Configuration</span>
      </a>
      <ul class="sub">
        
        <li class="sub-menu"><a href="javascript:;" ><span>Job Type</span></a>
        <ul class="sub">
          <li><a  href="AddJobType.php">Add New</a></li>
          <li><a  href="JobList.php">List</a></li>
        </ul>
        </li>  
         
      <li>
      <a href="errorList.php">
        <span>Error Log</span>
      </a>
    </li>  
     <li>
      <a href="addTnC.php">
        <span>Terms & Conditions</span>
      </a>
    </li>
      </ul>
    </li>
    
   
   
    <li class="sub-menu">
      <a href="javascript:;" >
        <i class="fa fa-th-list"></i>
        <span>Withdrawal</span>
      </a>
      <ul class="sub">
        <li><a  href="approvedRequests.php">Approved Withdrawal</a></li>
        <li><a  href="pendingRequests.php">Pending Withdrawal</a></li>        
        <li><a  href="declinedRequests.php">Declined Withdrawal</a></li>
      </ul>
    </li>
     
    
     <li class="sub-menu">
      <a href="javascript:;" >
        <i class="fa fa-th-list"></i>
        <span>Gift Cards</span>
      </a>
      <ul class="sub">
        <li><a  href="ImportGiftCards.php">Import Gift Cards</a></li>
        <li><a  href="ListGiftCards.php">All Gift Cards</a></li>        
      </ul>
    </li>
  
    <li>
      <a href="reports.php">
        <i class="fa fa-th-list"></i>
        <span>Reports</span>
      </a>
    </li>
    
    <!--multi level menu end-->
  </ul>
  <!-- sidebar menu end-->
</div>
</aside>
<!--sidebar end-->


    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
 
    <script src="{{URL::asset('assets/js/jquery.js')}}" src="js/jquery.js"></script>
    <script src="{{URL::asset('assets/js/jquery-1.8.3.min.js')}}" ></script>
    <script src="{{URL::asset('assets/js/jquery.dcjqaccordion.2.7.js')}}" class="include" type="text/javascript" ></script>
    <script src="{{URL::asset('assets/js/jquery.scrollTo.min.js')}}" ></script>
    <script src="{{URL::asset('assets/js/jquery.nicescroll.js')}}"  type="text/javascript"></script>
    <script src="{{URL::asset('assets/js/jquery.sparkline.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('assets/js/jquery.easy-pie-chart.js')}}" ></script>
    <script src="{{URL::asset('assets/js/owl.carousel.js')}}"  ></script>
    <script src="{{URL::asset('assets/js/jquery.customSelect.min.js')}}"  ></script>
    <script src="{{URL::asset('assets/js/respond.min.js')}}"  ></script>
    <script src="{{URL::asset('assets/js/jquery.dcjqaccordion.2.7.js')}}"  class="include" type="text/javascript" ></script>

<!--common script for all pages-->
   <script src="js/common-scripts.js"></script>

<!--script for this page-->
<script src="{{URL::asset('assets/js/sparkline-chart.js')}}"  ></script>
<script src="{{URL::asset('assets/js/easy-pie-chart.js')}}"  ></script>
<script src="{{URL::asset('assets/js/count.js')}}"  ></script>

    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
