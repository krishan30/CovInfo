<?php
    require_once ("Classes/classes.php");

    session_start();
    $today = date("Y-m-d");
    $logged_user = false;
    if (isset($_SESSION["user_id"])){
        $user_id = $_SESSION["user_id"] ;
        $logged_user = true;
    }else{
        header("Location:Login.php");
        return;
    }

    $connection = PDOSingleton::getInstance();

    $error1 = false;
    $error2 = false;

    /*
      should get from session variable after connecting with logging page
    */

    $userFactory = new UserFactory();
    $user = $userFactory->build($user_id);

    if(isset($_SESSION["ch"])){
        if($user->getPassword() == md5($_SESSION["ch-cPassword"])){
            if($_SESSION["ch-conPassword"] == $_SESSION["ch-nPassword"]){
                $user->setPassword(md5($_SESSION["ch-nPassword"]),$user->getUserID());
            }else{
                $error1 = true;
            }
        }else{
            $error2 = true;
        }
        unset($_SESSION["ch"]);
    }

    if(isset($_POST["passwordChange"])){
        $_SESSION["ch-cPassword"] = $_POST["currentPassword"];
        $_SESSION["ch-conPassword"] = $_POST["confirmPassword"];
        $_SESSION["ch-nPassword"] = $_POST["newPassword"];
        $_SESSION["ch"] = true;
        header("Location:profile.php");
        return;
    }

    $name = $user->getFirstName()." ".$user->getMiddleName()." ".$user->getLastName();
    $nic = $user->getNICNumber();
    $accountId = $user->getAccountID();
    $age = $user->getAge();
    $dob = $user->getDOBString();
    $sex = $user->getGender();
    $bloodType = $user->getBloodType();
    $district = $user->getDistrict();
    $status = get_class($user->getUserState()); //Infected, Quarantined, Healthy
    $vaccinated = get_class($user->getVaccinationState());   //Partial, None
    $phone = $user->getPhoneNumber();
    $address = $user->getAddress();
    $email = $user->getEmailAddress();

    $vaccineRecords = $connection->query("SELECT vaccination_record.dose,vaccine.vaccine_name,vaccination_record.place,vaccination_record.date,vaccination_record.batch_number,vaccination_record.next_appointment,vaccination_record.remarks 
                                                    FROM vaccination_record,vaccine 
                                                    WHERE vaccination_record.user_id = $user_id AND vaccination_record.vaccine_id = vaccine.vaccine_id 
                                                    ORDER BY vaccination_record.dose");

    $infectionRecords = $connection->query("SELECT infection_record.admitted_date,infection_record.release_date,medical_centre.name,infection_record.remarks 
                                                    FROM infection_record,medical_centre 
                                                    WHERE infection_record.user_id = $user_id AND medical_centre.medical_centre_id = infection_record.medical_centre_id 
                                                    ORDER BY infection_record.admitted_date");

    $quarantineRecords = $connection->query("SELECT quarantine_record.start_date,quarantine_record.end_date,quarantine_place.quarantine_place_name 
                                                    FROM quarantine_record,quarantine_place 
                                                    WHERE quarantine_record.user_id = $user_id AND quarantine_place.quarantine_place_id = quarantine_record.place_id 
                                                    ORDER BY quarantine_record.start_date");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CovInfo | My Profile</title>
    <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <link rel="stylesheet" href="styles/profile-view.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel = "icon" href = "logos/logo_icon.png"
          type = "image/x-icon">
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
        </div>
    </div>
</nav>
<br><br>
<p class="h-4 m-3 p-3 row justify-content-center border border-2 rounded-3 boxy" style="font-weight: bold">User Profile</p>
<br>

    <div class="toast-container" style="position: absolute; top: 100px; right: 10px;">
        <?php if($error1){?>
        <div class="toast fade show boxy-red">
            <div class="toast-header" style="background-color: rgba(182,3,35,0.41);color: white">
                <strong class="me-auto"><i class="bi-globe"></i> CovInfo</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" style="color: red">
                Passwords don't match! Try again
            </div>
        </div>
        <?php } ?>
        <?php if($error2){?>
            <div class="toast fade show boxy-red">
                <div class="toast-header" style="background-color: rgba(182,3,35,0.41);color: white">
                    <strong class="me-auto"><i class="bi-globe"></i> CovInfo</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body" style="color: red">
                    Current password is invalid! Try again
                </div>
            </div>
        <?php } ?>
    </div>

<div class="container justify-content-center">
    <div class="container boxy-blue p-3 m-4">
        <ul class="nav nav-tabs" id="myTab">
            <li class="nav-item">
                <a href="#home" class="nav-link active" data-bs-toggle="tab">General</a>
            </li>
            <li class="nav-item">
                <a href="#profile" class="nav-link" data-bs-toggle="tab">Contact</a>
            </li>
            <li class="nav-item">
                <a href="#vaccination" class="nav-link" data-bs-toggle="tab">Vaccination</a>
            </li>
            <li class="nav-item">
                <a href="#medical" class="nav-link" data-bs-toggle="tab">Medical</a>
            </li>
        </ul>

        <div class="tab-content p-2">
            <div class="tab-pane fade show active" id="home">
                <div class="container p-3">
                    <div class="row justify-content-center">
                        <div class="col-md-7 col-lg-4 mb-5 mb-lg-0">
                            <div class="row">
                                <div class="card border-0 shadow">
                                    <img src=
                                         <?php
                                            if($user->getGender() == "Male"){
                                                echo "images\User-big.png";
                                            }else{
                                                echo "images\User-female.png";
                                            }
                                         ?>

                                         alt="...">
                                    <div class="card-body p-1-9 p-xl-5">
                                        <div class="mb-4">
                                            <?php
                                            if ($status=="Infected"){
                                                echo '<h3 class="h4 mb-0" style="color: #bf1919; text-align: center">Infected!</h3>';}
                                            elseif ($status=="Quarantined"){
                                                echo '<h3 class="h4 mb-0" style="color: #bf8b19; text-align: center">Quarantined</h3>';}
                                            elseif ($status=="Deceased"){
                                                echo '<h3 class="h4 mb-0" style="color: #bf8b19; text-align: center">Deceased</h3>';}
                                            else{
                                                echo '<h3 class="h4 mb-0" style="color: #27bf19; text-align: center">Healthy</h3>';}
                                            ?>
                                            <br>
                                            <?php
                                            if ($vaccinated=="NotVaccinated"){
                                                echo '<h3 class="h6 mb-0" style="color: #bf1919; text-align: center">Not Vaccinated!</h3>';}
                                            elseif ($vaccinated=="PartiallyVaccinated"){
                                                echo '<h3 class="h5 mb-0" style="color: #bf8b19; text-align: center">Partially Vaccinated</h3>';}
                                            else{
                                                echo '<h3 class="h5 mb-0" style="color: #27bf19; text-align: center">Fully Vaccinated</h3>';}
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="h3 p-3 px-5" style="border-bottom: solid #0d6efd">General Information</div>
                            <br>
                            <div class="row p-3">
                                <div class="col-5 text-end" style="font-weight: bold">
                                    Name
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $name ?>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-5 text-end" style="font-weight: bold">
                                    NIC number
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $nic ?>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-5 text-end" style="font-weight: bold">
                                    Account ID
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $accountId ?>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-5 text-end" style="font-weight: bold">
                                    Age
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $age ?>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-5 text-end" style="font-weight: bold">
                                    Date of Birth
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $dob ?>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-5 text-end" style="font-weight: bold">
                                    Sex
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $sex ?>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-5 text-end" style="font-weight: bold">
                                    District of Residence
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $district ?>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-5 text-end" style="font-weight: bold">
                                    Blood Type
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $bloodType ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile">
                <div class="container p-3">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="h3 p-3 px-5" style="border-bottom: solid #0d6efd">Contact Information</div>
                            <br>
                            <div class="row p-3">
                                <div class="col-4 text-end" style="font-weight: bold">
                                    Phone Number
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $phone ?>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-4 text-end" style="font-weight: bold">
                                    Address
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $address ?>
                                </div>
                            </div>
                            <div class="row p-3">
                                <div class="col-4 text-end" style="font-weight: bold">
                                    Email
                                </div>:
                                <div class="col-6 text-start">
                                    <?php echo $email ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="vaccination">
                <div class="container p-3">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="h3 p-3 px-5" style="border-bottom: solid #0d6efd">Covid-19 Vaccination Details</div>
                            <br>
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Dose #</th>
                                    <th scope="col">Name of the Vaccine</th>
                                    <th scope="col">Place of Vaccination</th>
                                    <th scope="col">Date of Vaccination</th>
                                    <th scope="col">Next Appointment</th>
                                    <th scope="col">Batch Number</th>
                                    <th scope="col">Remarks</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                    while ($row = $vaccineRecords->fetch(PDO::FETCH_ASSOC)){ ?>
                                        <tr>
                                            <th scope="row"><?php echo $row["dose"]?></th>
                                            <td><?php echo $row["vaccine_name"]?></td>
                                            <td><?php echo $row["place"]?></td>
                                            <td><?php echo $row["date"]?></td>
                                            <td><?php echo $row["next_appointment"]?></td>
                                            <td><?php echo $row["batch_number"]?></td>
                                            <td><?php echo $row["remarks"]?></td>
                                        </tr>
                                <?php
                                    }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="medical">
                <div class="container p-3">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="h3 p-3 px-5" style="border-bottom: solid #0d6efd">Covid-19 Infection History</div>
                            <br>
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <!--<th scope="col">Test Report ID</th> -->
                                    <th scope="col">Date of Admittance</th>
                                    <th scope="col">Date of Release</th>
                                    <th scope="col">Medical Center</th>
                                    <th scope="col">Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                while ($row = $infectionRecords->fetch(PDO::FETCH_ASSOC)){?>
                                    <?php
                                        $i += 1;
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $i ?></th>
                                       <!-- <td><?php /*echo $row["test_report_id"]*/?></td> -->
                                        <td><?php echo $row["admitted_date"]?></td>
                                        <td><?php echo $row["release_date"]?></td>
                                        <td><?php echo $row["name"]?></td>
                                        <td><?php echo $row["remarks"]?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <br>
                            <br>
                            <div class="h3 p-3 px-5" style="border-bottom: solid #0d6efd">Covid-19 Quarantined History</div>
                            <br>
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date of Quarantine Start</th>
                                    <th scope="col">Date of Quarantine End</th>
                                    <th scope="col">Quarantined Center</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                while ($row = $quarantineRecords->fetch(PDO::FETCH_ASSOC)){?>
                                <?php
                                $i += 1;
                                ?>
                                <tr>
                                    <th scope="row"><?php echo $i ?></th>
                                    <td><?php echo $row["start_date"]?></td>
                                    <td><?php echo $row["end_date"]?></td>
                                    <td><?php echo $row["quarantine_place_name"]?></td>
                                </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <!--
                            <br>
                            <br>
                            <div class="h3 p-3 px-5" style="border-bottom: solid #0d6efd">Other Medical Conditions</div>
                            <br>
                            <ul>
                                <li>Chronic health diseases</li>
                                <li>Diabetics</li>
                            </ul>
                            <br>
                            <br>
                            <div class="h5 border-bottom border-primary p-2 px-5">Allergies</div>
                            <ul>
                                <li>Grass and tree pollen</li>
                                <li>Aspirin</li>
                            </ul>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container justify-content-center">
    <div class="container px-3 mx-4 ">
        <a href="editmyprofile.php" class="hiddenLink">
            <div class="p-3 card-child text-center btn-outline-primary d-inline-flex col-3 rounded-3">
                <div class="d-flex flex-row align-items-center"> <span class="circle"> <i class="fa fa-home"> <img src="images\edit.png" width="80%"></i>  </span>
                    <div class="d-flex flex-column ms-3">
                        <h5 class="fw-bold">Edit Profile</h5>
                    </div>
                </div>
            </div>
        </a>
        <button type="button" class="btn btn-outline-primary d-inline-flex px-3 mx-4 text-center card-child col-3 p-3 border-3 rounded-5" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <div class="d-flex flex-row align-items-center"> <span class="circle"> <i class="fa fa-home"> <img src="images\edit.png" width="80%"></i>  </span>
                <div class="d-flex flex-column ms-3">
                    <h5 class="fw-bold">Update Password</h5>
                </div>
            </div>
        </button>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="currentPassword" name="currentPassword"  required placeholder="Password">
                                <label for="currentPassword"> Current password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="newPassword" required name="newPassword" placeholder="Password">
                                <label for="newPassword">New password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="confirmPassword" required name="confirmPassword" placeholder="Password">
                                <label for="confirmPassword">Re-enter new password</label>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="passwordChange" class="btn btn-primary">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<br>
<br>

<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
-->
<script src="js\bootstrap.js"></script>
</body>
</html>