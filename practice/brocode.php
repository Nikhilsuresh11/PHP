
<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <form action="brocode.php" method="post">
        <label> username : </label> 
        <input type="text" name = "username"><br>
        <label> email : </label> 
        <input type="email" name = "email">
        <input type="submit" value="Log in">
        </form>
    </body>
    </html>


<?php
    /*
    $name = "Bro <br>";
    echo $name;
    echo " hello {$name}";

    $x =10;
    $y = 9;

    */

    echo "{$_POST["username"]}<br>";
    echo "{$_POST["email"]}<br>";

    if ($_POST["username"] == "Nikhil"){
        echo "success";
    }
    else{
        echo "fail";
    }

    $foods = array("hi", "hlo" , "hiii","huuuu");

    echo $foods[0];

?>