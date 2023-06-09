<!DOCTYPE html>
<!--
Author: Jessie Conn Ralph M. Sam
Contact: jessieconnralph.sam@msugensan.edu.ph
Capstone Group: Capstonics(2023)
-->
<!-- database connection -->
<?php include "database_connection.php";?>
<!--login validation-->
<?php 
session_start(); // start the session
if(!isset($_SESSION['user_id'])){ // check if the session variable is set
    header('Location: index.php'); // redirect to the login page if it's not set
    exit;
}
?>
<!--C.R acidity -->
<?php
      $query = "select * from acidity ORDER BY cdate DESC LIMIT 1";
      $result = mysqli_query($conn, $query);
      while ($row = mysqli_fetch_assoc($result)) {
         $data_acidity[] = $row['acidity'];
         $data_date[] = $row['cdate'];
      }
   ?>

<!--Current Readings TDS -->
<?php
      $query = "select * from ectemp ORDER BY cdate DESC LIMIT 1";
      $result = mysqli_query($conn, $query);
      while ($row = mysqli_fetch_assoc($result)) {
         $data_TDS[] = $row['EC'];
      }
   ?>

<!-- split datetime-->
<?php
$date_str = $data_date[0]; // assuming $data_date[0] contains the datetime string
$datetime = new DateTime($date_str);
$date = $datetime->format('F-d-Y');
?>

<!-- Notification-->
<?php $sql = "SELECT * FROM notifications WHERE status='0' ORDER BY id DESC";
        $res = mysqli_query($conn, $sql); ?>

<!-- TDS fetch value -->
<?php
  // Get the value of $data_TDS[0]
  $value = $data_TDS[0];

  // Set the class based on the value
  if ($value < 300) {
    $class = 'red-text';
  } else if ($value >= 300 && $value <= 800) {
    $class = 'green-text';
  } else {
    $class = 'red-text';
  }
?>
<?php 
$data = $data_acidity[0]; // assuming $data_acidity[0] contains the value to be displayed
$color = "";
if ($data < 7) {
    $color = "red";
} elseif ($data > 7) {
    $color = "red";
} else {
    $color = "green"; // if value is exactly 7, set color to green
}
?>

