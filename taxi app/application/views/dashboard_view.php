<?php
// echo "<pre>"; print_r($wallet_balance->wallet_balance);die;
//$count_active = $dashboard_details[0]['count_active'];
//$count_inactive = $dashboard_details[0]['count_inactive'];
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row state-overview">
            <a href="<?php echo site_url('Users/users_list'); ?>">      
                <div class="col-lg-4 col-sm-4">                          
                    <section class="panel bggrey">
                        <div class="symbol logoblue">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="value">
                            <?php
                            foreach ($query1 as $dev) {
                                ?>   
                                <h1 class="count"><?php print_r($dev->countid); ?></h1>
                            <?php } ?>

                            <p>Total Members</p>
                        </div>
                    </section>          
                </div>
            </a>
            <?php
            $clients = 0;
            $Drivers = 0;
            foreach ($query as $row) {

                if ($row->user_type == "0") {
                    $clients = $row->countiiii;
                }

                if ($row->user_type == "2") {
                    $Drivers = $row->countiiii;
                }
            }
            ?>
            <a href="<?php echo site_url('Users/client_list'); ?>">      
                <div class="col-lg-4 col-sm-4">                          
                    <section class="panel bggrey">
                        <div class="symbol logoblue">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="value">
                            <h1 class="count"><?php echo $clients; ?></h1>
                            <p>Total Clients</p>
                        </div>
                    </section>          
                </div>
            </a> 
            <a href="<?php echo site_url('Users/driver_list'); ?>">      
                <div class="col-lg-4 col-sm-4">                          
                    <section class="panel bggrey">
                        <div class="symbol logoblue">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="value">
                            <h1 class="count"><?php echo $Drivers; ?></h1>
                            <p>Total Drivers</p>
                        </div>
                    </section>          
                </div>
            </a> 
    <!--        <a href="<?php echo site_url('Dashboard') ?>">      
              <div class="col-lg-3 col-sm-">                          
                <section class="panel bggrey">
                  <div class="symbol logoblue">
                    <i class="fa fa-users"></i>
                  </div>
                  <div class="value">
                    <h1 class="count"> <?php echo $admin; ?></h1>
                    <p>Total Admin</p>
                  </div>
                </section>          
              </div>
            </a>  -->

            <a href="<?php echo site_url('Dashboard/payments'); ?>">      
                <div class="col-lg-4 col-sm-4">                          
                    <section class="panel bggrey">
                        <div class="symbol logoblue">
                            <i class="fa fa-money"></i>
                        </div>
                        <div class="value">
                            <h1 class="count"><?php if ($wallet_balance->wallet_balance>0) {
                                echo round($wallet_balance->wallet_balance);
                            } else {
                                echo "0";
                            }
                              ?></h1>
                            <p>Total Earning</p>
                        </div>
                    </section>          
                </div>
            </a> 
            <a href="<?php echo site_url('Dashboard/payments'); ?>">      
                <div class="col-lg-4 col-sm-4">                          
                    <section class="panel bggrey">
                        <div class="symbol logoblue">
                            <i class="fa fa-money"></i>
                        </div>
                        <div class="value">
                            <h1 class="count"><?php if ($wallet_balance->wallet_balance<0) {
                                echo round($wallet_balance->wallet_balance);
                            } else {
                                echo "0";
                            }?></h1>
                            <p>Money to pay</p>
                        </div>
                    </section>          
                </div>
            </a> 
            <a href="<?php echo site_url('Dashboard'); ?>">      
                <div class="col-lg-4 col-sm-4">                          
                    <section class="panel bggrey">
                        <div class="symbol logoblue">
                            <i class="fa fa-ban"></i>
                        </div>
                        <div class="value">
                            <h1 class="count">
                                <?php echo count($AvailableDrivers); ?>
                            </h1>
                            <p>Available Drivers</p>
                        </div>
                    </section>          
                </div>
            </a> 
        </div>          

        <div class="row">

            <div class="col-lg-8">                 
                <div id="piechart" style="width: 100%; height: 500px;"></div>
            </div>
            <div class="col-lg-4">
              <!--latest product info start-->
              <!-- <section class="panel post-wrap pro-box">
                  <aside>
                      <div class="post-info">
                          <span class="arrow-pro right"></span>
                          <div class="panel-body">
                              <h1><strong>popular</strong> <br> Brand of this week</h1>
                              <div class="desk yellow">
                                  <h3>Dimond Ring</h3>
                                  <p>Lorem ipsum dolor set amet lorem ipsum dolor set amet ipsum dolor set amet</p>
                              </div>
                              <div class="post-btn">
                                  <a href="javascript:;">
                                      <i class="fa fa-chevron-circle-left"></i>
                                  </a>
                                  <a href="javascript:;">
                                      <i class="fa fa-chevron-circle-right"></i>
                                  </a>
                              </div>
                          </div>
                      </div>
                  </aside>
                  <aside class="post-highlight yellow v-align">
                      <div class="panel-body text-center">
                          <div class="pro-thumb">
                              <img src="img/ring.jpg" alt="">
                          </div>
                      </div>
                  </aside>
              </section> -->
              <!--latest product info end-->
          </div>
        </div>
      </div>
    </section>
</section>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php
// $clients = 10;
// $Drivers = 20;
// $admin = 70;
$ch_data = "['Total Clients'," . $clients . "],";
$ch_data = $ch_data . "['Total Drivers'," . $Drivers . "],";
$ch_data = rtrim($ch_data, ',');
?>
<script type="text/javascript">
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Total Clients', 'Total Drivers'],
<?php echo $ch_data; ?>
        ]);
        var options = {
            title: 'Users',
            is3D: true,
            slices: {
                0: {color: '#4CA6EA'},
                1: {color: '#D8D8D8'},
                2: {color: '#3D404D'}
            }
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>
<script src="<?php echo base_url(); ?>/public/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/public/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?php echo base_url(); ?>/public/js/owl.carousel.js" ></script>
<script src="<?php echo base_url(); ?>/public/js/jquery.customSelect.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/respond.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>/public/js/jquery.dcjqaccordion.2.7.js"></script>

<!--common script for all pages-->
<script src="<?php echo base_url(); ?>/public/js/common-scripts.js"></script>

<!--script for this page-->
<script src="<?php echo base_url(); ?>/public/js/sparkline-chart.js"></script>
<script src="<?php echo base_url(); ?>/public/js/easy-pie-chart.js"></script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.8/js/dataTables.bootstrap.min.js " type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#hidden-table-info').DataTable();
    });
</script>