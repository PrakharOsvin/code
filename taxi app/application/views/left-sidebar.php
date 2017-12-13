<?php
 // if ($_SESSION['logged_in']['user_type']=='1') { 
?> 
<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <li><?php echo anchor('Dashboard', '<i class="fa fa-dashboard"></i><span>Dashboard</span>'); ?></li>
            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-users"></i>
                    <span>Users</span>
                </a>
                <ul class="sub">
                    <li><?php echo anchor('Users/users_list', '<i class="fa fa-list-ol"></i><span>All Users List</span>') ?></li>
                    <li><?php echo anchor('Users/manager_list', '<i class="fa fa-list-ol"></i><span>Manager List</span>') ?></li>
                    <li><?php echo anchor('Users/client_list', '<i class="fa fa-list-ol"></i><span>Client List</span>') ?></li>
                    <li><?php echo anchor('Users/driver_list', '<i class="fa fa-list-ol"></i><span>Driver List</span>') ?></li>
                    <li><?php echo anchor('Dashboard/logedInDrivers', '<i class="fa fa-list-ol"></i><span>Loged in Drivers</span>') ?></li>
                    <!--<li><a  href="declined_loan">Declined Loan</a></li>-->
                </ul>
            </li>
<!--            <li><?php // echo anchor('Users/add_users', '<i class="fa fa-users"></i><span>Add Users</span>') ?></li>-->
            
            <li><?php echo anchor('Users/add_users', '<i class="fa fa-plus-square"></i><span>Add Managers</span>') ?></li>
            <li><?php echo anchor('Driver/add_driver', '<i class="fa fa-plus-square"></i><span>Add Driver</span>') ?></li>
            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-home"></i>
                    <span>Vehicle</span>
                </a>
                <ul class="sub">
            <li><?php echo anchor('Dashboard/add_vehicle', '<i class="fa fa-plus-square"></i><span>Add Vehicle types</span>') ?></li>
            <li><?php echo anchor('Dashboard/add_vehicle_model', '<i class="fa fa-plus-square"></i><span>Add Vehicle Model</span>') ?></li>
            <li><?php echo anchor('Dashboard/vehicle_type_list', '<i class="fa fa-list-ol"></i><span>Vehicle type List</span>') ?></li>
                </ul>
            </li>
            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-home"></i>
                    <span>Holidays</span>
                </a>
                <ul class="sub">
            <li><?php echo anchor('Dashboard/holiday_list', '<i class="fa fa-list-ol"></i><span>Holidays List</span>') ?></li>
            <li><?php echo anchor('Dashboard/add_holidays', '<i class="fa fa-plus-square"></i><span>Add Holidays</span>') ?></li>
                </ul>
            </li>
            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-archive"></i>
                    <span>Archive/Ride History</span>
                </a>
                <ul class="sub">
                    <li><?php echo anchor('Dashboard/completedRides', '<i class="fa fa-archive"></i><span>Completed</span>') ?></li>
                    <li><?php echo anchor('Dashboard/closedRides', '<i class="fa fa-archive"></i><span>Closed</span>') ?></li>
                </ul>
            </li> 
            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-globe"></i>
                    <span>Maps</span>
                </a>
                <ul class="sub">
                    <!-- <li><?php echo anchor('Dashboard/googlemaps', '<i class="fa fa-globe"></i><span>All maps</span>') ?></li> -->
                    <li><?php echo anchor('Dashboard/AvailableDrivers', '<i class="fa fa-globe"></i><span>Available Drivers</span>') ?></li>
                    <!-- <li><?php echo anchor('Dashboard/NewRequests', '<i class="fa fa-globe"></i><span>New Requests</span>') ?></li> -->
                    <li><?php echo anchor('Dashboard/OngoingTrips', '<i class="fa fa-globe"></i><span>Ongoing Trips</span>') ?></li>

                     <li><?php echo anchor('Dashboard/unbookedRides', '<i class="fa fa-globe"></i><span>Not Booked Data</span>') ?></li>
                      <li><?php echo anchor('Dashboard/bookedRides', '<i class="fa fa-globe"></i><span>Booked Data</span>') ?></li>
                </ul>
            </li>
            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-flag"></i>
                    <span>Reports</span>
                </a>
                <ul class="sub">
                    <li><?php echo anchor('Dashboard/driverStats', '<i class="fa fa-list-ol"></i><span>Driver Stats</span>') ?></li>
                    <li><?php echo anchor('Dashboard/reports', '<i class="fa fa-list-ol"></i><span>Reports</span>') ?></li>
                    <li><?php echo anchor('Dashboard/reports/4', '<i class="fa fa-list-ol"></i><span>Report of Friday</span>') ?></li>
                    <li><?php echo anchor('Dashboard/reports/6', '<i class="fa fa-list-ol"></i><span>Report of Sunday</span>') ?></li>
                    <li><?php echo anchor('Dashboard/reports/2', '<i class="fa fa-list-ol"></i><span>Report of Wednesday</span>') ?></li>
                    <li><?php echo anchor('Dashboard/driver_cancelled_trips', '<i class="fa fa-list-ol"></i><span>Driver Cancelled Trips</span>') ?></li>
                    <li><?php echo anchor('Dashboard/user_cancelled_trips', '<i class="fa fa-list-ol"></i><span>User Cancelled Trips</span>') ?></li>
                    <li><?php echo anchor('Dashboard/low_rating', '<i class="fa fa-star-half"></i><span>Rating and Feedback</span>') ?></li>
                    <li><?php echo anchor('Dashboard/low_overall_rating', '<i class="fa fa-star-half-o"></i><span>overall rating</span>') ?></li>
                    <!-- <li><?php echo anchor('Dashboard/winners', '<i class="fa fa-users"></i><span>winners</span>') ?></li> -->
                    <li><?php echo anchor('Dashboard/alert', '<i class="fa fa-users"></i><span>Alerts </span>') ?></li>
                </ul>
            </li>
            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-gift"></i>
                    <span>Coupon/Promotions</span>
                </a>
                <ul class="sub">
                    <li><?php echo anchor('Dashboard/Add_promo','<i class="fa fa-plus-square fa-fw"></i><span>Add Promo</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/refferralValues','<i class="fa fa-plus-square fa-fw"></i><span>Set Coupon Value</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/cupon_list','<i class="fa fa-book fa-fw"></i><span>Coupon List</span>')  ?></li>
                </ul>
            </li>
            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-gift"></i>
                    <span>Notifications</span>
                </a>
                <ul class="sub">
                    <li><?php echo anchor('Dashboard/notificationList','<i class="fa fa-plus-square fa-fw"></i><span>Notification List</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/notificationSend','<i class="fa fa-book fa-fw"></i><span>Send Notification</span>')  ?></li>
                </ul>
            </li>
            <li><?php echo anchor('Dashboard/payments', '<i class="fa fa-credit-card"></i><span>All payments</span>') ?></li>
            
            <li><?php echo anchor('Dashboard/estimateCalculator', '<i class="fa fa-wrench"></i><span>Estimate Calculator</span>') ?></li>
            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-wrench"></i>
                    <span>Add/Subtract Funds</span>
                </a>
                <ul class="sub">
                    <li><?php echo anchor('Dashboard/addSubtractFund/0','<i class="fa fa-book fa-fw"></i><span>Client Funds</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/addSubtractFund/2','<i class="fa fa-book fa-fw"></i><span>Driver Funds</span>')  ?></li>
                </ul>
            </li>

            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-wrench"></i>
                    <span>Dispatcher Alerts</span>
                </a>
                <ul class="sub">
                    <!-- <li><?php echo anchor('Dashboard/idleRides','<i class="fa fa-book fa-fw"></i><span>Idle Rides</span>')  ?></li> -->
                    <li><?php echo anchor('Dashboard/driverCancelledRides', '<i class="fa fa-archive"></i><span>Driver Cancelled</span>') ?></li>
                    <li><?php echo anchor('Dashboard/clientCancelledRides', '<i class="fa fa-archive"></i><span>Passenger Cancelled</span>') ?></li>
                    <li><?php echo anchor('Dashboard/requestCancelled', '<i class="fa fa-archive"></i><span>Request Cancelled</span>') ?></li>
                    <li><?php echo anchor('Dashboard/driver_refuses', '<i class="fa fa-users"></i><span>driver refuses </span>') ?></li>
                </ul>
            </li>

            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-wrench"></i>
                    <span>Zone Settings</span>
                </a>
                <ul class="sub">
                    <li><?php echo anchor('Dashboard/zone_setting','<i class="fa fa-book fa-fw"></i><span>Smog Scenario</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/rzone_time','<i class="fa fa-book fa-fw"></i><span>Zone Timing</span>')  ?></li>
                    
                </ul>
            </li>

            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-wrench"></i>
                    <span>Settings</span>
                </a>
                <ul class="sub">
                    <li><?php echo anchor('Dashboard/settings','<i class="fa fa-book fa-fw"></i><span>General Settings</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/appSettings','<i class="fa fa-book fa-fw"></i><span>App Settings</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/setCommission','<i class="fa fa-book fa-fw"></i><span>Set Commission</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/commissionLevels','<i class="fa fa-book fa-fw"></i><span>Set commission Levels</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/callingMode','<i class="fa fa-book fa-fw"></i><span>Calling Mode</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/user_role','<i class="fa fa-book fa-fw"></i><span>User Role</span>')  ?></li>
                    <li><?php echo anchor('Dashboard/add_permission','<i class="fa fa-book fa-fw"></i><span>Add Permission</span>')  ?></li>
                    <li class="sub-menu" id="sub_menu1">
                        <a href="javascript:;" >
                            <i class="fa fa-home"></i>
                            <span>Departments</span>
                        </a>
                        <ul class="sub">
                            <li><?php echo anchor('Dashboard/add_department', '<i class="fa fa-plus-square"></i><span>Add Department</span>') ?></li>
                            <li><?php echo anchor('Dashboard/department_list', '<i class="fa fa-list-ol"></i><span>Department List</span>') ?></li>

                        </ul>
                    </li>
                    <li><?php echo anchor('Dashboard/traffic','<i class="fa fa-book fa-fw"></i><span>Set traffic</span>')  ?></li>
                </ul>
            </li>

            <li class="sub-menu" id="sub_menu1">
                <a href="javascript:;" >
                    <i class="fa fa-wrench"></i>
                    <span>Under Cunstruction</span>
                </a>
                <ul class="sub">
            <li><?php echo anchor('Dashboard/new_map','<i class="fa fa-book fa-fw"></i><span>Sorted zone</span>')  ?></li>
            <li><?php echo anchor('Dashboard/map_view','<i class="fa fa-book fa-fw"></i><span>Convex polygon R-zone</span>')  ?></li>
                </ul>
            </li>
            

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
