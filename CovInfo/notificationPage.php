<?php

    require_once "Classes/classes.php";
    session_start();

    if(!isset($_SESSION["user_id"])){
        header("Location:Login.php");
        return;
    }
    $logged_user = true ;
    $officer_id=$_SESSION["user_id"];
    $userProxyFactory = new UserProxyFactory();
    $user = $userProxyFactory->build($officer_id);

    if(is_a($user->getAccountState(),'PreUser')){
        try {
            //$user->activateAccount();
            header("Location:editmyprofile.php");
            return;
        } catch (Exception $e) {
        }
    }

    $connection = PDOSingleton::getInstance();
    if(isset($_POST["NotificationID"])){
        $notification=NotificationFactory::buildNotification((int)$_POST["NotificationID"]);
        $notification->markNotificationAsViewed();
        header("Location:notificationPage.php");
        return;
    }
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



?>



<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
        <link rel="stylesheet" href="styles/styles.css">
        <link rel="stylesheet" href="styles/notifications.css">
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
                        <a class="nav-link active" href="index.php">Home</a>
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

<div class="container bg-white justify-content-center boxy-blue">
    <div class="row justify-content-center p-3 ">
        <h3 class="text-center fw-bolder ">Notifications </h3>
    </div>
    <div class="row">
        <div class="d-flex align-items-start">
            <div class="col-4 border-right-1">
                <div class="nav flex-column nav-tabs me-3 d-grid gap-2 col float-left" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                   <?php
                   if(!$notifications){
                       echo ("<div class='text-muted'>No New Notifications</div>");
                   }
                   foreach ($notifications as $index => $notification) {
                        $notificationID=$notification->getNotificationRecordID();
                        $notificationMessage = $notification->getNotificationTypeHeading();
                        $notificationReceivedTime = $notification->getReceivedTime();
                        $notificationReceivedDate = $notification->getReceivedDate();
                        $link=str_replace(' ', '-', $notificationMessage);
                        echo(" <button  class='nav-link' id='v-pills-home-tab' data-bs-toggle='tab' data-bs-target='#$link' type='button' role='tab' aria-controls='v-pills-home' aria-selected='true' value='$notificationID'><i class='bi bi-chat-fill'></i> &nbsp; 
                        <div >
                         $notificationMessage
                        </div> 
                        <div style='display: inline-flex'>
                         <div style='margin-right:7vw'>$notificationReceivedDate</div>    <div style='margin-left:7vw'>$notificationReceivedTime</div>     
                        </div>
                        </button>");
                    } ?>
                   </div>
            </div>
            <div class="col">
                <div class="tab-content bg-light " id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="default" role="tabpanel" aria-labelledby="v-pills-home-tab" style="align-items: center; text-align:center;">
                        <small class="text-muted">Select a notification to view.</small>
                    </div>

                    <div class="tab-pane fade" id="Covid-19-Quarantine-Alert" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <p>
                            We are sorry to inform you that you are now placed under quarantine.
                                The relevant authorities are notified, and the Public Health Inspector of your area will get in touch with you soon.
                            <br><br>
                            Please check your mail for further details.
                        </p>

                    </div>
                    <div class="tab-pane fade" id="Covid-19-Infection-Alert" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <p>
                            We regret to inform you that according to recent PCR test results, you are now infected with CoVID-19.
                            Strictly isolate yourself from your family members and observe maximum vigilance on your condition.
                            Use a pulse oximetry if available and request immediate medical assistance if the Oxygen saturation drops below 94%. Also, be attentive to any shortness of breath, difficulty breathing, and heaviness in the chest. If any of these symptoms are present and worsening, visit the nearest government hospital as soon as possible.
                            <br><br>
                            Please check your mail for further details.
                        </p>

                    </div>
                    <div class="tab-pane fade" id="Covid-19-Quarantine Extend-Alert" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                        <p>
                            We are sorry to inform you that as per the latest PCR test results, your quarantine period is extended.
                            Please remain indoors and be vigilant on any symptoms.
                            <br><br>
                            Please check your mail for further details.
                        </p>

                    </div>
                    <div class="tab-pane fade" id="Covid-19-Quarantine-Ended-Alert" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <p>
                            We are pleased to inform you that your quarantine period is now over. Please continue to follow government health regulations while you recommence your daily activities.
                            Thank you for your cooperation.
                            <br><br>
                            Please check your mail for further details.
                        </p>

                    </div>
                    <div class="tab-pane fade" id="Covid-19-Patient-Release-Alert" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <p>
                            We are pleased to inform you that according to latest PCR reports you are no longer infected with CoVID-19. Please continue to take necessary health precautions and do not engage in physically strenuous tasks.
                            <br><br>
                            Please check your mail for further details.
                        </p>

                    </div>
                    <div class="tab-pane fade" id="Covid-19-Vaccination-Registration-Complete" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                        <p>
                            Vaccination registration is successfully completed. Please do not forget to bring your vaccination record if you have been vaccinated before. Be on time at the scheduled date and follow all health protocols at the vaccination center. Your cooperation is much appreciated.
                            <br><br>
                            Please check your mail for further details.
                        </p>

                    </div>

                </div>
                <form action="notificationPage.php" method="post" >
                    <input id="NI" type="text"  name="NotificationID" hidden>
                    <div class="col-sm text-end">
                        <button style="display:none" type="submit" class="btn btn-outline-primary " id="MR" name="register" >Mark As Read</button>
                    </div>
                </form>

            </div>
        </div>


    </div>
    <div class="text-center pt-4 pb-3">
        <a href="index.php" class="hiddenLink"> <button type="button" class="btn btn-outline-secondary " name="Home">Back To Home</button></a>
    </div>
    
</div>

    <script >$("button").click(function() {
            var NotificationID = $(this).val();
            var Input=document.getElementById("NI");
            var Button=document.getElementById("MR");

            if(Button.style.display==='none' && NotificationID){
                Button.style.display='inline';
            }
            if(NotificationID){
                Input.value=NotificationID;
            }
        });


    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>


