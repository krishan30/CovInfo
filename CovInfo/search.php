<?php
    require_once ("Classes/classes.php");

    session_start();

    $logged_user = isset($_SESSION["LogIn"]);
    $connection = PDOSingleton::getInstance();
    $user = null;
    $result = false;
    $results = [];
    if($logged_user){
        $user_id = $_SESSION["user_id"];
        $userProxyFactory = new UserProxyFactory();
        $user = $userProxyFactory->build($user_id);
        if($user->getUserType() == "Public"){
            header("Location:index.php");
            return;
        }
    }else{
        header("Location:Login.php");
        return;
    }

    if(isset($_POST["account_id"]) || isset($_POST["nic_number"])){
        if($_POST["account_id"] != null || $_POST["nic_number"] != null){
            $_SESSION["search_account_id"] = $_POST["account_id"];
            $_SESSION["search_nic_number"] = $_POST["nic_number"];
        }

        header("Location:search.php");
        return;

    }

    if(isset($_GET["findPage"])){
        if($_GET["id"] != ""){
            header("Location:profile-view.php?id=".$_GET["id"]);
        }
    }

    if(isset($_SESSION["search_account_id"]) || isset($_SESSION["search_nic_number"])){
        $result = true;
        $searchSql = "SELECT user.user_id FROM user WHERE user.account_id=:account_id OR user.nic_number=:nic_number ORDER BY account_id";
        $searchstmt = $connection->prepare($searchSql);
        $searchstmt->execute(array(':account_id' => $_SESSION["search_account_id"], ':nic_number'=>$_SESSION["search_nic_number"]));

        unset($_SESSION["search_account_id"]);
        unset($_SESSION["search_nic_number"]);
    }




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CovInfo - Home</title>
    <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css.map" />
    <link rel="stylesheet" href="styles/search.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <!--    <link href="
https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css
" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 --><link rel="stylesheet" href="css\bootstrap.css">
    <link rel="stylesheet" href="styles\styles.css">
    <link rel = "icon" href = "logos/logo_icon.png"
          type = "image/x-icon">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow fixed-top bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="logos\brand.png" alt="Site logo" width="110px" height="auto"> </a>
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
                    if($user->getUserType() != "Public"){?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="search.php">Search</a>
                        </li>
                    <?php }
                }
                ?>
            </ul>
            </ul>
            <?php if ($logged_user) { ?>
                <ul class="nav navbar-nav ">
                    <li class="nav-item"><a class="nav-link" href="profile.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M12 2.5a5.5 5.5 0 00-3.096 10.047 9.005 9.005 0 00-5.9 8.18.75.75 0 001.5.045 7.5 7.5 0 0114.993 0 .75.75 0 101.499-.044 9.005 9.005 0 00-5.9-8.181A5.5 5.5 0 0012 2.5zM8 8a4 4 0 118 0 4 4 0 01-8 0z"></path></svg><?php echo $user->getFirstName()." ".$user->getLastName() ?></a></li>
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
<br><br>
<p class="h-4 m-3 p-3 row justify-content-center border border-2 rounded-3 boxy" style="font-weight: bold">Search user</p>
<br>
<p class="container justify-content-center">

    <form method="post" action="search.php">
        <div class="container">
            <div class="search">
                <div class="row">
                    <div class="col-md-6">
                        <div class="search-1"> <i class='bx bx-search-alt'></i> <input type="text" name="account_id" placeholder="Enter Account ID"> </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <div class="search-2"> <i class='bx bxs-map'></i> <input type="text" name="nic_number" placeholder="Enter NIC"><button type="submit" name="search">Search</button> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <?php
        if($result){
            $i = 0;
            while ($row = $searchstmt->fetch(PDO::FETCH_ASSOC)){
                $i += 1;
                if($i == 1){?>
                    <div class="container">
                        <div class="table-wrap">
                            <table class="table table-borderless table-responsive">
                                <thead>
                                <tr>
                                    <th class="text-muted fw-600">No</th>
                                    <th class="text-muted fw-600">Email</th>
                                    <th class="text-muted fw-600">NIC Number</th>
                                    <th class="text-muted fw-600">Name</th>
                                    <th class="text-muted fw-600">Address</th>
                                    <th class="text-muted fw-600">Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                <?php }
                $newResult = $userProxyFactory->build($row["user_id"]);
                $name = $newResult->getFirstName()." ".$newResult->getMiddleName()." ".$newResult->getLastName();
                $nic = $newResult->getNICNumber();
                $sex = $newResult->getGender();
                $status = get_class($newResult->getUserState());
                $address = $newResult->getAddress();
                $email = $newResult->getEmailAddress();
                $accountId = $newResult->getAccountId();
                ?>
                <tr class="align-middle alert" role="alert">
                    <td> <?php echo $i ?></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="img-container">
                                <?php if($sex == "Male"){?>
                                    <img src="images/User-big.png" alt="">
                                <?php } else{ ?>
                                    <img src="images/User-female.png" alt="">
                                <?php } ?>
                                 </div>
                            <div class="ps-3">
                                <div class="fw-600 pb-1"><?php echo $email ?></div>
                                <p class="m-0 text-grey fs-09"><?php echo $accountId ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-600"><?php echo $nic ?></div>
                    </td>
                    <td>
                        <div class="fw-600"><?php echo $name ?></div>
                    </td>
                    <td>
                        <div class="fw-600"><?php echo $address ?></div>
                    </td>
                    <td>
                        <div class="d-inline-flex align-items-center active">
                            <div class="circle"></div>
                            <div class="ps-2"><?php echo $status ?></div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-600">
                            <form method="get">
                                <input type="text" name="id" value="<?php echo $row["user_id"]?>" hidden>
                                <button type="submit" name="findPage">Select</button>
                            </form>
                        </div>
                    </td>
                </tr>

            <?php }
            if($i == 0){ ?>
                <div class="container boxy-blue text-center">
                    <div class="h1">Result not found!</div>
                </div>
            <?php }else{ ?>
                </tbody>
                    </table>
                </div>
            </div>
           <?php }
        }
    ?>







</div>

<!--<script src="
https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js
" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
-->
<script src="js\bootstrap.js"></script>
</body>
</html>