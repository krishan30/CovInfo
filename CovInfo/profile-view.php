<?php
require_once ("Classes/PDO.php");
require_once ("Classes/classes.php");

session_start();
$today = date("Y-m-d");
$logged_user = false;
$userBuilder = new UserBuilder();
$authority = null;
$user = null;

if (isset($_SESSION["user_id"])){
    $authority = $userBuilder->buildUser($_SESSION["user_id"]);
    $logged_user = true;
    if($authority->getUserType() == "Public"){
        header("Location:index.php");
        return;
    }
}else{
    header("Location:Login.php");
    return;
}


if (isset($_GET["id"])){
    $user_id = $_GET["id"];
    $user = $userBuilder->buildUser($_GET["id"]);
    $_SESSION["searchedId"] = $user_id;
}else{
    header("Location:search.php");
    return;
}

/*
  should get from session variable after connecting with logging page
*/


$name = $user->getFirstName()." ".$user->getMiddleName()." ".$user->getLastName();
$nic = $user->getNICNumber();
$age = $user->getAge();
$dob = $user->getDOBString();
$sex = $user->getGender();
$bloodType = $user->getBloodType();
$district = $user->getDistrict();
$status = $user->getStatus(); //Infected, Quarantined, Healthy
$vaccinated = $user->getVaccinationStatus();   //Partial, None
$phone = $user->getPhoneNumber();
$address = $user->getAddress();
$email = $user->getEmailAddress();

