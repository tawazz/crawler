<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
      <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <title>Movie Crawler</title>

      {% include 'parts/css.php'%}

</head>

<body>

    <header>
    <!--Navbar-->
    {% include 'parts/bs-nav.php'%}
    <!--/.Navbar-->
    </header>

    <main>
        <!--Main layout-->
          <div class="container">
            {% block content %}
            {% endblock %}
          </div>
        <!--/.Main layout-->
    </main>

    <!--Footer-->
    {% include 'parts/footer.php' %}
    <!--/.Footer-->

    <!-- SCRIPTS -->
    {% include 'parts/scripts.php' %}
    {% block js %}
    {% endblock %}

</body>

</html>
