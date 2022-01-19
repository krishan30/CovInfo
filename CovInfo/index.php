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
    <link rel="stylesheet" href="styles/home.css">
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
                        <a  href="#" class="nav-link" style="border-bottom: none" role="button" data-bs-toggle="dropdown" id="notify" aria-expanded="false">
                            <?php  if($user->isNewNotificationsAvailable()) {?>    <!--   have_notifications-->
                            <img src="images/notification.png" alt="" width="24" height="24">
                            <span class="badge bg-primary"><?= $user->getNewNotificationCount() ?></span>
                            </button>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notify" style="list-style-type: none;overflow-y:scroll;height:50vh;">
                            <div class="notif" style="width:31vw">
                                <li class="notif-header">
                                    <div class="d-flex">
                                        <div class="me-auto" style="font-weight: 500; color: #0C91E6;font-size: 20px">Notifications
                                        </div><div class=""><a href="notificationPage.php" class="btn btn-primary btn-sm rounded-0" style="color: white">Read all</a>
                                        </div></div>
                                </li>
                                <div class="notif-items" >
                                    <?php
                                    $connection = PDOSingleton::getInstance();
                                    $query = "SELECT notification_id  FROM notification WHERE receiver_id=:receiver_id && read_status_id=1 ORDER BY sent_date_time DESC ";
                                    $stmt = $connection->prepare($query);
                                    $stmt->execute(array(":receiver_id" => $user->getUserId()));
                                    $notificationsIDS = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    $notifications = array();
                                    $index = 0;
                                    foreach ($notificationsIDS as $notificationID) {
                                        $notification = NotificationFactory::buildNotification((int)$notificationID["notification_id"]);
                                        $notifications[$index] = $notification;
                                        $index++;
                                    }
                                    foreach ($notifications as $index => $notification) {
                                        $notificationMessage = $notification->getNotificationTypeHeading();
                                        $notificationReceivedTime = $notification->getReceivedTime();
                                        $notificationReceivedDate = $notification->getReceivedDate();
                                        echo("<li style='cursor: pointer' class='dropdown-item'>
                                        <span  class='item-name fw-bold'> $notificationMessage</span>
                                        <br>
                                        <span class='fw-lighter '>$notificationReceivedDate</span>
                                        <span style='padding-left:60%' class='fw-lighter  '>$notificationReceivedTime</span>
                                        </li>");
                                    }
                                    ?>

                                </div>
                            </div>
                        </ul>
                        <?php } else { ?>
                            <img src="images/notification.svg" alt="" width="24" height="24">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notify" style="list-style-type: none;">
                                <div class="notif">
                                    <li class="notif-header">
                                        <div class="d-flex"><div class="me-auto" style="font-weight: 500; color: #0C91E6;font-size: 20px">Notifications</div></div>
                                    </li>
                                    <div class="notif-items">
                                        <li class="dropdown-item">
                                            <span class="item-name">No New Notifications!</span>
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

<br>

<div class="container-fluid div1">
    <div class="h1 display-1">
        <br><br><br><br>
        &nbsp;Welcome to CovInfo <br>
        <div class="small h-50" style="color: #0d6efd; font-size: 1.7rem">&emsp;National COVID Information System for Rapid Patient Management
        </div>
        <br>
        <br>
    </div>
</div>

<div class="container-flex p-0 m-0 div2 boxy-blue">
    <div class="row justify-content-center">
        <div class="col-3 p-3">
            <a href="#option1" class="text-decoration-none">
                <div class="card text-start boxy-blue pt-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <img src="images/card1.png" class="p-2" style="width: auto;height: 150px;" alt="">
                        </div>
                        <div class="col">
                            <h5 class="card-title" style="font-weight: bold">If you think you are sick?</h5>
                            <img src="images/right-arrow.png" class="py-2" style="width: auto;height: 40px;" alt="">
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-3 p-3">
            <a href="#option2" class="text-decoration-none">
                <div class="card text-start boxy-blue pt-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <img src="images/card2.png" class="p-2" style="width: auto;height: 150px;" alt="">
                        </div>
                        <div class="col">
                            <h5 class="card-title" style="font-weight: bold">How to protect yourself & others?</h5>
                            <img src="images/right-arrow.png" class="py-2" style="width: auto;height: 40px;" alt="">
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-3 p-3">
            <a href="#option3" class="text-decoration-none">
                <div class="card text-start boxy-blue pt-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <img src="images/card3.png" class="p-2" style="width: auto;height: 150px;" alt="">
                        </div>
                        <div class="col">
                            <h5 class="card-title" style="font-weight: bold">Information for general public</h5>
                            <img src="images/right-arrow.png" class="py-2" style="width: auto;height: 40px;" alt="">
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-3 p-3">
            <a href="#option4" class="text-decoration-none">
                <div class="card text-start boxy-blue pt-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <img src="images/card4.png" class="p-2" style="width: auto;height: 150px;" alt="">
                        </div>
                        <div class="col">
                            <h5 class="card-title" style="font-weight: bold">FAQ</h5>
                            <img src="images/right-arrow.png" class="py-2" style="width: auto;height: 40px;" alt="">
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-5 mx-5" id="option1">
    <br><br><br>
    <div class="display-4 text-center" style="font-weight: bold; color: #0e3c80">IF YOU THINK YOU ARE SICK ?</div>
    <br><br>
    <p class="text-center h5"style="color: gray">These are the most common symptoms of COVID-19. Some people become infected but don’t develop any symptoms and don't feel unwell.</p>
    <br><br>
    <div class="row justify-content-center">
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms1.jpg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Congestion or runny nose</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms2.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Cough</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms3.svg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Diarrhea</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms4.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Fever or chills</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms5.svg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Headache</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms6.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Muscle or body aches</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms7.svg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Nausea or vomiting</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms8.svg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Fatigue</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms9.svg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">New loss of taste or smell</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms10.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Shortness of breath or difficulty breathing</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/symptoms11.svg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Sore throat</p>
        </div>
    </div>
    <p class="" style="font-weight: bold">Having these symptoms doesn’t mean you have COVID-19. However, since these are common symptoms of the COVID-19 infection, for the sake of those close to you, please follow the measures mentioned here <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal" style="text-decoration: none;">
            <img src="images/right-arrow.png" alt="" style="width: 1em;height: auto;"></button>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content px-5 pb-3 div4">
                <div class="modal-header border-bottom-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ol style="color: #0e3c80" class="h5">
                        <li class="my-3">
                            Do not leave home for any reason other than seeking medical advice.
                        </li>
                        <li class="my-3">
                            If possible, use a separate room for yourself.
                        </li>
                        <li class="my-3">
                            Always keep a distance of at least one meter between yourself and others at home.
                        </li>
                        <li class="my-3">
                            If possible, use a separate washroom. If this is not possible, always wash the taps, doorknobs etc. after use with soap and water.
                        </li>
                        <li class="my-3">
                            Do not encourage visitors to the house.
                        </li>
                        <li class="my-3">
                            Frequently wash your hands for at least 20 seconds with soap and water. Ask your family members to do the same.
                        </li>
                        <li class="my-3">
                            Use separate cups, plates, towels, bedspreads etc. Wash them separately with soap and water.
                        </li>
                        <li class="my-3">
                            Cover your mouth and nose with a disposable tissue or the inside of your elbow when coughing and sneezing. Safely dispose of the used tissues.
                        </li>
                        <li class="my-3">
                            Do not reuse face masks and gloves etc. Dispose them in a garbage bin with a lid.
                        </li>
                        <li class="my-3">
                            If you have returned from abroad or associated someone who has COVID-19 or someone suspected of being infected within the past 14 days, immediately report to the Public Health Inspector (PHI) or the Medical Officer of Health (MOH) of your area.
                        </li>
                        <li class="my-3">
                            Call 1999 hotline for medical and other advice regarding Covid-19.
                        </li>
                        <li class="my-3">
                            Call 1990 for ambulance facilities in case of an emergency.
                        </li>
                    </ol>
                    <br>
                    <div class="text-center h5" style="font-weight: normal">
                        <div class="display-6" style="font-size: 2em;color: #0e3c80">A person with above symptoms,</div><br>
                        <strong>who has</strong><br><br>
                        returned to Sri Lanka from <strong>ANY COUNTRY</strong> within the last 14 days<br>
                        <br>
                        <strong>or</strong><br><br>
                        having close-contact with a confirmed or suspected COVID-19 patient during the last 14 days prior to onset of symptoms<br>
                        <br><strong>or</strong><br><br>
                        with severe acute pneumonia regardless of travel or contact history as decided by the treating Consultant
                        <br><br>
                        <strong>are advised to seek medical advice from the nearest Government Hospital immediately.</strong><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br>

<div class="container-fluid div3">
    <div class="row">
        <div class="col-7 offset-1 display-4">
            <br>
            &emsp;For further information,<br>&emsp;Contact <span style="font-weight: 500;color: #0e3c80">“Suwasariya”</span><br>
            <div class="small h-50" style="color: #ffffff; font-size: 2rem">&emsp;&emsp;&nbsp;24 HOUR TRILINGUAL<br>&nbsp;&emsp;&emsp;HEALTHCARE HOTLINE</div>
            <br>
        </div>
        <div class="col-4 display-4">
            <br><br>
            <a href="tel:1999" class="btn btn-primary p-3 rounded-pill" style="text-decoration: none;background-color: #0e3c80">
                <div class="row">
                    <div class="col">&nbsp;&nbsp;&nbsp;<img src="images/call.png" style="height: 3em" alt=""></div>
                    <div class="col"><div class="display-6">&nbsp;1999&nbsp;&nbsp;&nbsp;</div></div>
                </div></a>
        </div>
    </div>
</div>
<br>
<div class="container-fluid px-5 mx-5" id="option2">
    <br><br><br><br>
    <div class="display-4 text-center" style="font-weight: 400; color: rgba(32,3,131,0.53)">How to protect yourself & others?</div>
    <br><br>
    <p class="text-center h5" style="color: #626060">You can reduce your chances of being infected or spreading COVID-19 by taking these simple precautions.</p>
    <br><br>
    <div class="row justify-content-center">
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/protect1.jpg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Get vaccinated for as soon as you are eligible</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/protect2.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Frequently wash your hands with soap and water or use a hand sanitizer with at least 60% alcohol</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/protect3.jpg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Wear a face mask when in public to prevent the spread of the virus</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/protect4.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Cough or sneeze into a tissue or bent elbow. Throw the tissue in the trash</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/protect5.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Stay at least 6 feet away from others</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/protect6.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">If you feel sick, stay home and contact your health care provider</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/protect7.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Do not touch your face without washing your hands first</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/protect8.jpg" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Avoid crowds and poorly ventilated spaces. Avoid indoor spaces that do not offer fresh air</p>
        </div>
        <div class="card border-0 text-center col-2 m-4 my-3" style="background-color: #fafafa">
            <img src="images/protect9.png" class="card-img-top mx-auto" style="width: 80%;height: auto;border-radius: 50%" alt="">
            <p class="card-body">Clean and disinfect frequently touched objects and surfaces</p>
        </div>
    </div>
    <br>
    <div class="row justify-content-center" style="font-weight:bold;color: #0e3c80">
        <div class="col-3">
            Prevention at Workplace <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal2" style="text-decoration: none;">
                <img src="images/right-arrow.png" alt="" style="width: 1em;height: auto;"></button>
        </div>
        <div class="col-3">Prevention at Market Place <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal3" style="text-decoration: none;">
                <img src="images/right-arrow.png" alt="" style="width: 1em;height: auto;"></button></li>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal2Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content px-5 pb-3 div5-1">
                <div class="modal-header">
                    <h5 class="modal-title text-center" style="color: #0a53be" id="exampleModal2Label">MEASURES TO BE TAKEN AT WORKPLACE TO PREVENT COVID-19</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="display-5" style="font-size: 1.3em;font-weight: 400">
                    We spend more time at work places. So we need to care more about how to protect places that work against covid-19
                    <br><br>
                    Follow these instructions to keep your work safe.
                    </div>
                    <br>
                    <ol class="m-1 h5" style="color: #052452">
                        <li class="m-1">If anyone shows light symptoms such as fever, cough or cold, <br>advise him not to go to work until it is fully healed. If possible,
                            <br>encourage me to work from home.</li>
                        <br>
                        <li class="m-1">Clean the equipment used by more than one person like<br>door handles, phone receivers, table surface, stapler.</li>
                        <br>
                        <li class="m-1">Wash your hands well often using soap. Avoid<br>touching your face, mouth, nose and eyes. Covid-19
                            <br>virus is usually spread through contact.</li>
                        <br>
                        <li class="m-1">Avoid increasing the crowd as much as possible. <br>Snooze meetings and follow modern communication
                            <br>methods such as video conferencing and group calling</li>
                    </ol>
                    <br><br>
                    <div class="display-5" style="font-size: 1.3em;font-weight: 500">
                    If you reduce the chance to spread the corona virus at your workplace, you may also reduce the chance of immune to your beloved family members.
                    </div><br><br>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModal3Label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content px-5 pb-3 div5-2">
                <div class="modal-header">
                    <h5 class="modal-title text-center" style="color: #0a53be" id="exampleModal3Label">MEASURES TO BE TAKEN AT MARKET PLACE TO PREVENT COVID-19</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <br>
                    <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6">
                    <ol class="m-1 h5" style="color: #052452">
                        <li class="m-1">Maintain at least one meter distance between you and other customers when you standing a queue.
                        </li>
                        <br>
                        <li class="m-1">Wash your hands with soap or hand wash before entering the shop. It is scientifically proven that washing hands can successfully reduce the spread of virus.
                        </li>
                        <br>
                        <li class="m-1">Avoid touching nose and eyes for no reason. Thus the virus spreads.
                        </li>
                        <br>
                        <li class="m-1">If you have symptoms like fever, cold, cough, please do not go to the stores.
                        </li>
                        <br>
                        <li class="m-1">Buy what you need only. This is your national responsibility..
                        </li>
                        <br>
                        <li class="m-1">Give priority to old people while standing in queue at stores. They are more likely to be seriously sick by Covid-19 virus than others.
                        </li>
                    </ol>
                    <br>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid px-5 mx-5" id="option3">
    <br><br><br><br>
    <div class="container mx-5">
        <div class="row justify-content-center">
            <div class="col-7 div6 boxy-blue rounded-3">
                <div>
                    <br>
                    <div class="display-5 text-center" style="font-weight: 400;color: #052452">Information for General Public</div>
                    <hr style="background-color: #000000;width:90%;height: 3px;margin: auto">
                    <ul>
                        <br><b>
                            <li>
                                Delivery of medicine from hospital clinics<br>
                                <a href="Downloads/delivery-medicine-hospital-clinics.pdf" style="text-decoration: none">Find out more <img src="images/right-arrow.png" class="" style="width: 1em;height: auto;" alt=""></a>
                            </li>
                            <br>
                            <li>
                                Revised Timeline for Public Activities<br>
                                <a href="Downloads/revised-timeline.pdf" style="text-decoration: none">Find out more <img src="images/right-arrow.png" class="" style="width: 1em;height: auto;" alt=""></a>
                            </li>
                            <br>
                            <li>
                                Home Quarantine - Sinhala<br>
                                <a href="Downloads/home-quarantine.pdf" style="text-decoration: none">Find out more <img src="images/right-arrow.png" class="" style="width: 1em;height: auto;" alt=""></a>
                            </li>
                            <br>
                        <li>
                            Other Important <Contactsbr></Contactsbr>
                            <ul class="list-unstyled ms-5">
                                <li class="my-1">Epidemiology Unit:&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&nbsp;<a href="tel:1999" style="text-decoration: none;color: #0e3c80">+94011 269 5112</a></li>
                                <li class="my-1">Quarantine Unit:&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<a href="tel:1999" style="text-decoration: none;color: #0e3c80">+94011 211 2705</a></li>
                                <li class="my-1">Disaster Management Unit:&emsp;<a href="tel:1999" style="text-decoration: none;color: #0e3c80">+94011 307 1073</a></li>
                            </ul>
                        </li></b>
                        <br>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid div7" id="option4">
    <br><br><br>
    <div class="display-3 ms-5 ps-5"><b>F</b>requently <b>A</b>sked <b>Q</b>uestions<hr style="width: 90%;height: 3px;color: #0652f6"></div>
    <div class="container p-5">
        <div class="row justify-content-center">
            <div class="col-9">
                <div class="accordion-flush px-5" id="accordionFAQ">
                    <div class="accordion-item mx-5 my-3 boxy-blue">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                What is a coronavirus?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Coronaviruses are a large family of viruses which may cause illness in animals or humans. In humans, several coronaviruses are known to cause respiratory infections ranging from the common cold to more severe diseases such as Middle East Respiratory Syndrome (MERS) and Severe Acute Respiratory Syndrome (SARS). The most recently discovered coronavirus causes coronavirus disease COVID-19.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mx-5 my-3 boxy-blue">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How does COVID-19 spread?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                People can catch COVID-19 from others who have the virus. The disease can spread from person to person through small droplets from the nose or mouth which are spread when a person with COVID-19 coughs or exhales. These droplets land on objects and surfaces around the person. Other people then catch COVID-19 by touching these objects or surfaces, then touching their eyes, nose or mouth. People can also catch COVID-19 if they breathe in droplets from a person with COVID-19 who coughs out or exhales droplets. This is why it is important to stay more than 1 meter (3 feet) away from a person who is sick. WHO is assessing ongoing research on the ways COVID-19 is spread and will continue to share updated findings.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mx-5 my-3 boxy-blue">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                What are symptoms of COVID-19?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                The most common symptoms of COVID-19 are fever, tiredness, and dry cough. Some patients may have aches and pains, nasal congestion, runny nose, sore throat or diarrhea. These symptoms are usually mild and begin gradually. Some people become infected but don’t develop any symptoms and don't feel unwell. Most people (about 80%) recover from the disease without needing special treatment. Around 1 out of every 6 people who gets COVID-19 becomes seriously ill and develops difficulty breathing. Older people, and those with underlying medical problems like high blood pressure, heart problems or diabetes, are more likely to develop serious illness. People with fever, cough and difficulty breathing should seek medical attention.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mx-5 my-3 boxy-blue">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Can the virus that causes COVID-19 be transmitted through the air?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                Studies to date suggest that the virus that causes COVID-19 is mainly transmitted through contact with respiratory droplets rather than through the air. See previous answer on “How does COVID-19 spread?”
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mx-5 my-3 boxy-blue">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Can COVID-19 be caught from a person who has no symptoms?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                The main way the disease spreads is through respiratory droplets expelled by someone who is coughing. The risk of catching COVID-19 from someone with no symptoms at all is very low. However, many people with COVID-19 experience only mild symptoms. This is particularly true at the early stages of the disease. It is therefore possible to catch COVID-19 from someone who has, for example, just a mild cough and does not feel ill. WHO is assessing ongoing research on the period of transmission of COVID-19 and will continue to share updated findings.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mx-5 my-3 boxy-blue">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Can I catch COVID-19 from the feces of someone with the disease?
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                The risk of catching COVID-19 from the feces of an infected person appears to below. While initial investigations suggest the virus may be present in feces in some cases, spread through this route is not a main feature of the outbreak. WHO is assessing ongoing research on the ways COVID-19 is spread and will continue to share new findings. Because this is a risk, however, it is another reason to clean hands regularly, after using the bathroom and before eating.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mx-5 my-3 boxy-blue">
                        <h2 class="accordion-header" id="headingSeven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                What can I do to protect myself and prevent the spread of disease?
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                <strong>Protection measures for everyone</strong><br>Stay aware of the latest information on the COVID-19 outbreak, available on the WHO website and through your national and local public health authority. Many countries around the world have seen cases of COVID-19 and several have seen outbreaks. Authorities in China and some other countries have succeeded in slowing or stopping their outbreaks. However, the situation is unpredictable so check regularly for the latest news.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mx-5 my-3 boxy-blue">
                        <h2 class="accordion-header" id="headingEight">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                How likely am I to catch COVID-19?
                            </button>
                        </h2>
                        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                The risk depends on where you are - and more specifically, whether there is a COVID-19 outbreak unfolding there.For most people in most locations the risk of catching COVID-19 is still low. However, there are now places around the world (cities or areas) where the disease is spreading. For people living in, or visiting, these areas the risk of catching COVID19 is higher. Governments and health authorities are taking vigorous action every time a new case of COVID-19 is identified. Be sure to comply with any local restrictions on travel, movement or large gatherings. Cooperating with disease control efforts will reduce your risk of catching or spreading COVID-19. COVID-19 outbreaks can be contained and transmission stopped, as has been shown in China and some other countries. Unfortunately, new outbreaks can emerge rapidly. It’s important to be aware of the situation where you are or intend to go. WHO publishes daily updates on the COVID-19 situation worldwide.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="footer m-0 mt-5 p-5 text-center">© 2021 Digitally Crafted by Sanitizers</div>
<!--<script src="
https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js
" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
-->
<script src="js/bootstrap.js"></script>
</body>
</html>