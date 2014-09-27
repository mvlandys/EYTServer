<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Games Database</title>

    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="/vendor/colorbox/colorbox.css" rel="stylesheet">

    <script src="/vendor/jquery/jquery-2.1.1.min.js" type="application/javascript"></script>
    <script src="/vendor/jquery-ui/jquery-ui.min.js" type="application/javascript"></script>
    <script src="/vendor/colorbox/jquery.colorbox-min.js" type="application/javascript"></script>
    <script src="/js/app.js" type="application/javascript"></script>
</head>
<body>
    @if (Session::has("user_id"))
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Games Database</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/vocab">Vocab Data</a></li>
                    <li><a href="/cardsort">CardSort Data</a></li>
                    <li><a href="/questionnaire">Questionnaire Data</a></li>
                    <li><a href="/mrant">MrAnt Data</a></li>
                    <li><a href="/fishshark">Fish Shark Data</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/logout">Logout</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->

        </div><!-- /.container-fluid -->
    </nav>
    @endif
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
