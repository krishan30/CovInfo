<?php
    require_once "Classes/classes.php";
    session_start();

    if(!isset($_SESSION["user_id"])){
        header("Location:Login.php");
        return;
    }
    if(!isset($_SESSION["searchedId"])){
        header("Location:search.php");
        return;
    }
    $medical_officer_id=$_SESSION["user_id"];
    $userProxyFactory = new UserProxyFactory();
    $user = $userProxyFactory->build($medical_officer_id);

    if($user->getUserType() == "Public"){
        header("Location:index.php");
        return;
    }else{
        $logged_user = true ;
    }

    $searchedId=$_SESSION["searchedId"];
    $connection=PDOSingleton::getInstance();

    if(isset($_POST["new_end_date"])){
        $todayDate=date('Y-m-d');
        $end_date=$_POST["prev_end_date"];
        if($end_date>$todayDate){
            $sql = "UPDATE quarantine_record  set end_date=:end_date  where user_id=:user_id && end_date=:prev_end_date";
            $stmt = $connection->prepare($sql);
            $stmt->execute(array(":user_id"=>$searchedId,":end_date"=>$_POST["new_end_date"],":prev_end_date"=>$end_date));

            $newDate = $_POST["new_end_date"];
            $sender = $userProxyFactory->build($searchedId);
            if($sender->getGender() == "Male"){
                $temp = "sir";
            }else{
                $temp = "madam";
            }
            MailWrapper::sendMail($sender,"CoVID-19 Quarantine Termination Alert",
                "<p>Dear ".$temp." , </p>
<p>
    We are sorry to inform you that your quarantine period is extended until $newDate. 
Please remain indoors and be vigilant on any symptoms.
</p>
<p>
    An automated message via Covinfo CoVID Information System. Do not reply.
</p>");

            $_SESSION['QExtend']=true;
        }
        header("Location:QuarantineExtendForm.php");
        return;
    }else if(!isset($_SESSION["QExtend"])) {
        $sql = "SELECT quarantine_record.start_date,quarantine_record.end_date,quarantine_place.quarantine_place_name FROM quarantine_record join quarantine_place ON quarantine_record.place_id=quarantine_place.quarantine_place_id  WHERE user_id=:user_id ORDER BY end_date DESC LIMIT 1";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$searchedId));
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        $start_date = $result["start_date"];
        $end_date = $result["end_date"];
        $quarantine_place_name = $result["quarantine_place_name"];
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <title>CovInfo | Quarantine Extend Form</title>
    <link rel = "icon" href = "logos/logo_icon.png" type = "image/x-icon">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow fixed-top bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="logos/brand.png" alt="Site logo" width="110px" height="auto"> </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
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
<br><br><br><br>

<div class="container bg-white boxy-blue p-4 mb-4 rounded-3">
    <div class=" container d-grid gap-3 bg-white p-3">
        <div class="row ">
            <p class="fs-3 text-center  fw-bold ">Quarantine Extend Form</p>
        </div>
        <?php if(!isset($_SESSION['QExtend'])){?>
            <div class="row border-bottom border-primary">
                <p class=" fs-5 text-justify text-primary">Latest Quarantined History</p>
            </div>
            <div class="row">
                <div class="col-sm">
                    <label class="form-label" >Date of Quarantine Start</label>
                    <input class="form-control" type="text" value="<?=$result['start_date'] ?>"  readonly>
                </div>
                <div class="col-sm">
                    <label class="form-label" >Date of Quarantine End</label>
                    <input class="form-control" type="text"  value="<?=$result['end_date']?>" readonly>
                </div>
                <div class="col-sm">
                    <label  class="form-label" >Quarantined Center</label>
                    <input class="form-control" type="text"  value="<?=$result['quarantine_place_name'] ?>" readonly>
                </div>
            </div>
            <br>
            <div class=" container d-grid gap-3 bg-white p-3">
                <div class="row border-bottom border-primary ">
                    <p class="fs-5 text-justify text-primary ">Extend Quarantine Period</p>
                </div>
                <form action="QuarantineExtendForm.php" method="post">
                    <div class="row">
                        <div class="col-sm-4">
                            <input type="date" name="prev_end_date" value="<?=$result['end_date']?>" hidden>
                            <label class="form-label" for="new_end_date">Enter new ending date</label>
                            <input type="date" class="form-control col-3" id="new_end_date" name="new_end_date" min="<?=date_format(date_create($result['end_date'])->modify("+1 days"),"Y-m-d")?>" required>
                        </div>
                    </div>
                    <br> <br>
                    <div class="row">
                        <div class="col-sm text-center">
                            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="hiddenLink"> <button type="button" class="btn btn-outline-secondary btn-lg" name="cancel">Cancel</button></a>
                        </div>
                        <div class="col-sm text-center">
                            <button type="submit" class="btn btn-outline-primary btn-lg"  >Extend</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } else { ?>
            <div class=" container d-grid gap-3 p-4">
                <img class="container" src="images/RegistrationSuccess.png" alt="Registration complete" style="width:150px;">
            </div>
            <div class="alert alert-success text-center">
                <strong>Extend success!</strong>
            </div>
            <div class="text-center pb-5">
                <a href="profile-view.php?id=<?=$_SESSION["searchedId"]?>" class="hiddenLink"><button type="button" class="btn btn-outline-secondary">Back To Profile</button></a>
            </div>
        <?php } ?>
    </div>
    <script src="js/bootstrap.js"></script>
</body>
</html>

