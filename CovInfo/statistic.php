<?php
require_once "Classes/classes.php";

session_start();
$today = date("Y-m-d");
$connection = PDOSingleton::getInstance();
$stmt = $connection->query("SELECT new_cases,deaths,recovered,date FROM daily_report WHERE date = '$today'");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $new_cases = $row['new_cases'];
    $new_deaths = $row['deaths'];
    $new_recovered = $row['recovered'];
}

$stmt = $connection->query("SELECT total_cases,total_deaths,total_recovered FROM report");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $total_cases = $row['total_cases'];
    $total_deaths = $row['total_deaths'];
    $total_recovered = $row['total_recovered'];
}


$logged_user = isset($_SESSION["LogIn"]);
$user = null;
if($logged_user){
    $user_id = $_SESSION["user_id"];
    $userFactory = new UserFactory();
    $user = $userFactory->buildUser($user_id);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CovInfo - Dashboard</title>
    <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />

    <!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        --><link rel="stylesheet" href="css\bootstrap.css">
    <link rel="stylesheet" href="styles\styles.css">
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
                    <a class="nav-link active" aria-current="page" href="#">Statistics</a>
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
<br><br>
<p class="h-4 m-3 p-3 row justify-content-center border border-2 rounded-3 boxy">COVID-19 : Live Situational Analysis Dashboard of Sri Lanka</p>
<br>
<div class="container-fluid align-content-center">
    <div class="container justify-content-center col-7 boxy-blue rounded-3">
        <div class="row align-content-center py-1 border-bottom"><span style="font-weight: bold">Daily Figures: (<?php echo $today ?>)</span></div>
        <div class="row justify-content-center py-4">
            <div class="col mx-5 rounded-3 boxy-blue">
                <div class="row">
                    <div class="col-3 border-end py-4 px-1"><img src="images\cases.png" width="45px" height="45px" alt=""></div>
                    <div class="col-9">
                        <div class="row py-2 border-bottom" style="text-align: center; display: block;">New Cases</div>
                        <div class="row pt-2 h4" style="text-align: center; display: block; color: #0d6efd;"><?php echo $new_cases;?></div>
                    </div>
                </div>
            </div>
            <div class="col mx-5 rounded-3 boxy-red">
                <div class="row">
                    <div class="col-3 border-end py-4 px-1"><img src="images\2PX.png" width="45px" height="45px" alt=""></div>
                    <div class="col-9">
                        <div class="row py-2 border-bottom" style="text-align: center; display: block;">Deaths</div>
                        <div class="row pt-2 h4" style="text-align: center; display: block; color: rgba(255,46,49,0.87);"><?php echo $new_deaths;?></div>
                    </div>
                </div>
            </div>
            <div class="col mx-5 rounded-3 boxy-green">
                <div class="row">
                    <div class="col-3 border-end py-2 ps-0 pt-3"><img src="images\recovered.png" width="50px" height="50px" alt=""></div>
                    <div class="col-9">
                        <div class="row py-2 border-bottom" style="text-align: center; display: block;">Recovered</div>
                        <div class="row pt-2 h4" style="text-align: center; display: block; color: rgba(47,231,47,0.87); -webkit-text-stroke: 0.1px black;"><?php echo $new_recovered;?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="container justify-content-center col-7 boxy-blue rounded-3">
        <div class="row align-content-center py-1 border-bottom"><span style="font-weight: bold">Total Figures</span></div>
        <div class="row justify-content-center py-4">
            <div class="col mx-5 rounded-3 boxy-blue">
                <div class="row">
                    <div class="col-3 border-end py-4 px-1"><img src="images\cases.png" width="45px" height="45px" alt=""></div>
                    <div class="col-9">
                        <div class="row py-2 border-bottom" style="text-align: center; display: block;">Total Cases</div>
                        <div class="row pt-2 h4" style="text-align: center; display: block; color: #0d6efd;"><?php echo $total_cases;?></div>
                    </div>
                </div>
            </div>
            <div class="col mx-5 rounded-3 boxy-red">
                <div class="row">
                    <div class="col-3 border-end py-4 px-1"><img src="images\2PX.png" width="45px" height="45px" alt=""></div>
                    <div class="col-9">
                        <div class="row py-2 border-bottom" style="text-align: center; display: block;">Total Deaths</div>
                        <div class="row pt-2 h4" style="text-align: center; display: block; color: rgba(255,46,49,0.87);"><?php echo $total_deaths;?></div>
                    </div>
                </div>
            </div>
            <div class="col mx-5 rounded-3 boxy-green">
                <div class="row">
                    <div class="col-3 border-end py-2 ps-0 pt-3"><img src="images\recovered.png" width="50px" height="50px" alt=""></div>
                    <div class="col-9">
                        <div class="row py-2 border-bottom" style="text-align: center; display: block;">Total Recovered</div>
                        <div class="row pt-2 h4" style="text-align: center; display: block; color: rgba(47,231,47,0.87); -webkit-text-stroke: 0.1px black;"><?php echo $total_recovered;?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-5 boxy-blue my-3 mx-3 text-center rounded-3">
            <span style="font-weight: bold">Daily New Cases</span>
            <div id="bar-chart"></div>
        </div>
        <div class="col-5 boxy-blue my-3 mx-3 text-center rounded-3">
            <span style="font-weight: bold">Cumulative Cases</span>
            <div id="line-chart"></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-5 boxy-blue my-3 mx-3 text-center rounded-3">
            <span style="font-weight: bold">Daily Deaths</span>
            <div id="bar-chart-1"></div>
        </div>
        <div class="col-5 boxy-blue my-3 mx-3 text-center rounded-3">
            <span style="font-weight: bold">Cumulative Deaths</span>
            <div id="line-chart-1"></div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6 boxy-blue my-3 mx-3 text-center rounded-3">
            <span style="font-weight: bold">New Cases vs Recovered</span>
            <div id="line-chart-2"></div>
        </div>
        <div class="col-4 boxy-blue my-3 mx-3 text-center rounded-3">
            <span style="font-weight: bold">Summary of Total Cases</span>
            <div id="donut-chart"></div>
        </div>
    </div>
</div>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="js\bootstrap.js"></script>

<script>
    $(document).ready(function() {
        barChart();
        lineChart();
        barChart2();
        lineChart2();
        lineChart3();
        donutChart()


        $(window).resize(function() {
            window.barChart.redraw();
            window.lineChart.redraw();
            window.barChart2.redraw();
            window.lineChart2.redraw();
            window.lineChart3.redraw();
            window.donutChart.redraw();
        });
    });

    function barChart() {
        window.barChart = Morris.Bar({
            element: 'bar-chart',
            data: [
                <?php
                    for ($i = 0; $i < 13; $i++) {
                        $day = Date("Y-m-d", strtotime("-" . (13 - $i) . " days"));
                        $stmt = $connection->query("SELECT new_cases FROM daily_report WHERE date='$day'");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "{ y: '".$day."', a: ".$row["new_cases"]."},";
                        }
                    }

                    $stmt = $connection->query("SELECT new_cases FROM daily_report WHERE date='$today'");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "{ y: '".$today."', a: ".$row["new_cases"]."}";
                    }
                ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['New Cases'],
            lineColors: ['#1e88e5'],
            lineWidth: '3px',
            resize: true,
            redraw: true
        });
    }

    function lineChart() {
        window.lineChart = Morris.Line({
            element: 'line-chart',
            data: [
                <?php
                    for ($i = 0; $i < 13; $i++) {
                        $day = Date("Y-m-d", strtotime("-" . (13 - $i) . " days"));
                        $stmt = $connection->query("SELECT new_cases FROM daily_report WHERE date='$day'");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "{ y: '".$day."', a: ".$row["new_cases"]."},";
                        }
                    }

                    $stmt = $connection->query("SELECT new_cases FROM daily_report WHERE date='$today'");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "{ y: '".$today."', a: ".$row["new_cases"]."}";
                    }
                ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['New Cases'],
            lineColors: ['#1e88e5'],
            lineWidth: '3px',
            resize: true,
            redraw: true
        });
    }

    function barChart2() {
        window.barChart2 = Morris.Bar({
            element: 'bar-chart-1',
            data: [
                <?php
                    for ($i = 0; $i < 13; $i++) {
                        $day = Date("Y-m-d", strtotime("-" . (13 - $i) . " days"));
                        $stmt = $connection->query("SELECT deaths FROM daily_report WHERE date='$day'");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "{ y: '".$day."', a: ".$row["deaths"]."},";
                        }
                    }

                    $stmt = $connection->query("SELECT deaths FROM daily_report WHERE date='$today'");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "{ y: '".$today."', a: ".$row["deaths"]."}";
                    }
                ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Deaths'],
            lineColors: ['#1e88e5'],
            barColors: ['#e4494d'],
            lineWidth: '3px',
            resize: true,
            redraw: true
        });
    }

    function lineChart2() {
        window.lineChart2 = Morris.Line({
            element: 'line-chart-1',
            data: [
                <?php
                    for ($i = 0; $i < 13; $i++) {
                        $day = Date("Y-m-d", strtotime("-" . (13 - $i) . " days"));
                        $stmt = $connection->query("SELECT deaths FROM daily_report WHERE date='$day'");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "{ y: '".$day."', a: ".$row["deaths"]."},";
                        }
                    }

                    $stmt = $connection->query("SELECT deaths FROM daily_report WHERE date='$today'");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "{ y: '".$today."', a: ".$row["deaths"]."}";
                    }
                ?>
            ],
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Deaths'],
            lineColors: ['#e4494d'],
            lineWidth: '3px',
            resize: true,
            redraw: true
        });
    }

    function lineChart3() {
        window.lineChart3 = Morris.Line({
            element: 'line-chart-2',
            data: [
                <?php
                    for ($i = 0; $i < 13; $i++) {
                        $day = Date("Y-m-d", strtotime("-" . (13 - $i) . " days"));
                        $stmt = $connection->query("SELECT new_cases,recovered FROM daily_report WHERE date='$day'");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "{ y: '".$day."', a: ".$row["recovered"].", b: ".$row["new_cases"]."},";
                        }
                    }

                    $stmt = $connection->query("SELECT new_cases,recovered FROM daily_report WHERE date='$today'");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "{ y: '".$today."', a: ".$row["recovered"].", b: ".$row["new_cases"]."},";
                    }
                ?>
            ],
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['Recovered', 'New Cases'],
            lineColors: ['#1e88e5','#e4494d'],
            lineWidth: '3px',
            resize: true,
            redraw: true
        });
    }

    function donutChart() {
        window.donutChart = Morris.Donut({
            element: 'donut-chart',
            data: [
                {label: "Active", value: <?php echo ($total_cases - $total_deaths - $total_recovered)?> ,color: '#0384c5'},
                {label: "Dead", value: <?php echo $total_deaths?> , color: '#e4494d'},
                {label: "Recovered", value: <?php echo $total_recovered?>, color: '#79ca53'}
            ],
            resize: true,
            redraw: true
        });
    }
</script>

</body>
</html>