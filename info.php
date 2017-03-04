<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A mediacenter for home">
    <meta name="author" content="DangerBlack">
    <link rel="icon" href="../../favicon.ico">

    <title>HomeFlix</title>

    <link rel="stylesheet" href="css/default.css" />
    <link rel="stylesheet" href="css/info.css" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/default.js" ></script>
    <script type="text/javascript" src="js/info.js" ></script>
    <script type="text/javascript" >
        $(document).ready(function(){
            isLogged();
            loadInfo("<?php echo $_GET['movie'];?>");
        });
    </script>
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
          <ul class="nav navbar-nav">
            <li><a href="index.html">Home</a></li>
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
          <div class="row">
              <div class="col-md-4 description">
                  <h1 id="title">Title</h1>
                  <p>
                      <span id="stars">
                          <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                          <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                          <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                          <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                          <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                      </span>
                      <span id="year">year</span> - <span id="runtime">?h ??m</span>
                      <article id="overview">
                          <p >A lot of interesting stuff!</p>
                      </article>
                      <p id="favorite"><span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span> Preferiti</p>
                      <p><a href="#" class="youtube"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span> TRAILER</a></p>
                      <p><a href="#" class="details"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> DETAILS</a></p>
                      <p><a href="#" class="comment addComment" data-toggle="modal" data-target="#myModal" data-whatever="@Commento"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> COMMENTS</a></p>

                  </p>

              </div>
              <div class="col-md-8 bgwide" style="background-image:url('archive/<?php echo $_GET['movie'];?>.jpg')">
                <p class="full"><a id="watch" href="#"><span class="play glyphicon glyphicon-play-circle"></span></a></p>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <h3 id="lastseen">Last seen from:</h3>
                  <ul id="watched"></ul>
              </div>
          </div>
          <div class="row">
              <div id="postlist" class="col-md-12">
              </div>
          </div>
      </div>

    </div><!-- /.container -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button id="send" type="button" class="btn btn-primary">Send</button>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
