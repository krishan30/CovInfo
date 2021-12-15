<?php
    require_once "Classes/Pdo.php";
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
    $userBuilder = new UserBuilder();
    $user = $userBuilder->buildUser($medical_officer_id);

    if($user->getUserType() == "Public"){
        header("Location:index.php");
    }else{
        $logged_user = true ;
    }

    if(isset($_POST["cancel"])){
        header("Location:profile-view.php?id=".$_SESSION["searchedId"]);
        return;
    }

    if(isset($_POST["medical_centre_id"]) && isset($_POST["register"])){
        unset($_SESSION["PRegistration"]);
        $sql = "INSERT INTO infection_record (user_id,medical_officer_id,admitted_date,medical_centre_id,remarks) VALUES (:user_id,:medical_officer_id,:admitted_date,:medical_centre_id,:remarks)";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array('user_id'=>$_POST['user_id'],'medical_officer_id'=>$medical_officer_id,'admitted_date'=>$_POST['admission-date'],'medical_centre_id'=>$_POST["medical_centre_id"],'remarks'=>$_POST["remarks"]));
        $sql = "UPDATE user set status_id=3  where user_id=:user_id";
        $stmt=$connection->prepare($sql);
        $stmt->execute(array(':user_id' => $_POST['user_id']));


        $today = date("Y-m-d");
        $casesCount = $connection->query("SELECT new_cases FROM daily_report WHERE date = '$today'");
        $count = 0;
        while ($row = $casesCount->fetch(PDO::FETCH_ASSOC)){
            $count = $row["new_cases"];
        }
        $count += 1;
        $updatesql = "UPDATE daily_report SET new_cases = $count where date = '$today'";
        $updatestmt = $connection->prepare($updatesql);
        $updatestmt->execute();

        $casesCount = $connection->query("SELECT total_cases FROM report WHERE report_id = 1");
        $count = 0;
        while ($row = $casesCount->fetch(PDO::FETCH_ASSOC)){
            $count = $row["total_cases"];
        }
        $count += 1;
        $updatesql = "UPDATE report SET total_cases = $count where report_id = 1";
        $updatestmt = $connection->prepare($updatesql);
        $updatestmt->execute();


        $_SESSION["PRegistration"]=true;
        header("Location:NewPatientReport.php");
        return;
    }elseif (isset($_POST["backToHome"])){
        unset($_SESSION["PRegistration"]);
        header("Location:index.php");
        return;
    }

    $searchedId=$_SESSION["searchedId"];
    $searchedPerson = $userBuilder->buildUser($searchedId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    --><link href="css/bootstrap.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <title>CovInfo - New Patient Form</title>
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
                        if($user->getUserType() != "Public"){?>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="search.php">Search</a>
                            </li>
                        <?php }
                    }
                    ?>


                </ul>
                
                <?php if ($logged_user) { ?>
                    <ul class="nav navbar-nav ">
                        <li class="nav-item"><a class="nav-link" href="register.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M12 2.5a5.5 5.5 0 00-3.096 10.047 9.005 9.005 0 00-5.9 8.18.75.75 0 001.5.045 7.5 7.5 0 0114.993 0 .75.75 0 101.499-.044 9.005 9.005 0 00-5.9-8.181A5.5 5.5 0 0012 2.5zM8 8a4 4 0 118 0 4 4 0 01-8 0z"></path></svg><?php echo $user->getFirstName()." ".$user->getLastName()?></a></li>
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
            <p class=" fs-5 text-center fw-bold">New Patient Admission Form</p>
        </div>
        <?php if(!isset($_SESSION['PRegistration'])){?>
        <div class="row border-bottom border-primary">
            <p class=" fs-5 text-justify">Patient Details</p>
        </div>
        <div class="row">
            <div class="col-sm">
                <label class="form-label" for="first-name">First Name</label>
                <input class="form-control" type="text" value="<?=$searchedPerson->getFirstName() ?>" name="first_name" form="new-patient" readonly>
            </div>
            <div class="col-sm">
                <label class="form-label" for="middle_name">Middle Name</label>
                <input class="form-control" type="text" id="second-name" value="<?=$searchedPerson->getMiddleName()?>" name="middle_name" form="new-patient" readonly>
            </div>
            <div class="col-sm">
                <label  class="form-label" for="last_name">Last Name</label>
                <input class="form-control" type="text" id="third-name" value="<?=$searchedPerson->getLastName() ?>" name="last_name" form="new-patient" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <label class="form-label" for="nic_number">ID Number</label>
                <input class="form-control" type="text" name="nic_number" id="id=no" value="<?=$searchedPerson->getNICNumber() ?>" form="new-patient" readonly>
            </div>
        </div>
    </div>
        <br>
    <div class=" container d-grid gap-3 bg-white p-3">
        <div class="row border-bottom border-primary ">
            <p class="fs-5 text-justify">Admission Details</p>
        </div>
        <form action="NewPatientReport.php" method="post">
            <div class="row">
                <div class="col-sm">
                    <input type="text" name="user_id" value="<?=$_SESSION["searchedId"]?>" style="display: none" readonly>
                    <label class="form-label" for="doc-name">Medical Officer</label>
                    <input class="form-control" type="text" name="doc-name" id="doc-name" value="<?= $user->getFirstName()." ".$user->getLastName()?>" readonly>
                </div>
                <div class="col-sm">
                    <label class="form-label" for="inst-name">Institute</label>
                    <select class="form-select" aria-label="Default select example" name="medical_centre_id" required>
                        <option  selected value="">Select a Institute</option>
                        <?php
                            $medical_centres = $connection->query("SELECT * FROM medical_centre")->fetchAll(PDO:: FETCH_ASSOC);
                            foreach ($medical_centres as $row){
                                echo ("<option value=$row[medical_centre_id]>$row[name]</option>");
                            }

                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <label class="form-label" for="admission-date">Admission Date</label>
                <div class="col-sm-3">
                <input class="form-control" type="text" name="admission-date" id="admission-date" value="<?= date('Y-m-d', time());?>" readonly>
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
                    <button type="submit" class="btn btn-outline-secondary btn-lg" name="cancel">Cancel</button>
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
                <strong>Registration success!</strong>
            </div>
            <form method="post">
                <div class="text-center pb-5">
                    <input type="text" name = "backToHome" value="1" hidden>
                    <button type="submit" class="btn btn-outline-secondary">Back To Home</button>
                </div>
            </form>

        <?php } ?>
    </div>
    <script src="js/bootstrap.js"></script>
</body>
</html>



