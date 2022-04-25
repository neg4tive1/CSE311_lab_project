<?php
    header("Content-Type: application/json; charset=UTF-8");
    
    // no API_KEY or no METHOD throws error and exits
    if(!isSet($_GET["API_KEY"]) || !isSet($_GET["METHOD"]))
    {
        if (!isset($param)) $param = new stdClass();
        $param->status = "WRONG QUERY PARAM";
        $param->status_code = 400;
        echo json_encode($param);
        exit();
    }
    
    // wrong API_KEY throws error and exits
    if($_GET["API_KEY"] != "KEY")
    {
        if (!isset($verification)) $verification = new stdClass();
        $verification->status = "WRONG API_KEY";
        $verification->status_code = 400;
        echo json_encode($verification);
        exit();
    }
    
 
    
    // method that echos Connection information
    function returnConnectionInformation()
    {
        $host ="localhost";
        $username ="root";
        $password ="";
        $db = "id18081046_icarus_databse";
        
        // connect to database
        $mysqli = mysqli_connect($host, $username, $password, $db);
        
        if (!isset($status)) $status = new stdClass();
        if ($mysqli -> connect_errno) {
          $status->status_code = 500;
          $status->response = "internal server error";
          $status->error = $mysqli -> connect_errno;
          echo json_encode($status);
          exit();
        }
        else {
         $status->status_code = 200;
         $status->response = "connection successful";
         $status->error = null;
         echo json_encode($status);
        }
        // close connection
        mysqli_close($mysqli);
    }
    
    // executes given query
    function executeQuery()
    {
        $host ="localhost";
        $username ="root";
        $password ="";
        $db = "id18081046_icarus_databse";
        
        // connect to database
        $mysqli = mysqli_connect($host, $username, $password, $db);
        if (!isset($status)) $status = new stdClass();
        if ($mysqli -> connect_errno) {
          $status->status_code = 500;
          $status->response = "internal server error";
          $status->error = $mysqli -> connect_errno;
          echo json_encode($status);
          exit();
        }
    
        // Perform query
        if(($_GET["TYPE"] == "DBA"))
        {
            if ($result = mysqli_multi_query($mysqli, $_GET["QUERY"])) 
            {
            if (!isset($response)) $response = new stdClass();
            $response->query = $_GET["QUERY"];
            $response->query_type = $_GET["TYPE"];
            $response->status = $result; 
            if($_GET["TYPE"] == "RETRIEVE")
            {
                $response->data = $result->fetch_all(MYSQLI_ASSOC); 
                //http://192.168.64.2/db_manager.php?API_KEY=KEY&&QUERY=SELECT%20name,email,userID,password%20FROM%20users%20WHERE%20email='email';&&METHOD=executeQuery&&TYPE=RETRIEVE
            }
            echo json_encode($response);
        }
        else 
        {
            if (!isset($response)) $response = new stdClass();
            $response->query = $_GET["QUERY"];
            $response->query_type = $_GET["TYPE"];
            $response->status = false; 
            $response->error = mysqli_error($mysqli); 
            echo json_encode($response);
        }
        }
        else{

            if ($result = mysqli_query($mysqli, $_GET["QUERY"])) 
            {
            if (!isset($response)) $response = new stdClass();
            $response->query = $_GET["QUERY"];
            $response->query_type = $_GET["TYPE"];
            $response->status = $result; 
            if($_GET["TYPE"] == "RETRIEVE")
            {
                $response->data = $result->fetch_all(MYSQLI_ASSOC); 
                //http://192.168.64.2/db_manager.php?API_KEY=KEY&&QUERY=SELECT%20name,email,userID,password%20FROM%20users%20WHERE%20email='email';&&METHOD=executeQuery&&TYPE=RETRIEVE
            }
            echo json_encode($response);
            }
        else 
        {
            if (!isset($response)) $response = new stdClass();
            $response->query = $_GET["QUERY"];
            $response->query_type = $_GET["TYPE"];
            $response->status = false; 
            $response->error = mysqli_error($mysqli); 
            echo json_encode($response);
        }
        }
       
    
        // close connection
        mysqli_close($mysqli);
        exit();
    }

    // call the method to check the connection
    if($_GET["METHOD"] == "returnConnectionInformation") {
        returnConnectionInformation();
    }
    
    // call the method to execute DB query
    if($_GET["METHOD"] == "executeQuery") {
        if(!isSet($_GET["QUERY"]))
        {
            if (!isset($param)) $param = new stdClass();
            $param->status = "QUERY NONEXISTENT";
            $param->status_code = 400;
            echo json_encode($param);
            exit();
        }
        if(isSet($_GET["TYPE"]))
        {
            if($_GET["TYPE"] == "RETRIEVE" || $_GET["TYPE"] == "UPDATE"  || $_GET["TYPE"] == "DBA")
            {
                 executeQuery();
            }
            else
            {
                if (!isset($param)) $param = new stdClass();
                $param->status = "QUERY TYPE INVALID";
                $param->status_code = 400;
                echo json_encode($param);
                exit();
            }
        }
        else 
        {
            if (!isset($param)) $param = new stdClass();
            $param->status = "QUERY TYPE NONEXISTENT";
            $param->status_code = 400;
            echo json_encode($param);
            exit();
        }
    }
?>
