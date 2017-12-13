<script src="{{URL::asset('assets/js/jquery.js')}}" src="js/jquery.js"></script>
    <script src="{{URL::asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/jquery.dcjqaccordion.2.7.js')}}" class="include" type="text/javascript" ></script>
    <script src="{{URL::asset('assets/js/jquery.scrollTo.min.js')}}" ></script>
    <script src="{{URL::asset('assets/js/jquery.sparkline.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('assets/js/jquery.easy-pie-chart.js')}}" ></script>
    <script src="{{URL::asset('assets/js/owl.carousel.js')}}"  ></script>
    <script src="{{URL::asset('assets/js/jquery.customSelect.min.js')}}"  ></script>
    <script src="{{URL::asset('assets/js/respond.min.js')}}"  ></script>

    <!--common script for all pages-->
    <script src="{{URL::asset('assets/js/common-scripts.js')}}"  ></script>


<!--script for this page-->
<script src="{{URL::asset('assets/js/count.js')}}"  ></script>
<aside>
<div id="sidebar"  class="nav-collapse ">
  <!-- sidebar menu start-->
  <ul class="sidebar-menu" id="nav-accordion">
    <li>
      <a class="active" href="{{ url('/') }}">
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
        <li><a  href="{{ url('/users') }}">All</a></li>
      
      <!--   <li>
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
    </li> -->
    
    <!--multi level menu end-->
  </ul>
</li>
</ul>
  <!-- sidebar menu end-->
</div>
</aside>
<!--sidebar end-->
                   