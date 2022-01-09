<?php

    // change //
    require_once ("Classes/classes.php");

    session_start();

    $logged_user = isset($_SESSION["LogIn"]);
    $user = null;
    if($logged_user){
        $user_id = $_SESSION["user_id"];
        $userProxyFactory = new UserProxyFactory();
        $user = $userProxyFactory->build($user_id);
    }




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CovInfo | Home</title>
    <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <!--    <link href="
https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css
" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 --><link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel = "icon" href = "logos/logo_icon.png"
          type = "image/x-icon">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow fixed-top bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="logos/brand.png" alt="Site logo" width="110px" height="auto"> </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="statistic.php">Statistics</a>
                </li>
                <?php
                if($logged_user){
                    if($user->getUserType() == "Authority" || $user->getUserType() == "Medical"){?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="search.php">Search</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="user-create.php">Add New User</a>
                        </li>
                    <?php }

                    if($user->getUserType() == "Admin"){?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="settings.php">Settings</a>
                        </li>
                    <?php }
                }
                ?>

            </ul>
            <?php if ($logged_user) { ?>
                <ul class="nav navbar-nav ">
                    <li class="dropdown">
                        <a href="#" class="nav-link" style="border-bottom: none" role="button" data-bs-toggle="dropdown" id="notify" aria-expanded="false">
                            <?php  if(true) {?>    <!--   have_notifications-->
                            <img src="images/notification.svg" alt="" width="24" height="24">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notify" style="list-style-type: none;">
                            <div class="notif">
                                <li class="notif-header">
                                    <div class="d-flex">
                                        <div class="me-auto" style="font-weight: 500; color: #0C91E6;font-size: 20px">Notifications
                                        </div><div class=""><a href="#" class="btn btn-primary btn-sm rounded-0" style="color: white">Read all</a>
                                        </div></div>
                                </li>
                                <div class="notif-items">
                                    <li class="dropdown-item">
                                        <span class="item-name">You've been Infected!</span>
                                    </li>
                                </div>
                            </div>
                        </ul>
                        <?php } else { ?>
                            <img src="images/bell.svg" alt="" width="24" height="24">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notify" style="list-style-type: none;">
                                <div class="notif">
                                    <li class="notif-header">
                                        <div class="d-flex"><div class="me-auto" style="font-weight: 500; color: #0C91E6;font-size: 20px">Notifications</div></div>
                                    </li>
                                    <div class="notif-items">
                                        <li class="dropdown-item">
                                            <span class="item-name">No new Notifications!</span>
                                        </li>
                                    </div>
                                </div>
                            </ul>
                        <?php } ?>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="profile.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M12 2.5a5.5 5.5 0 00-3.096 10.047 9.005 9.005 0 00-5.9 8.18.75.75 0 001.5.045 7.5 7.5 0 0114.993 0 .75.75 0 101.499-.044 9.005 9.005 0 00-5.9-8.181A5.5 5.5 0 0012 2.5zM8 8a4 4 0 118 0 4 4 0 01-8 0z"></path></svg>&nbsp;<?php echo $user->getFirstName()." ".$user->getLastName() ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M3 3.25c0-.966.784-1.75 1.75-1.75h5.5a.75.75 0 010 1.5h-5.5a.25.25 0 00-.25.25v17.5c0 .138.112.25.25.25h5.5a.75.75 0 010 1.5h-5.5A1.75 1.75 0 013 20.75V3.25zm16.006 9.5l-3.3 3.484a.75.75 0 001.088 1.032l4.5-4.75a.75.75 0 000-1.032l-4.5-4.75a.75.75 0 00-1.088 1.032l3.3 3.484H10.75a.75.75 0 000 1.5h8.256z"></path></svg>&nbsp;Logout</a></li>
                </ul>
            <?php } else { ?>
                <span class="navbar-text">Already have an account?&nbsp;&nbsp;&nbsp;</span>
                <ul class="navbar-nav ">
                    <li class="nav-item">
                        <a class="nav-link" role="button" aria-expanded="false" href="Login.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M3 3.25c0-.966.784-1.75 1.75-1.75h5.5a.75.75 0 010 1.5h-5.5a.25.25 0 00-.25.25v17.5c0 .138.112.25.25.25h5.5a.75.75 0 010 1.5h-5.5A1.75 1.75 0 013 20.75V3.25zm9.994 9.5l3.3 3.484a.75.75 0 01-1.088 1.032l-4.5-4.75a.75.75 0 010-1.032l4.5-4.75a.75.75 0 011.088 1.032l-3.3 3.484h8.256a.75.75 0 010 1.5h-8.256z"></path></svg>&nbsp;Login</a>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
</nav>
<br><br>
<p class="h-4 m-3 p-3 row justify-content-center border border-2 rounded-3 boxy" style="font-weight: bold">Home</p>
<br>
<div class="container justify-content-center">
    <div class="container boxy-blue text-center">
        <div class="h1">This is the Home Page</div>
    </div>
</div>

<!--<script src="
https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js
" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
-->
<script src="js/bootstrap.js"></script>
</body>
</html>