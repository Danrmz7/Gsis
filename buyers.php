<?php
include ('_assets/classes/header.inc.php');
$compradores->setData();
$process = $compradores->process();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admin | Dashboard</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Star Admin2 </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="_assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="_assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="_assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="_assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="_assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="_assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="_assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="_assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="_assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="assets/js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="_assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="_assets/images/favicon.png" />

    <!-- Custom styles for this template-->
    <link href="_assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>
<body>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include ('_assets/includes/sidebar.inc.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <?php include ('_assets/includes/navbar.inc.php'); ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                <?php
                    print $process;
                ?>
                </div>
            </div>
        </div>
   

 
    </div>

    <!-- plugins:js -->
    <script src="_assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="_assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="_assets/vendors/chart.js/chart.umd.js"></script>
    <script src="_assets/vendors/progressbar.js/progressbar.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="_assets/js/off-canvas.js"></script>
    <script src="_assets/js/template.js"></script>
    <script src="_assets/js/settings.js"></script>
    <script src="_assets/js/hoverable-collapse.js"></script>
    <script src="_assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="_assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="_assets/js/dashboard.js"></script>
    <!-- <script src="_assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
</body>
</html>