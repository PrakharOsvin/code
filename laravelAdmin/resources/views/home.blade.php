@extends('layouts.app')
@extends('layouts.sidebar')
@section('content')
<section id="main-content">
  <section class="wrapper">
    <!--state overview start-->
    <div class="row state-overview">
      <a href={{ url('/users') }}>
        <div class="col-lg-4 col-sm-6">                          
          <section class="panel">
            <div class="symbol logoblue">
              <i class="fa fa-user"></i>
            </div>
            <div class="value">
              <h1 class="count"><?php echo $users['total']; ?></h1>
              <p>Total</p>
            </div>
          </section>          
        </div>
      </a>
      <a href={{ url('/users') }}>
        <div class="col-lg-4 col-sm-6">      
          <section class="panel">
            <div class="symbol logoblue">
              <i class="fa fa-user"></i>
            </div>
            <div class="value">
              <h1 class="count2"><?php echo $users['localhost']; ?></h1>
              <p>Total Localhost</p>
            </div>
          </section>          
        </div>
      </a>  
      <a href={{ url('/users') }}>
        <div class="col-lg-4 col-sm-6">
          <section class="panel">
            <div class="symbol logoblue">
              <i class="fa fa-user"></i>
            </div>
            <div class="value">
              <h1 class="count3"><?php echo $users['user']; ?></h1>
              <p>Total Users</p>
            </div>
          </section>          
        </div>
      </a>    
    </div> 
    <!--state overview end-->
<!--     <div class="row">
      <div class="col-lg-8">                 
        <div id="piechart" style="width: 100%; height: 500px;">  </div>
      </div>
      <a href="tipsSentHistory.php?userid=<?php //echo $MaxTipSenderMonth['tipFrom']; ?>">
        <div class="col-lg-4">
          <aside >
            <div class="post-info">
              <div class="panel-body flat-carousal" style="background:#3399FF;">
                <h1 style="color:#fff;"><strong style="color:#fff;">popular</strong> <br> Max Tips Sender of this Month </h1>
                <div class="desk yellow">
                      <h3 style="font-weight:bold;">Name-: <?php // echo $MaxTipSenderMonth['username'];  ?></h3>
                      <h3 style="margin-top: 0;font-weight:bold;">Tips Amount Given-: $<?php // echo CointoDoller($MaxTipSenderMonth['amount'],$twiplyRate);  ?></h3>                                      
                </div>                    
              </div> 
            </div>
          </aside> 
        </div>
      </a>
      <a href="tipsReceivedHistory.php?userid=<?php //echo $MaxTipReceiverMonth['tipTo']; ?>">    
        <div class="col-lg-4" style="margin-top:1%">
          <aside >
            <div class="post-info">
              <div class="panel-body flat-carousal" style="background:#3399FF;">
                <h1 style="color:#fff;"><strong style="color:#fff;">popular</strong> <br> Max Tips Receiver of this Month </h1>
                <div class="desk yellow">
                      <h3 style="font-weight:bold;">Name-: <?php // echo $MaxTipReceiverMonth['username'];  ?></h3>
                      <h3 style="margin-top: 0;font-weight:bold;">Tips Amount Received-: $</h3>                    
                </div>                       
              </div>
            </div>
          </aside> 
        </div>
      </a>
      <a href="reviewsReceived.php?userid=<?php// echo $MaxRatingMonth['ratingTo']; ?>">
        <div class="col-lg-4" style="margin-top:1%">
          <aside >
            <div class="post-info">
              <div class="panel-body flat-carousal" style="background:#3399FF;">
                <h1 style="color:#fff;"><strong style="color:#fff;">popular</strong> <br> Max Ratings Received this Month </h1>
                <div class="desk yellow">
                    <h3 style="font-weight:bold;">Name-: <?php //  echo $MaxRatingMonth['username'];  ?></h3>
                    <h3 style="margin-top: 0;font-weight:bold;">Ratings Received-: <?php // echo round($MaxRatingMonth['rating'],2);  ?></h3>
                </div>                      
              </div>
            </div>
          </aside> 
        </div>
      </a>  
    </div>  -->
  </section>
</section>
@endsection
