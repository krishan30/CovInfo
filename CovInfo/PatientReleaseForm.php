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
    $userFactory = new UserFactory();
    $userProxyFactory = new UserProxyFactory();
    $user = $userProxyFactory->build($medical_officer_id);

    if($user->getUserType() == "Public"){
        header("Location:index.php");
        return;
    }else{
        $logged_user = true ;
    }

    $is_page_refreshed = (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=0');
    $searchedId=$_SESSION["searchedId"];
    $searchedPerson = $userFactory->build($searchedId);
    if(!$is_page_refreshed && is_a($searchedPerson->getUserState(),'Infected')){
        unset($_SESSION["PRelease"]);
    }

$connection=PDOSingleton::getInstance();

    if(isset($_POST["end-date"])){
        $todayDate=date('Y-m-d');
        $sql = "UPDATE infection_record  set release_date=:release_date,release_medical_officer_id=:release_medical_officer_id  where user_id=:user_id && admitted_date=:admitted_date";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$searchedId,":release_date"=>$todayDate,":admitted_date"=>$_POST["admitted_date"],":release_medical_officer_id"=>$medical_officer_id));
        $sql = "INSERT INTO  quarantine_record (user_id, start_date, end_date, administrator_id,place_id) VALUES (:user_id,:start_date,:end_date,:administrator_id,:place_id)";
        $stmt = $connection->prepare($sql);
        $administrator_id = $connection->query("SELECT administrator_id FROM administrator WHERE user_id=".$medical_officer_id)->fetch(PDO:: FETCH_ASSOC);
        $stmt->execute(array(':user_id' =>$searchedId, ':start_date' =>$todayDate, ':end_date' => $_POST["end-date"], ':administrator_id' => $administrator_id["administrator_id"],':place_id'=>1));
        try {
            $searchedPerson->startQuarantine();
        } catch (Exception $e) {

        }


        $updateQuery = "UPDATE daily_report SET recovered = recovered + 1 WHERE date=CURRENT_DATE()";
        $updatestmt = $connection->prepare( $updateQuery);
        $updatestmt->execute();
        $updateQuery="UPDATE report SET total_recovered = total_recovered + 1 WHERE report_id=1";
        $updatestmt = $connection->prepare($updateQuery);
        $updatestmt->execute();

        $sender = $userProxyFactory->build($searchedId);
        if($sender->getGender() == "Male"){
            $temp = "sir";
        }else{
            $temp = "madam";
        }
        MailWrapper::sendMail($sender,"CoVID-19 Patient Release Alert",
            "<p>Dear ".$temp." , </p>
<p>
    We are pleased to inform you that according to latest PCR reports you are no longer infected with CoVID-19. Please be advised that you will now be placed under quarantine. Kindly continue taking necessary steps to isolate yourself and avoid engaging in physically strenuous tasks.
</p>
<p>
    An automated message via Covinfo CoVID Information System. Do not reply.
</p>");
        $_SESSION['PRelease']=true;
        header("Location:PatientReleaseForm.php");
        return;
    }else if(!isset($_SESSION["PRelease"])) {
        $sql = "SELECT infection_record.admitted_date,infection_record.remarks,medical_centre.name,administrator.user_id FROM infection_record join medical_centre ON medical_centre.medical_centre_id=infection_record.medical_centre_id JOIN administrator ON infection_record.admission_administrator_id=administrator.administrator_id  WHERE infection_record.user_id=:user_id && infection_record.release_date IS NULL LIMIT 1";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(":user_id"=>$searchedId));
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        $admitted_date = $result["admitted_date"];
        $remark=$result["remarks"];
        $medicalCentreName=$result["name"];
        $admissionOfficer=$userProxyFactory->build($result['user_id']);
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
    <title>CovInfo | Patient Release Form</title>
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
                    <a class="nav-link active" aria-current="page" href="statistic.php">Statistics</a>
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
                                        <div class="d-flex">
                                            <div class="me-auto" style="font-weight: 500; color: #0C91E6;font-size: 20px">Notifications
                                            </div><div class=""><a href="notificationPage.php" class="btn btn-primary btn-sm rounded-0" style="color: white">Read all</a>
                                            </div></div>
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
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="user-create.php">Add New User</a>
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
            <p class=" fs-3 text-center fw-bold">Patient Release Form</p>
        </div>
        <?php if(!isset($_SESSION['PRelease'])){?>
            <div class="row border-bottom border-primary">
                <p class=" fs-5 text-justify text-primary">Latest Infection History</p>
            </div>
            <div class="row">
                <div class="col-sm">
                    <label class="form-label" >Admitted Date</label>
                    <input class="form-control" type="text" value="<?=$admitted_date ?>"  readonly>
                </div>
                <div class="col-sm">
                    <label  class="form-label" >Medical Center</label>
                    <input class="form-control" type="text"  value="<?= $medicalCentreName ?>" readonly>
                </div>
                <div class="col-sm">
                    <label  class="form-label" >Admission Officer</label>
                    <input class="form-control" type="text"  value="<?=$admissionOfficer->getFullName() ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <label class="form-label" >Remark</label>
                    <input class="form-control" type="text"  value="<?=$remark?>" readonly>
                </div>
            </div>
            <br>
            <div class=" container d-grid gap-3 bg-white p-3">
                <div class="row border-bottom border-primary ">
                    <p class="fs-5 text-justify text-primary">Quarantine Registration</p>
                </div>
                <form action="PatientReleaseForm.php" method="post">
                    <div class="row">
                        <div class="col-sm">
                            <input type="date" name="admitted_date" value="<?=$admitted_date?>" hidden>
                            <label for="start-date" class="form-label">Quarantine Start Date</label>
                            <input type="date" id="start-date" name="start-date" class="form-control" value="<?= date('Y-m-d', time());?>" readonly>
                        </div>
                        <div class="col-sm">
                            <label for="end-date" class="form-label">Quarantine End Date</label>
                            <input type="date" min="<?=date('Y-m-d', strtotime(' +7 day'));?>" id="end-date" name="end-date" class="form-control" required>
                        </div>
                        <div class="col-sm">
                            <label for="place" class="form-label">Quarantine Location</label>
                            <input class="form-control" id="place"  name="place-of-quarantine" value="Home" readonly>
                        </div>
                    </div>
                    <br> <br>
                    <div class="row">
                        <div class="col-sm text-center">
                            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"> <button type="button" class="btn btn-outline-secondary btn-lg" name="cancel">Cancel</button></a>
                        </div>
                        <div class="col-sm text-center">
                            <button type="submit" class="btn btn-outline-primary btn-lg"  >Release</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } else { ?>
            <div class=" container d-grid gap-3 p-4">
                <img class="container" src="images/RegistrationSuccess.png" alt="Registration complete" style="width:150px;">
            </div>
            <div class="alert alert-success text-center">
                <strong>Release success!</strong>
            </div>
            <div class="text-center pb-5">
                <a href="profile-view.php?id=<?=$_SESSION["searchedId"]?>"><button type="button" class="btn btn-outline-secondary">Back To Profile</button></a>
            </div>
        <?php } ?>
    </div>
    <script src="js/bootstrap.js"></script>
</body>
</html>