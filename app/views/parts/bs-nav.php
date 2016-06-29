<nav class="navbar navbar-dark primary-color-dark">

    <!-- Collapse button-->
    <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#collapseEx">
        <i class="fa fa-bars"></i>
    </button>

    <div class="container">

        <!--Collapse content-->
        <div class="collapse navbar-toggleable-xs" id="collapseEx">
            <!--Navbar Brand-->
            <a class="navbar-brand" href="{{ urlFor('home')}}">Crawler</a>
            <!--Links-->
            <ul class="nav navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ urlFor('home')}}">Library <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ urlFor('add')}}">Add Movies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ urlFor('about')}}">About</a>
                </li>
            </ul>
            <!--Search form-->
            <form class="form-inline">
                <input class="form-control" type="text" placeholder="Search">
            </form>
        </div>
        <!--/.Collapse content-->

    </div>
</nav>
