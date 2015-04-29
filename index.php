<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
include "db.php";
?>
<!DOCTYPE html>
<html class="no-js" lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Client-Side Application</title>
		<link rel="shortcut icon" href="http://tympanus.net/Tutorials/favicon.ico">
		<link rel="stylesheet" type="text/css" href="source/normalize.css">
		<link rel="stylesheet" type="text/css" href="source/demo.css">
		<link rel="stylesheet" type="text/css" href="source/component.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="scripts/jquery.tablesorter.js" type="text/javascript"></script>
		<script src="scripts/jquery.tablepager.js" type="text/javascript"></script>
		<script src="scripts/Utils.js" type="text/javascript"></script>
		<script src="scripts/main.js" type="text/javascript"></script>		
		<!--[if IE]>
  		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			<!-- Top Navigation -->
			<header>
				<h1>Server Monitor Dashboard</h1>					
			</header>
			<div class="component">
				<h2>Server Details</h2>
				<!--<p>...</a>.</p>-->
				<div id="tablediv" class="sticky-wrap">
					
				</div>
			</div>
			<!-- Server History -->
			<div id="serverHistoryModal" class="modalPopUp">
				<div id="serverHistoryModalContent">
					<h2>Server History</h2>
					<table style="width:100%; margin-bottom:20px; font-weight:bold;" class="sticky-enabled">
						<tr>
							<td style="width:1%;">
								Name:
							</td>
							<td>
								<label id="serverHistoryName"></label>
							</td>
						</tr>
						<tr>
							<td>
								Address:
							</td>
							<td>
								<label id="serverHistoryAddress"></label>
							</td>
						</tr>
					</table>
					<div id="serverHistoryResults">
					
					</div>
				</div>            
			</div>
			<!-- Server Errors -->
			<div id="serverErrorModal" class="modalPopUp">
				<div id="serverErrorModalContent">
					<h2>Server Error Log</h2>
					<table style="width:100%; margin-bottom:20px; font-weight:bold;" class="sticky-enabled">
						<tr>
							<td style="width:1%;">
								Name:
							</td>
							<td>
								<label id="serverErrorName"></label>
							</td>
						</tr>
						<tr>
							<td>
								Address:
							</td>
							<td>
								<label id="serverErrorAddress"></label>
							</td>
						</tr>
					</table>
					<div id="serverErrorResults">
					
					</div>
				</div>            
			</div>
		</div><!-- /container -->
		<!-- Adding graph -->
		<div class="component">			
			<h2>Visualisation</h2>
			<?php include("graph.php"); ?>
		</div>
</body>
</html>