<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Hydroponics NFT Decision Support System">
    <meta name="keywords" content="Hydroponics, NFT, Decision Support System">
    <meta name="author" content="Capstonics">
    <title>Dashboard | Hydroponics</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- BEGIN: CSS-->
    <link rel="stylesheet" type="text/css" href="css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="css/animate.css">
    <link rel="stylesheet" type="text/css" href="css/chartist.min.css">
    <link rel="stylesheet" type="text/css" href="css/chartist-plugin-tooltip.css">
    <!-- END: CSS-->
    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="css/materialize.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard-modern.css">
    <link rel="stylesheet" type="text/css" href="css/intro.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- END: Page Level CSS-->
  </head>
  <!-- END: Head-->
  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns"> 
    <!-- BEGIN: Header-->
    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-blue no-shadow">
          <div class="nav-wrapper">
            <ul class="navbar-list right">
              <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
              <li>
                 <a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown" href="#" id="notifications"><i  class="fa fa-bell-o" aria-hidden="true"style="font-size: 20px;"><small class="count"><?php echo mysqli_num_rows($res); ?></small></i></a>
              </li>
              <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="image/profile.jpg" alt="avatar"><i></i></span></a></li>           
            </ul>
            <!-- notifications-dropdown-->
            <ul class="dropdown-content" id="notifications-dropdown">
              <li>
                <h6><strong style="color:black;">NOTIFICATIONS</strong><span class="new badge"><?php echo mysqli_num_rows($res); ?></span></h6>
              </li>
              <li class="divider"></li>
              <?php
              if (mysqli_num_rows($res) > 0) {
                foreach ($res as $item) {
                  $formatted_date = date("F-d-Y h:i A", strtotime($item["cdate"]));
                  ?>
                  <li><?php echo $item["text"]; ?></li>
                  <li style="color:blue;"><?php echo $formatted_date; ?></li>
                  <li class="divider"></li> <!-- Add this line after each notification -->
                  <?php
                }
              }
              ?>
            </ul>
            <!-- profile-dropdown-->
            <ul class="dropdown-content" id="profile-dropdown">
              <li><a class="grey-text text-darken-1" href="logout.php"><i class="material-icons">keyboard_tab</i> Logout</a></li>
            </ul>
          </div>
          <nav class="display-none search-sm">
            <div class="nav-wrapper">
              <form id="navbarForm">
                <div class="input-field search-input-sm">
                  <input class="search-box-sm mb-0" type="search" required="" id="search" placeholder="Explore Materialize" data-search="template-list">
                  <label class="label-icon" for="search"><i class="material-icons search-sm-icon">search</i></label><i class="material-icons search-sm-close">close</i>
                  <ul class="search-list collection search-list-sm display-none"></ul>
                </div>
              </form>
            </div>
          </nav>
        </nav>
      </div>
    </header>
    <!-- END: Header-->
    <ul class="display-none" id="page-search-title">
      <li class="auto-suggestion-title"><a class="collection-item" href="#">
          <h6 class="search-title">PAGES</h6></a></li>
    </ul>
    <ul class="display-none" id="search-not-found">
      <li class="auto-suggestion"><a class="collection-item display-flex align-items-center" href="#"><span class="material-icons">error_outline</span><span class="member-info">No results found.</span></a></li>
    </ul>
    <!-- BEGIN: SideNav-->
    <aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark sidenav-active-normal">
      <div class="brand-sidebar">
        <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="ph.php"><span class="logo-text hide-on-med-and-down"tyle="font-size:25px;">Hydroponics NFT</span></a><a class="navbar-toggler" href="#"></a></h1>
      </div>
      <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
        <li class="active bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">dashboard</i><span class="menu-title" data-i18n="Dashboard">Dashboard</span><span class="badge badge pill blue float-right mr-10">2</span></a>
          <div class="collapsible-body">
            <ul class="collapsible collapsible-sub" data-collapsible="accordion">
              <li class=""><a class="" href="main.php"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Main</span></a>
              <li><a href="water_metrics.php"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Analytics">Water Metrics</span></a>
              </li>
            </ul>
          </div>
        </li>        
        <li class="navigation-header"><a class="navigation-header-text">Applications</a><i class="navigation-header-icon material-icons">more_horiz</i>
        </li>
        
          <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">folder</i><span class="menu-title" data-i18n="Invoice">Report</span></a>
            <div class="collapsible-body">
              <ul class="collapsible collapsible-sub" data-collapsible="accordion">
               <li><a href="system_activity_list.php"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Invoice View">System Activity</span></a>
               </li>
                <li><a href="acidity.php"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Invoice View">pH Level</span></a>
                </li>
                <li><a href="temperature.php"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Invoice Edit">Temperature</span></a>
                </li>
                <li><a href="conductivity.php"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Invoice Add">Conductivity</span></a>
                </li>
                <li><a href="waterflow.php"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Invoice Add">Waterflow</span></a>
                </li>
                <li><a href="waterlevel.php"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Invoice Add">Waterlevel</span></a>
                </li>
              </ul>
            </div>
          </li>
      </ul>
      <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
    </aside>
    <!-- END: SideNav-->
    <!-- BEGIN: Page Main-->
    <div id="main">
      <div class="row">
        <div class="content-wrapper-before gradient-45deg-indigo-blue"></div>
        <div class="col s12">
          <div class="container">
            <div class="section">
   <!-- Search Bar-->
   <div class="row vertical-modern-dashboard">
      <div class="col s12 m8 l8 animate fadeLeft">
         <!-- pH Level -->
         <div class="card">
          <div class="card-move-up waves-effect waves-block waves-light">
            <div class="move-up cyan darken-1">
               <div>
                  <span class="chart-title white-text"> <strong>pH Level</strong></span>
                  <div class="chart-revenue cyan darken-2 white-text">
                     <p class="chart-revenue-total"><?php echo $date; ?></p>
                     <p class="chart-revenue-per"><i class="material-icons">arrow_drop_up</i> <strong>Readings</strong></p>
                  </div>                 
               </div>
               <div class="trending-line-chart-wrapper"><canvas id="revenue-line-chart" height="180"></canvas>
               </div>               
            </div>
         </div>
         </div>
      </div>
      <div class="col s12 l3">
         <div class="card animate fadeRight">
            <div class="card-content">
               <h4 class="card-title mb-0">pH level Conversion</h4>
               <div class="conversion-ration-container mt-8">
                <img src="image/pH-Scale-Diagram.jpg" alt="Description of the image" width="160" height="340">                  
               </div>
               <p class="medium-small center-align">Current Reading</p>
               <h5 class="center-align mb-0 mt-0"><span style="color:<?php echo $color; ?>"><?php echo $data; ?></span></h5> <!-- C.R Acidity -->
            </div>
         </div>
      </div>
   </div>
   <!--Dissolved Solids-->
   <div class="row">
      <div class="col s12 l5">
         <!-- Conversion TDS -->
         <div class="card user-statistics-card animate fadeLeft">
            <div class="card-content" style>
              <div id="dual_x_div" style="width: 300px; height: 300px;"></div>
            </div>
         </div>
      </div>
      <div class="col s12 l4">
         <!-- Reading TDS -->
         <div class="card recent-buyers-card animate fadeUp"style="width: 500px;">
            <div class="card-content">
               <h4 class="card-title mb-0">Total Dissolved Solids Conversion</h4>
               <img src="image/TDS.png" alt="Description of the image" width="450" height="230">
               <h4 class="card-title mb-0">Current Reading: <span class="<?php echo $class; ?>"><?php echo $value; ?> </span> ppm</h4>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- / Intro -->
          </div>
          <div class="content-overlay"></div>
        </div>
      </div>
    </div>
    <!-- END: Page Main-->    
    <!-- BEGIN: Footer-->
    <!-- END: Footer-->
