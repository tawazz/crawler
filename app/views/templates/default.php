<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Tazzy Movie Crawler</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link href='//fonts.googleapis.com/css?family=Raleway:400,300,600' rel='stylesheet' type='text/css'>

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="{{baseUrl()}}/css/normalize.css">
  <link rel="stylesheet" href="{{baseUrl()}}/css/bootstrap.min.css">
  <!--link rel="stylesheet" href="{{baseUrl()}}/css/custom.css"-->
  <link rel="stylesheet" href="{{baseUrl()}}/css/style.css">
	{% block css %}{% endblock %}

  <!-- Scripts
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  {% include 'parts/scripts.php'%}

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <!--link rel="icon" type="image/png" href="../../dist/images/favicon.png"-->

</head>
<body>
  {% include 'parts/nav.php'%}
  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
		{% block content %}
		{% endblock %}
<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	{% block js %}
	{% endblock %}
</body>
</html>
