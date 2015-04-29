<?php
include_once "db.php";
//require 'phpMailer/PHPMailerAutoload.php';
//$mail = new PHPMailer;
ini_set("log_errors", 1);
ini_set("error_log", "/php-error.log");
$response = "";
try
{
    // Verify the connection is not null
    //if($connection)
    {
        // Get action if any
        if(isset($_POST['action']))
        {
            // Perform a switch to apply the correct action for the request
            switch($_POST['action']) { 
                // Gets the list of products from the database. 
                // Builds the HTML so we can easily request the information and update the page it is shown on in a callback
                case 'getServerList':
                    mysqli_set_charset($conn, "utf8");         
                    $query = "SELECT
                            s.SERVER_ID,
                            s.SERVER_NAME,
                            s.SERVER_ADDRESS,
                            m.LATENCY,
                            m.UP,
                            m.CONNECTION_DATE
                            FROM Servers s INNER JOIN
                            MonitorResults m
                            ON s.Server_ID = m.SERVER_ID
                            INNER JOIN
                            (
                            SELECT SERVER_ID, LATENCY, UP,CONNECTION_DATE, MAX(CONNECTION_DATE) AS Max_Date
                            FROM MonitorResults
                            GROUP BY SERVER_ID) c
                            ON s.SERVER_ID= c.SERVER_ID
                            AND m.CONNECTION_DATE = c.Max_Date
                            WHERE s.DELETED = 0
                            GROUP BY s.SERVER_ID";
                    $rawResult = mysqli_query($conn, $query) or die(mysqli_error($conn));                 
                    
                    // Create the table
                    $response .= '<table id="serversTable" style="margin: 0px; width: 100%;" class="sticky-enabled">';
                    $response .= '<thead>';
                    $response .= '<tr><th></th>'; // Empty header cell for command button column
                    $response .= '<th>Server ID</th>';
                    $response .= '<th>Server Name</th>';
                    $response .= '<th>Address</th>';
                    $response .= '<th>Status</th>';
                    $response .= '<th>Latency</th>';
                    $response .= '<th>Connection Date</th>';
                    $response .= '</tr>';
                    $response .= '</thead>';
                    $response .= '<tbody>';
                    
                    // Loop the query results and create the product rows. 
                    while($result = mysqli_fetch_array($rawResult, MYSQLI_ASSOC))
                    {
                        if ($result['UP']) 
                        { 
                            $status = "Online"; 
                        } 
                        else 
                        { 
                            $status = "Offline"; 
                        }

                        if($result['LATENCY'] == 10000)
                        { 
                            $latency = ""; 
                        } 
                        else 
                        { 
                            $latency = $result['LATENCY'];
                        }
                        $response .= '<tr>';
                        $response .= '<td class="commmandButtonCell">';
                        $response .= '<input type="button" value="'.$result['SERVER_ID'].';'.$result['SERVER_NAME'].';'.$result['SERVER_ADDRESS'].'" title="View the server\'s history" class="cellCommandButton viewHistory" />';
                        $response .= '<input type="button" value="'.$result['SERVER_ID'].';'.$result['SERVER_NAME'].';'.$result['SERVER_ADDRESS'].'" title="View the server\'s error log" class="cellCommandButton viewErrors" />';
                        $response .= '</td>';
                        $response .= '<td class="server_id">'.$result['SERVER_ID'].'</td>';
                        $response .= '<td class="server_name">'.$result['SERVER_NAME'].'</td>';
                        $response .= '<td class="server_address">'.$result['SERVER_ADDRESS'].'</td>';
                        $response .= '<td class="server_status '.$status.'">'.$status.'</td>';
                        $response .= '<td class="server_latency">'.$latency.'</td>';    
                        $response .= '<td class="server_date">'.$result['CONNECTION_DATE'].'</td>';             
                        $response .= '</tr>';
                    }
                    
                    $response .= '</tbody>';
                    $response .= '</table>';
                    htmlentities((string) $response, ENT_QUOTES, 'utf-8', FALSE);
                    break;
                // Gets the monitoring results for the server
                case 'getServerErrors':
                    $response = "";
                    $serverID = htmlspecialchars($_POST["serverID"]);
                    error_log("ServerID ".$serverID, 0);
                    mysqli_set_charset($conn, "utf8");         
                    $query = "SELECT
                            s.SERVER_ID,
                            s.SERVER_NAME,
                            s.SERVER_ADDRESS,
                            e.ERROR_MESSAGE,
                            e.DATE_OCCURRED
                            FROM Servers s INNER JOIN
                            ErrorLog e
                            ON s.Server_ID = e.SERVER_ID                           
                            WHERE s.SERVER_ID= '$serverID'
                            ORDER BY e.DATE_OCCURRED DESC";
                    
                    $rawResult = mysqli_query($conn, $query) or die(mysqli_error($conn));                 
                    
                    if(mysqli_num_rows($rawResult)){
                        // Create the table
                        $response .= '<table id="serverHistoryTable" style="margin: 0px; width: 100%;" class="sticky-enabled">';
                        $response .= '<thead>';
                        $response .= '<tr>';
                        $response .= '<th class="sorter-false">Server ID</th>';
                        $response .= '<th class="sorter-false">Server Name</th>';
                        $response .= '<th class="sorter-false">Address</th>';
                        $response .= '<th class="sorter-false">Error</th>';
                        $response .= '<th class="sorter-false">Occurred</th>';
                        $response .= '</tr>';
                        $response .= '</thead>';
                        $response .= '<tbody>';
                        
                        // Loop the query results and create the product rows. 
                        while($result = mysqli_fetch_array($rawResult, MYSQLI_ASSOC))
                        {
                            $response .= '<tr>';
                            $response .= '<td class="server_id">'.$result['SERVER_ID'].'</td>';
                            $response .= '<td class="server_name">'.$result['SERVER_NAME'].'</td>';
                            $response .= '<td class="server_address">'.$result['SERVER_ADDRESS'].'</td>';
                            $response .= '<td class="server_errorMessage">'.$result['ERROR_MESSAGE'].'</td>';
                            $response .= '<td class="server_errorDate">'.$result['DATE_OCCURRED'].'</td>';               
                            $response .= '</tr>';
                        }
                        
                        $response .= '</tbody>';
                        $response .= '</table>';
                        htmlentities((string) $response, ENT_QUOTES, 'utf-8', FALSE);
                    }
                    break;
                // Gets the monitoring errors for the server
                case 'getServerHistory':
                    $response = "";
                    $serverID = htmlspecialchars($_POST["serverID"]);
                    error_log("ServerID ".$serverID, 0);
                    mysqli_set_charset($conn, "utf8");         
                    $query = "SELECT
                            s.SERVER_ID,
                            s.SERVER_NAME,
                            s.SERVER_ADDRESS,
                            m.LATENCY,
                            m.UP,
                            m.CONNECTION_DATE
                            FROM Servers s INNER JOIN
                            MonitorResults m
                            ON s.Server_ID = m.SERVER_ID                           
                            WHERE s.SERVER_ID= '$serverID'
                            ORDER BY m.CONNECTION_DATE DESC";
                    
                    $rawResult = mysqli_query($conn, $query) or die(mysqli_error($conn));                 
                    
                    if(mysqli_num_rows($rawResult)){

                        // Create the table
                        $response .= '<table id="serverHistoryTable" style="margin: 0px; width: 100%;" class="sticky-enabled">';
                        $response .= '<thead>';
                        $response .= '<tr>';
                        $response .= '<th class="sorter-false">Server ID</th>';
                        $response .= '<th class="sorter-false">Server Name</th>';
                        $response .= '<th class="sorter-false">Address</th>';
                        $response .= '<th class="sorter-false">Status</th>';
                        $response .= '<th class="sorter-false">Latency</th>';
                        $response .= '<th class="sorter-false">Connection Date</th>';
                        $response .= '</tr>';
                        $response .= '</thead>';
                        $response .= '<tbody>';
                        
                        // Loop the query results and create the product rows. 
                        while($result = mysqli_fetch_array($rawResult, MYSQLI_ASSOC))
                        {
                            if ($result['UP']) { $status = "Online"; } else { $status = "Offline"; }
                            $response .= '<tr>';
                            $response .= '<td class="server_id">'.$result['SERVER_ID'].'</td>';
                            $response .= '<td class="server_name">'.$result['SERVER_NAME'].'</td>';
                            $response .= '<td class="server_address">'.$result['SERVER_ADDRESS'].'</td>';
                            $response .= '<td class="server_status '.$status.'">'.$status.'</td>';
                            $response .= '<td class="server_latency">'.$result['LATENCY'].'</td>';    
                            $response .= '<td class="server_date">'.$result['CONNECTION_DATE'].'</td>';             
                            $response .= '</tr>';
                        }
                        
                        $response .= '</tbody>';
                        $response .= '</table>';
                        htmlentities((string) $response, ENT_QUOTES, 'utf-8', FALSE);
                    }
                    break;
                // Gets the list of products from the database. 
                // Builds the HTML so we can easily request the information and update the page it is shown on in a callback
                case 'getServerObjects':
                    mysqli_set_charset($conn, "utf8");         
                    $query = "SELECT
                            s.SERVER_ID,
                            s.SERVER_NAME,
                            s.SERVER_ADDRESS,
                            m.LATENCY,
                            m.UP,
                            m.CONNECTION_DATE
                            FROM Servers s INNER JOIN
                            MonitorResults m
                            ON s.Server_ID = m.SERVER_ID
                            INNER JOIN
                            (
                            SELECT SERVER_ID, LATENCY, UP,CONNECTION_DATE, MAX(CONNECTION_DATE) AS Max_Date
                            FROM MonitorResults
                            GROUP BY SERVER_ID) c
                            ON s.SERVER_ID= c.SERVER_ID
                            AND m.CONNECTION_DATE = c.Max_Date
                            WHERE s.DELETED = 0
                            GROUP BY s.SERVER_ID
                            LIMIT 100";
                    $rawResult = mysqli_query($conn, $query) or die(mysqli_error($conn));                 
                    
                    // Create server array
                    $response = array(); 
                    // Loop the query results and create the product rows. 
                    while($result = mysqli_fetch_array($rawResult, MYSQLI_ASSOC))
                    {
                        if ($result['UP']) { $status = "Online"; } else { $status = "Offline"; }                      
                        $serverItem = array(
                                "ID" => $result['SERVER_ID'],
                                "Name" => $result['SERVER_NAME'],
                                "Address" => $result['SERVER_ADDRESS'],
                                "Status" => $status,
                                "Latency" => $result['LATENCY'],
                                "ConnectionDate" => $result['CONNECTION_DATE']
                            );
                        if($status == "Online"){
                        array_push($response, $serverItem);
                        }
                    }
                    break;
            }
            echo json_encode($response); 
        }
    }

}
catch(Exception $ex)
{
    $response['status'] = 'error';
    $response['message'] = $ex->getMessage();
    echo json_encode($response); 
}
?>