<!------------------------------------------------------------------------------->
    <!-- BEGIN VENDOR JS-->
    <script src="js/vendors.min.js"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="js/chart.min.js"></script>
    <script src="js/chartist.min.js"></script>
    <script src="js/chartist-plugin-tooltip.js"></script>
    <script src="js/chartist-plugin-fill-donut.min.js"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN THEME  JS-->
    <script src="js/plugins.js"></script>
    <script src="js/search.js"></script>
    <script src="js/customizer.js"></script>
    <!-- END THEME  JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="js/dashboard-modern.js"></script>
    <script src="js/intro.js"></script>
    <!-- END PAGE LEVEL JS-->
    <!--APIS-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script async src="https://cse.google.com/cse.js?cx=62e63539c10b34139"></script>
  </body>
</html>

<!-- Script: Notifications-->
<script>
    $(document).ready(function() {
      $("#notifications").on("click", function() {
        $.ajax({
          url: "readNotifications.php",
          success: function(res) {
            console.log(res);
          }
        });
      });
    });
  </script>
<!-- Script: Bargraph-->
<!------------------------------------------------------------------------------>
<script type="text/javascript">
   google.charts.load('current', {'packages':['bar']});
   google.charts.setOnLoadCallback(drawStuff);

   function drawStuff() {
     var data = new google.visualization.arrayToDataTable([
       ['Date&Time', 'TDS', 'Temp'],
       //data config php
       <?php
            $query = "select * from ectemp ORDER BY cdate DESC LIMIT 1";
            $res = mysqli_query($conn, $query);
            while ($data = mysqli_fetch_array($res)) {
                $datetime = date('m-d-Y h:i A', strtotime($data['cdate'])); // add "AM" or "PM"
                $electric_con = $data['EC'];
                $temperature = $data['Temperature'];              
           ?>
           ['<?php echo $datetime;?>',<?php echo $electric_con;?>,<?php echo $temperature;?>],   
           <?php   
            }
       ?>                
     ]);
     var options = {
       width: 300,
       chart: {
         title: 'Temperature & Total Dissolved Solids',
         //subtitle: 'Date'
       },
       bars: 'vertical', // Required for Material Bar Charts.
       series: {
         0: { axis: 'distance' }, // Bind series 0 to an axis named 'distance'.
         1: { axis: 'brightness' } // Bind series 1 to an axis named 'brightness'.
       },
       axes: {
         x: {
           distance: {label: 'parsecs'}, // Bottom x-axis.
           brightness: {side: 'top', label: 'apparent magnitude'} // Top x-axis.
         }
       }
     };
   var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
   chart.draw(data, options);  
 };  
 </script>
