<?php
$start_date = "10/12/2021" ;
$end_date = "10/12/2021" ;
$quarantine_place_name = "Home" ;
$remarks = "stay the f at home." ;
$i = 1 ;
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
                    
                    


                </ul>
                    <span class="navbar-text">Already have an account?</span>
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                            <a class="nav-link" role="button" aria-expanded="false" href="Login.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M3 3.25c0-.966.784-1.75 1.75-1.75h5.5a.75.75 0 010 1.5h-5.5a.25.25 0 00-.25.25v17.5c0 .138.112.25.25.25h5.5a.75.75 0 010 1.5h-5.5A1.75 1.75 0 013 20.75V3.25zm9.994 9.5l3.3 3.484a.75.75 0 01-1.088 1.032l-4.5-4.75a.75.75 0 010-1.032l4.5-4.75a.75.75 0 011.088 1.032l-3.3 3.484h8.256a.75.75 0 010 1.5h-8.256z"></path></svg>Login</a>
                        </li>
                    </ul>
            </div>
        </div>
    </nav>
    <br><br><br><br>

    <div class="container p-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
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
                            <tr>
                                <th scope="row"><?php echo $i ?></th>
                                <td><?php echo $start_date?></td>
                                <td><?php echo $end_date?></td>
                                <td><?php echo $quarantine_place_name?></td>
                                <td><?php echo $remarks?></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                
                <div class="h4 p-3 px-5" style="border-bottom: solid #0d6efd"> Extend Quarrantine Period</div>
                <br>
                <div class="row">
                    <div class="col-3">
                        <label for="new_end_date" class="form-label">Enter new ending date:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <input type="date" class="form-control col-3" id="new_end_date" formaction="extendQuarantine.php">
                    </div>
                    <div class="col-sm">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div> 
            
               
        </div>
    </div>
