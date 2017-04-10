

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="A mediacenter for home">
    <meta name="author" content="DangerBlack">
    <link rel="apple-touch-icon" sizes="120x120" href="css/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="css/icons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="css/icons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="css/icons/manifest.json">
    <link rel="mask-icon" href="css/icons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="css/icons/favicon.ico">
    <meta name="msapplication-config" content="css/icons/browserconfig.xml">
    <meta name="theme-color" content="#000000">

    <title>HomeFlix</title>

    <link rel="stylesheet" href="css/default.css" />
    <link rel="stylesheet" href="css/movie.css" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script type="text/javascript" src="js/default.js" ></script>
    <script type="text/javascript" >
        $(document).ready(function(){
            isLogged();
        });
    </script>
    <style>
        #transframe{
            width:100%;
            height:500px;
        }
    </style>
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid shorten">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">HomeFlix</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul id="movieFolder" class="nav navbar-nav">
            <li class="default"><a href="index.html" id="news">Home</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
			  <li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" >Utility <span class="caret"></span></a>
				  <ul class="dropdown-menu">
					  <li><a href="settings.html"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Settings</a></li>
				  </ul>
			  </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container-fluid shorten">

      <div class="starter-template">
        <h1 id="foldername">Download</h1>
        <?php
            require_once("php/config.php");
            $server = exec('curl http://ipecho.net/plain; echo');
            echo '<iframe id="transframe" src="http://'.$server.':'.$TRANSMISSION_PORT.'" ></iframe>';
        ?>
      </div>

    </div><!-- /.container -->
  </body>
</html>
