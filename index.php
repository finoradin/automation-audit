<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Sorry, you have to log in.';
    exit;
} else {
    $binderUsername = $_SERVER['PHP_AUTH_USER'];
    $binderPassword = $_SERVER['PHP_AUTH_PW'];
    $context = stream_context_create(array(
    'http' => array(
        'header'  => "Authorization: Basic " . base64_encode("$binderUsername:$binderPassword")
    )
)); 

?>

<!--

I added a new column to transfers.db called "dateDeleted" – this is where the date the transfer is deleted gets recorded

... I just realized that I accidentally created it with integer datatype – sqlite3 doesn't let you drop columns, so I made a new one...

use "dateDeletedgood"

The user is recorded in "deletedBy"


current status:
• the mark as deleted button is updating the DB

todo:
• check if "datedeleted" is empty
• if no, display timestamp
• optimize page load time (currently all API calls happen before page load)


transfers.db "unit" table columns

0|id|INTEGER|1||1
1|uuid|VARCHAR(36)|0||0
2|path|BLOB|0||0
3|unit_type|VARCHAR(10)|0||0
4|status|VARCHAR(20)|0||0
5|microservice|VARCHAR(50)|0||0
6|current|BOOLEAN|0||0
7|dateDeleted|text|0||0
8|deletedBy|text|0||0

-->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>DRMC Automation-audit</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

	<style type="text/css">
		body { padding-top: 70px; }
		.label-as-badge {
    border-radius: 1em;
    font-size: 15px;
}
	</style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="#">DRMC automation-audit</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">choose db<span class="caret"></span></a>
				  <ul class="dropdown-menu" role="menu">
				  	<?php
						foreach (glob("transfers.db*") as $filename) {
						    echo "<li><a href='#'> $filename </a></li>";
						}
				  	?>
				  </ul>
				</li>
		      </ul>
		      <ul class="nav navbar-nav navbar-right">
		      	<li><a href=""><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="user"><?php echo " {$_SERVER['PHP_AUTH_USER']}"; ?></span></a></li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
	</nav>


<?php
	$db = new SQLite3('transfers.db');
	$query = $db->query('SELECT * FROM unit');
	echo '<table class="table table-striped">
		      <thead>
		        <tr>
		          <th>id</th>
		          <th>Path</th>
		          <th>Stage</th>
		          <th>Status</th>
		          <th>AIP UUID</th>
		          <th>Storage Service</th>
		          <th>Binder</th>
		          <th>Source deletion status</th>
		          <th></th>
		        </tr>
		      </thead>
		      <tbody>';

	while ($row = $query->fetchArray()) {
		$id = $row[0];
		$uuid = $row[1];
		$path = $row[2];
		$unitType = $row[3];
		$status = $row[4];
		$microservice = $row[5];
		$current = $row[6];
		$dateDeleted = $row[7];
		$deletedBy = $row[8];
		$rowcolor = "";
		$storageservice = "";
		$deletebutton = "";
		$ssgood = False;
		$bindergood = False;
		$binderstatus = "";
		if ($status == "FAILED" or $status == "REJECTED"){
			$rowcolor = "danger";
		};

		/* if uuid is not empty, ping SS API and Binder API  */
		if (strlen(trim($uuid)) > 0){
			$ssUrl = 'http://archivematica.museum.moma.org:8000/api/v2/file/'.$uuid.'/?format=json';
			$url_header = @get_headers($ssUrl);
			if ($url_header[0] == 'HTTP/1.1 200 OK') {
				$ssgood = True;
				$ssendpoint = file_get_contents($ssUrl);
				$jsonresult = json_decode($ssendpoint, true);
				$storageserviceuuid = $jsonresult['uuid'];
				if ($storageserviceuuid == $uuid){
					$storageservice = '<span class="label label-success label-as-badge"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></span>';
				};
			}
			else  {
				$ssgood = False;
				$storageservice = '<span class="label label-danger label-as-badge"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></span>';
			}
			$binderURL = 'http://drmc.museum.moma.org/api/aips/'.$uuid;
			$binderEndpoint = @file_get_contents($binderURL, false, $context);
			if ($binderEndpoint === FALSE) {
				$binderstatus = '<span class="label label-danger label-as-badge"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></span>';
				$bindergood = False;
				$rowcolor = "danger";
			}
			else {
				$binderjson = json_decode($binderEndpoint, true);
				$binderstatus = '<span class="label label-success label-as-badge"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></span>';
				$bindergood = True;
			}

		};

		if ($ssgood and $bindergood and $status != "FAILED" and strlen($dateDeleted) < 2){
			$deletebutton = '<div class="btn-group" role="group" aria-label="...">
	                        <button type="button" id="'.$uuid.'" class="btn btn-warning btn-xs rm">mark source as deleted <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
	                </div>';
		}
		elseif (strlen($dateDeleted) > 2) {
			$deletebutton = 'Deleted by '.$deletedBy." on ".$dateDeleted;
		};

		

	echo '<tr class="'.$rowcolor.'">
		<th>'.$id.'</th>
		<td>'.$path.'</td>
		<td>'.$unitType.'</td>
		<td>'.$status.'</td>
		<td>'.$uuid.'</td>
		<td>'.$storageservice.'</td>
		<td>'.$binderstatus.'</td>
		<td>'.$deletebutton.'</td>
	';



	};
}
?>


</body>
<script src="rm.js"></script>





