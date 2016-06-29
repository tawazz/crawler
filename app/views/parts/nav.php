<div class="row">
  <nav class="navbar">
    <div class="container">
      <div class="medium-6 columns">
        <nav>
          <ul style="padding:10px">
            <li><a href="{{urlFor('home')}}">Library</a></li>
            <li><a href="#">Add Movie</a></li>
            <li><a href="#">About</a></li>
          </ul>
      </nav>
      </div>
      <div class="medium-6 columns">
        <form class="" action="/search" method="post" style="padding:10px">
          <div class="input-group">
            <input class="input-group-field" type="text" name="search" value="" placeholder="search..." autocomplete="off">
            <div class="input-group-button">
                <input type="submit"  value="Search" class="button" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </nav>
</div>
