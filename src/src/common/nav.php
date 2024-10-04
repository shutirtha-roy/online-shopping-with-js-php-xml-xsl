<?php
    /*
        Name: Shutirtha Roy
        Student ID: 105008711
        Course: COS80021 Web Application Development
        Function: This file is used to show the project navigations. Here 2 logics are
        used to seperate the pages shown when the user is logged in and when the 
        user is logged out.
    */

    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        $loggedin = true;
    } else {
        $loggedin = false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ShipOnline - Start Your Shipment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/CSS/pre-request.shipment.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">ShipOnline System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                    <?php
                        if(!$loggedin) {
                            echo '
                                <li class="nav-item">
                                    <a class="nav-link" href="../home/shiponline.php">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../account/login.php" tabindex="-1">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../account/register.php" tabindex="-1">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../admin/admin.php" tabindex="-1">Admin</a>
                                </li>';
                        }

                        if($loggedin) {
                            echo '<li class="nav-item">
                                        <a class="nav-link" aria-current="page" href="pre-request-shipment.php">Dashboard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" aria-current="page" href="request-shipment.php">Request Shipments</a>
                                    </li>';
                       } 
                    ?>
                    
                </ul>

                <?php
                    if($loggedin) {
                        echo '<span class="navbar-text me-3">
                            Welcome, '. $name . 
                            '</span>
                            <a class="btn btn-outline-danger" href="../account/logout.php">Logout</a>';
                    }                   
                ?>
                
                
            </div>
        </div>
    </nav>
</body>