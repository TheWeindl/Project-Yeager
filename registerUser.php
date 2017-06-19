<?php
Require_Once('config.php');
Require_Once('userStartConfig.php');

function register($sUsername, $sPassword1, $sEmail){

    // open connection to db server and selcting the db
    if(! $oMySqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE)) {
        die("Database connection could not be established!");
    }

    $sInsert = "INSERT INTO user (username, userpw, useremail) VALUES ('".$oMySqli->real_escape_string($sUsername)."', '". password_hash($sPassword1, PASSWORD_DEFAULT) ."', '". $oMySqli->real_escape_string($sEmail)."');";
    $oMySqli->query($sInsert);

    $query = "SELECT userID FROM user WHERE username = '{$sUsername}'";
    $Res = $oMySqli->query($query);

    $userData = mysqli_fetch_assoc($Res);

    $userID = (int)$userData["userID"];
    $_SESSION["userID"] = $userID;

    //Set the starting values in the tabeles
    SetStartResources($oMySqli);
    SetUserInfo($oMySqli);
    SetBuildings($oMySqli);

    // close connection to db server
    if(!$oMySqli->close()) {
        echo("Database connection could not be closed");
    }
}

function SetUserInfo($oMysqli){

    $sSetTimeStamp = "INSERT INTO userinfo (userID, lastrefresh, coordX, coordY) VALUES ({$_SESSION['userID']},{new date(\"Y-m-d H:i:s\")}, 1, 1 )";
    $oMysqli->query($sSetTimeStamp);
}

function SetStartResources($oMysqli){

    global $startResources;

    $sSetResources = "INSERT INTO ressources (userID, wood, metal, stone, people) VALUES ({$_SESSION['userID']},{$startResources['wood']}, {$startResources['metal']}, {$startResources['stone']}, {$startResources['people']} )";
    $oMysqli->query($sSetResources);
}

function SetBuildings($oMysqli){

    $sSetBuildings = "INSERT INTO buildings (userID, headquarter, woodFactory, stoneFactory, metalFactory) VALUES ({$_SESSION['userID']},1,1,1,1)";
    $oMysqli->query($sSetBuildings);
}

?>