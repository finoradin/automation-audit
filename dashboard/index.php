<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MoMA DRMC dashboard</title>


    <!--

        pseudocode for DRMC dashboard

        pipeline widget (every minute do this)
            open connection to sqlite db
            what is the current unit's UUID?
            what is the current unit's path?
                get the object ID, component number, and component ID
            talk to Archivematica API
                what microservice is it currently on?
            talk to TMS API object endpoint
                what is the title?
                who is the artist?
            talk to TMS API component endpoint
                what is the component name?

-->




    <!-- Bootstrap Core CSS -->
<!--     <link href="css/bootstrap.min.css" rel="stylesheet">
 -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

 
    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
 
<body>

<?php

$object_endpoint = 'http://vmsqlsvcs.museum.moma.org/TMSAPI/TmsObjectSvc/TmsObjects.svc/GetTombstoneDataRest/Object/';

?>

    <div id="wrapper">

<br>

        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-4">
                    <div class="panel panel-primary">


        <?php

        $user = 'bfino';
        $apiKey = '47a5b3791ef9dad6bdfaf56fe27ff78b71c857cb';

        $objectNumber = '560.1984'; 

        $url = file_get_contents($object_endpoint.$objectNumber);
        $json = json_decode($url, true);
        $title = $json["GetTombstoneDataRestResult"]["Title"];
        $artist = $json["GetTombstoneDataRestResult"]["AlphaSort"];
        $objectnum = $json["GetTombstoneDataRestResult"]["ObjectNumber"];
        $objectid = $json["GetTombstoneDataRestResult"]["ObjectID"];
        
        ?>



                        <div class="panel-heading">
                            Pipeline #1
                        </div>
                        <div class="panel-body">
                            <div class="media">
                              <div class="media-left">
                                <a href="#">
                                  <img class="media-object" src="http://vmsqlsvcs.museum.moma.org/TMSImages/Size3/Images/33059_idemitsu.jpg" height="200px">
                                </a>
                              </div>
                              <div class="media-body">
                                <h4 class="media-heading"><?php echo $title ?></h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                              </div>
                            </div>
                            <br>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                <span class="sr-only">45% Complete</span>
                              </div>
                            </div>
                            Microservice 27 of 45    
                        </div>
                        <div class="panel-footer">
                            storage.museum.moma.org/drmc/collection-materials/ready-for-ingest-bagged
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Pipeline #2
                        </div>
                        <div class="panel-body">
                            <div class="media">
                              <div class="media-left">
                                <a href="#">
                                  <img class="media-object" src="http://vmsqlsvcs.museum.moma.org/TMSImages/Size3/Images/CT43_Zando.jpg" height="200px">
                                </a>
                              </div>
                              <div class="media-body">
                                <h4 class="media-heading">496.2011.x2</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                              </div>
                            </div>
                            <br>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                <span class="sr-only">45% Complete</span>
                              </div>
                            </div>
                            Microservice 22 of 45                            
                        </div>
                        <div class="panel-footer">
                            storage.museum.moma.org/drmc/collection-materials/ready-for-ingest-unbagged
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Pipeline #3
                        </div>
                        <div class="panel-body">
                            <div class="media">
                              <div class="media-left">
                                <a href="#">
                                  <img class="media-object" src="http://vmsqlsvcs.museum.moma.org/TMSImages/Size3/Images/487.1985_Laub.jpg.jpg" height="200px">
                                </a>
                              </div>
                              <div class="media-body">
                                <h4 class="media-heading">487.1985.x3</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                              </div>
                            </div>
                            <br>
                            <div class="progress">
                              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 25%">
                                <span class="sr-only">45% Complete</span>
                              </div>
                            </div>        
                            Microservice 12 of 45                      
                        </div>
                        <div class="panel-footer">
                            isilon-nfs.museum.moma.org:/ifs/drmc-staging/read-for-ingest
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-4 -->
            </div>
   
            <div class="row">
                <div class="col-lg-6">
                <iframe src="http://archivematica.museum.moma.org/automation-audit/metrics" width="100%" height="500px"></iframe>

                </div>

                                <div class="col-lg-6">
                <iframe src="http://archivematica.museum.moma.org/automation-audit/size.php" width="100%" height="500px"></iframe>

                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Storage stats
                        </div>
                        <div class="panel-body">
                            Microservice 12 of 45                      
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    asdf
                </div>
                <div class="col-md-2">
                    asdf
                </div>
                <div class="col-md-2">
                    asdf
                </div>
            </div>
      
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript --> 
    <script src="js/sb-admin-2.js"></script>

</body>

</html>