$vaccineRecords = $connection->query("SELECT vaccination_record.dose,vaccine.vaccine_name,vaccination_record.place,vaccination_record.date,vaccination_record.batch_number,vaccination_record.next_appoinment,vaccination_record.remarks 
                                                    FROM vaccination_record,vaccine 
                                                    WHERE vaccination_record.user_id = $user_id AND vaccination_record.vaccine_id = vaccine.vaccine_id 
                                                    ORDER BY vaccination_record.dose");

$infectionRecords = $connection->query("SELECT infection_record.test_report_id,infection_record.admitted_date,infection_record.release_date,medical_centre.name,infection_record.remarks 
                                                    FROM infection_record,medical_centre 
                                                    WHERE infection_record.user_id = $user_id AND medical_centre.medical_centre_id = infection_record.medical_centre_id 
                                                    ORDER BY infection_record.admitted_date");

$quarantineRecords = $connection->query("SELECT quarantine_record.start_date,quarantine_record.end_date,quarantine_place.quarantine_place_name,quarantine_record.remarks 
                                                    FROM quarantine_record,quarantine_place 
                                                    WHERE quarantine_record.user_id = $user_id AND quarantine_place.quarantine_place_id = quarantine_record.place_id 
                                                    ORDER BY quarantine_record.start_date");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CovInfo-My Profile</title>
    <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 --><link rel="stylesheet" href="css\bootstrap.css">
    <link rel="stylesheet" href="styles\styles.css">
    <link rel="stylesheet" href="styles\profile-view.css">
    <link rel = "icon" href = "logos/logo_icon.png"
          type = "image/x-icon">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow fixed-top bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="logos\brand.png" alt="Site logo" width="110px" height="auto"> </a>
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
                    if($authority->getUserType() != "Public"){?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="search.php">Search</a>
                        </li>
                    <?php }
                }
                ?>


            </ul>
            <ul class="nav navbar-nav ">
                <li class="nav-item active"><a class="nav-link active" href="#" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M12 2.5a5.5 5.5 0 00-3.096 10.047 9.005 9.005 0 00-5.9 8.18.75.75 0 001.5.045 7.5 7.5 0 0114.993 0 .75.75 0 101.499-.044 9.005 9.005 0 00-5.9-8.181A5.5 5.5 0 0012 2.5zM8 8a4 4 0 118 0 4 4 0 01-8 0z"></path></svg><?php echo $authority->getFirstName()." ".$authority->getLastName()?></a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M3 3.25c0-.966.784-1.75 1.75-1.75h5.5a.75.75 0 010 1.5h-5.5a.25.25 0 00-.25.25v17.5c0 .138.112.25.25.25h5.5a.75.75 0 010 1.5h-5.5A1.75 1.75 0 013 20.75V3.25zm16.006 9.5l-3.3 3.484a.75.75 0 001.088 1.032l4.5-4.75a.75.75 0 000-1.032l-4.5-4.75a.75.75 0 00-1.088 1.032l3.3 3.484H10.75a.75.75 0 000 1.5h8.256z"></path></svg>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<br><br>
<p class="h-4 m-3 p-3 row justify-content-center border border-2 rounded-3 boxy" style="font-weight: bold">User Profile</p>
<br>
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
                                            else{
                                                echo '<h3 class="h4 mb-0" style="color: #27bf19; text-align: center">Healthy</h3>';}
                                            ?>
                                            <br>
                                            <?php
                                            if ($vaccinated=="None"){
                                                echo '<h3 class="h6 mb-0" style="color: #bf1919; text-align: center">Not Vaccinated!</h3>';}
                                            elseif ($vaccinated=="Partial"){
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
                                        <td><?php echo $row["next_appoinment"]?></td>
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
                                    <th scope="col">Remarks</th>
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
                                        <td><?php echo $row["remarks"]?></td>
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





<div class="card p-3 py-3 mt-3 card-1 text-center">
    <h4></h4>


    <div class="p-3 card-2">
        <div class="p-3 card-child">
            <div class="d-flex flex-row align-items-center"> <span class="circle"> <i class="fa fa-home"> <img src="images\edit.png" width="80%"></i>  </span>
                <div class="d-flex flex-column ms-3">
                    <h6 class="fw-bold">Edit Profile</h6> <span>change the profile details</span>
                </div>
            </div>
        </div>

        <?php if($status!="Infected"){?>
            <a href="NewPatientReport.php" class="hiddenLink">
                <div class="p-3 card-child mt-4">
                    <div class="d-flex flex-row align-items-center"> <span class="circle-2"> <i class="fa fa-bank"> <img src="images\addPatient.png" width="80%"> </i> </span>
                        <div class="d-flex flex-column ms-3">
                                <h6 class="fw-bold">Add as a Covid Patient</h6> <span>To mark the profile owner as a covid patient</span>
                        </div>
                    </div>
                </div>
            </a>
        <?php } ?>

        <?php if($status =="Infected" && $authority->getUserType() == "Medical"){?>
            <div class="p-3 card-child mt-4">
                <div class="d-flex flex-row align-items-center"> <span class="circle-4"> <i class="fa fa-bank"> <img src="images\releaseP.png" width="80%"> </i> </span>
                    <div class="d-flex flex-column ms-3">
                        <h6 class="fw-bold">Release Patient</h6> <span>To release the patient from the medical centre</span>
                    </div>
                </div>
            </div>

        <?php } ?>


        <?php if($status =="Healthy" && $authority->getUserType() == "Authority"){?>
            <div class="p-3 card-child mt-4">
                <div class="d-flex flex-row align-items-center"> <span class="circle-3"> <i class="fa fa-bank"> <img src="images\addQ.png" width="80%"> </i> </span>
                    <div class="d-flex flex-column ms-3">
                        <h6 class="fw-bold">Add to Quarantine</h6> <span>To mark the profile owner as a quarantining person</span>
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php if($status =="Quarantined" && $authority->getUserType() == "Authority"){?>
            <div class="p-3 card-child mt-4">
                <div class="d-flex flex-row align-items-center"> <span class="circle-3"> <i class="fa fa-bank"> <img src="images\extendQ.png" width="80%"> </i> </span>
                    <div class="d-flex flex-column ms-3">
                        <h6 class="fw-bold">Extend the Quarantine</h6> <span>Extend the quarantining period of the person</span>
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php if($vaccinated !="Completed" && $authority->getUserType() == "Medical"){?>
            <div class="p-3 card-child mt-4">
                <div class="d-flex flex-row align-items-center"> <span class="circle-5"> <i class="fa fa-bank"> <img src="images\vaccine.png" width="80%"> </i> </span>
                    <div class="d-flex flex-column ms-3">
                        <h6 class="fw-bold">Add vaccination record</h6> <span>To add record about vaccination dose for user</span>
                    </div>
                </div>
            </div>

        <?php } ?>


    </div>
</div>



<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
-->
<script src="js\bootstrap.js"></script>
</body>
</html>