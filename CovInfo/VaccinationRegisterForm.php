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

    $userFactory = new UserFactory();
    $connection = PDOSingleton::getInstance();
    $is_page_refreshed = (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=0');
    $searchedId=$_SESSION["searchedId"];
    $searchedPerson = $userFactory->build($searchedId);
    if(isset($_POST["next-appointment-date"]) && isset($_POST["batch-number"]) && isset($_POST["vaccine-id"]) && isset($_POST["vaccination-place"]) && isset($_POST["remarks"])) {
        $dose_count=(int)$_POST['dose-count'];
        $administrator_id = $connection->query("SELECT administrator_id FROM administrator WHERE user_id=".$medical_officer_id)->fetch(PDO:: FETCH_ASSOC);
        $sql = "INSERT INTO vaccination_record (user_id,date,vaccine_id,administrator_id,place,dose,batch_number,next_appointment,remarks) VALUES (:user_id,:registration_date,:vaccine_id,:administrator_id,:place,:dose,:batch_number,:next_appointment,:remarks)";
        $stmt = $connection->prepare($sql);
        if($_POST["next-appointment-date"]===null){
            $nextAppointmentDate=$_POST["next-appointment-date"];
        }else{
            $nextAppointmentDate=null;
        }
        $stmt->execute(array(':user_id' => $searchedId, ':registration_date' => date('Y-m-d'), ':vaccine_id' => $_POST["vaccine-id"], ':administrator_id' => $administrator_id["administrator_id"], ':place' => $_POST["vaccination-place"],':dose' =>++$dose_count,':batch_number' => $_POST["batch-number"],':next_appointment' => $nextAppointmentDate,'remarks' => $_POST["remarks"]));

        try {
            $searchedPerson->getDose();
        } catch (Exception $e) {
        }



        $_SESSION["VRegistration"] = true;
        header("Location:VaccinationRegisterForm.php");
        return;
    }else if(!isset($_SESSION["VRegistration"])){
        /*$sql = "SELECT vaccination_record.dose FROM vaccination_record where user_id=:user_id ORDER BY dose DESC LIMIT 1";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':user_id'=>$searchedId));
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if($result) {
            $dose_count = $result["dose"];
        }else{
            $dose_count=0;
        }*/
        $dose_count=$searchedPerson->getVaccinatedDoseCount();
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
    <title>CovInfo - Vaccination Register Form</title>
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
                        <li class="nav-item"><a class="nav-link" href="profile.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M12 2.5a5.5 5.5 0 00-3.096 10.047 9.005 9.005 0 00-5.9 8.18.75.75 0 001.5.045 7.5 7.5 0 0114.993 0 .75.75 0 101.499-.044 9.005 9.005 0 00-5.9-8.181A5.5 5.5 0 0012 2.5zM8 8a4 4 0 118 0 4 4 0 01-8 0z"></path></svg><?php echo $user->getFirstName()." ".$user->getLastName()?></a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M3 3.25c0-.966.784-1.75 1.75-1.75h5.5a.75.75 0 010 1.5h-5.5a.25.25 0 00-.25.25v17.5c0 .138.112.25.25.25h5.5a.75.75 0 010 1.5h-5.5A1.75 1.75 0 013 20.75V3.25zm16.006 9.5l-3.3 3.484a.75.75 0 001.088 1.032l4.5-4.75a.75.75 0 000-1.032l-4.5-4.75a.75.75 0 00-1.088 1.032l3.3 3.484H10.75a.75.75 0 000 1.5h8.256z"></path></svg>Logout</a></li>
                    </ul>
                <?php } else { ?>
                    <span class="navbar-text">Already have an account?</span>
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                            <a class="nav-link" role="button" aria-expanded="false" href="Login.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M3 3.25c0-.966.784-1.75 1.75-1.75h5.5a.75.75 0 010 1.5h-5.5a.25.25 0 00-.25.25v17.5c0 .138.112.25.25.25h5.5a.75.75 0 010 1.5h-5.5A1.75 1.75 0 013 20.75V3.25zm9.994 9.5l3.3 3.484a.75.75 0 01-1.088 1.032l-4.5-4.75a.75.75 0 010-1.032l4.5-4.75a.75.75 0 011.088 1.032l-3.3 3.484h8.256a.75.75 0 010 1.5h-8.256z"></path></svg>Login</a>
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
            <p class=" fs-3 text-center fw-bold">Vaccination Register Form</p>
        </div>
        <?php if(!isset($_SESSION['VRegistration'])){?>
        <div class="row border-bottom border-primary">
            <p class=" fs-5 text-justify text-primary">Profile Details</p>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="form-label" for="first-name">First Name</label>
                <input class="form-control" type="text" value="<?=$searchedPerson->getFirstName() ?>"  readonly>
            </div>
            <div class="col-sm">
                <label class="form-label" for="middle_name">Middle Name</label>
                <input class="form-control" type="text"  value="<?=$searchedPerson->getMiddleName()?>"  readonly>
            </div>
            <div class="col-sm">
                <label  class="form-label" for="last_name">Last Name</label>
                <input class="form-control" type="text"  value="<?=$searchedPerson->getLastName() ?>" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label class="form-label" for="nic_number">ID Number</label>
                <input class="form-control" type="text"  value="<?=$searchedPerson->getNICNumber() ?>" readonly>
            </div>
            <div class="col-sm-4">
            <label class="form-label" for="age">Age</label>
            <input class="form-control" type="text" value="<?=$searchedPerson->getAge() ?>"   readonly>
            </div>
            <div class="col-sm-4">
                <label class="form-label" for="Sex">Sex</label>
                <input class="form-control" type="text" value="<?=$searchedPerson->getGender() ?>"  readonly>
            </div>
        </div>
    </div>
        <br>
    <div class=" container d-grid gap-3 bg-white p-3">
        <div class="row border-bottom border-primary ">
            <p class="fs-5 text-justify text-primary">Vaccination Details</p>
        </div>
        <form action="VaccinationRegisterForm.php" method="post">
            <div class="row">
                <div class="col-sm">
                    <input type="text" name="dose-count" value="<?=$dose_count?>" hidden>
                    <label class="form-label" for="doc-name">Medical Officer</label>
                    <input class="form-control" type="text"  value="<?= $user->getFirstName()." ".$user->getLastName()?>" readonly>
                </div>
                <div class="col-sm">
                    <label class="form-label" for="vaccine">Type Of Vaccine</label>
                    <select class="form-select" aria-label="Default select example" name="vaccine-id" required>
                        <option  selected value="">Select A Vaccine</option>
                        <?php
                            $vaccines = $connection->query("SELECT vaccine_id,vaccine_name FROM vaccine")->fetchAll(PDO:: FETCH_ASSOC);
                            foreach ($vaccines as $row){
                                echo ("<option value=$row[vaccine_id]>$row[vaccine_name]</option>");
                            }

                        ?>
                    </select>
                </div>
                <div class="col-sm">
                    <label class="form-label" for="batch-number">Batch Number</label>
                    <input class="form-control" type="text" name="batch-number" id="batch-number" placeholder="Enter the Batch Number" required>
                </div>
                <div class="col-sm">
                    <label class="form-label" for="current-dose">Current Dose</label>
                    <input class="form-control" type="text" name="current-dose" id="current-dose" value="<?=$dose_count?>" readonly >
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <label class="form-label" for="next-appointment-date">Next Appointment Date</label>
                    <input class="form-control" type="date" name="next-appointment-date" id="next-appointment-date" min="<?=date('Y-m-d', strtotime(' +1 day'))?>" >
                </div>
                <div class="col-sm">
                    <label class="form-label" for="vaccination-place">Vaccination Place</label>
                    <input class="form-control" type="text" name="vaccination-place" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" id="vaccination-place" placeholder="Enter the Vaccination Place" required>
                </div>
            </div>
            <div class="row">
                <label class="form-label" for="remarks">Remarks:</label>
                <div class="col">
                <textarea class="form-control" id="remarks" name = "remarks" rows="3"></textarea>
                </div>
            </div>
            <br> <br>
            <div class="row">
                <div class="col-sm text-center">
                    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"> <button type="button" class="btn btn-outline-secondary btn-lg" name="cancel">Cancel</button></a>
                </div>
                <div class="col-sm text-center">
                    <button type="submit" class="btn btn-outline-primary btn-lg" name="register" >Register</button>
                </div>
            </div>
        </form>
    </div>
        <?php } else { ?>
            <div class=" container d-grid gap-3 p-4">
                <img class="container" src="images/RegistrationSuccess.png" alt="Registration complete" style="width:150px;">
            </div>
            <div class="alert alert-success text-center">
                <strong>Vaccine Registration success!</strong>
            </div>
                <div class="text-center pb-5">
                    <a href="profile-view.php?id=<?=$_SESSION["searchedId"]?>"><button type="button" class="btn btn-outline-secondary">Back To Profile</button></a>
                </div>
        <?php } ?>
    </div>
    <script src="js/bootstrap.js"></script>
</body>
</html>