<!-- End Script: Bargraph-->
<!------------------------------------------------------------------------------> 
<!-- Script: line chart-->
 <script>
    (function (window, document, $) { 
   //Ph level line chart
   var revenueLineChartCTX = $("#revenue-line-chart");
   //option block
   var revenueLineChartOptions = {
      responsive: true,
      // maintainAspectRatio: false,
      legend: {
         display: false
      },
      hover: {
         mode: "label"
      },
      scales: {
         xAxes: [
            {
               display: true,
               gridLines: {
                  display: false
               },
               ticks: {
                  fontColor: "#fff"
               }
            }
         ],
         yAxes: [
            {
               display: true,
               fontColor: "#fff",
               gridLines: {
                  display: true,
                  color: "rgba(255,255,255,0.3)"
               },
               ticks: {
                  beginAtZero: true,
                  fontColor: "#fff"
               }
            }
         ]
      }
   };
   //data block
   var labels = [];
   var data = [];
  <?php
      $query = "select * from acidity ORDER BY cdate DESC LIMIT 5";
      $result = mysqli_query($conn, $query);
      while ($row = mysqli_fetch_assoc($result)) {
         $labels[] = $labels[] = date('h:i A', strtotime($row['cdate']));
         $data[] = $row['acidity'];
      }
   ?>
   var revenueLineChartData = {
      labels:['Current', <?php echo json_encode($labels[2]); ?>,<?php echo json_encode($labels[4]); ?>, <?php echo json_encode($labels[6]); ?>,<?php echo json_encode($labels[8]); ?>],
      datasets: [
         {
            label: "Acidity",
            data: <?php echo json_encode($data); ?>,
            backgroundColor: "rgba(128, 222, 234, 0.5)",
            borderColor: "#d1faff",
            pointBorderColor: "#d1faff",
            pointBackgroundColor: "#00bcd4",
            pointHighlightFill: "#d1faff",
            pointHoverBackgroundColor: "#d1faff",
            borderWidth: 2,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 4,
            pointRadius: 4
         },
         
      ]
   };
   //config block
   var revenueLineChartConfig = {
      type: "line",
      options: revenueLineChartOptions,
      data: revenueLineChartData
   };
   // Create the chart
   window.onload = function () {
      revenueLineChart = new Chart(revenueLineChartCTX, revenueLineChartConfig);
   };
   // Refresh data every 2 seconds
   setInterval(function() {
      $.ajax({
        url: 'path/to/database_connection.php,path/to/main.php', // replace with the path to your PHP file that returns the updated data
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          revenueLineChart.data.datasets[0].data = data; // update the chart data with the new data
          revenueLineChart.update(); // update the chart
        }
      });
    }, 2000);
})(window, document, jQuery);
</script>
<!-- End Script: line chart-->