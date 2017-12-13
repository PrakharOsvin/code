<body>
  <section id="container" >  
    <!--header start-->
    <header class="header white-bg">
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right"></div>
        <!-- <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div> -->
      </div>
      <!--logo start-->
      <!-- <a href="index.php" class="logo" style="margin-top: 7px;"><img src="<?php echo base_url(); ?>/public/images/forta_logo.png" width="160px;" /></a> -->
      <!--logo end-->
      <div class="nav notify-row" id="top_menu" style="margin-top: 10px;">
        <ul class="nav top-menu"><h4 style="width: 100%;">Welcome to ABER Dashboard</h4></ul>
      </div>                  			
      <div class="top-nav ">
        <!--search & user info start-->
        <ul class="nav pull-right top-menu">
          <!--li><input type="text" class="form-control search" placeholder="Search"></li-->
          <!-- user login dropdown start-->
          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
              <img alt="" src="<?php echo base_url();?>/public/images/avatar1_small.jpg" width="30px;">
              <span class="username">Hello <?php echo $_SESSION['logged_in']['name']; ?></span>
              <!-- <span class="username"><?php //echo $user_getdata['email'];?></span> -->
              <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
              <div class="log-arrow-up"></div>
              <li><a href="<?php echo site_url('Logout') ?>"><i class="fa fa-key1"></i> Log Out</a></li>
              <li><a href="<?php echo site_url('Updatepassword/Updatepassword') ?>"><i class="fa fa-key1"></i>Change Password</a></li>
            </ul>
          </li>
          <!-- user login dropdown end -->
        </ul>
        <!--search & user info end-->
      </div>
    </header>
    
    <!--header end-->

