<?php
$username = "sql474784";
$password = "gD2!tL9!";
$hostname = "sql4.freemysqlhosting.net";
$database = "sql474784";

//Connect to the database
$conn = mysqli_connect($hostname, $username, $password, $database);

updateResult($conn);

function updateResult($conn)
{
	$GLOBALS['result'] = mysqli_query($conn, "select * FROM Servers WHERE deleted = 0");
	$GLOBALS['server_count'] = mysqli_num_rows($GLOBALS['result']);
}

function resultsArray($conn)
{
	updateResults($conn);
	$result = array();
	while ($row = mysqli_fetch_array($GLOBALS['result'], MYSQLI_ASSOC))
	{
		$result[] = $row;
	}
	return $result;
}
?>


