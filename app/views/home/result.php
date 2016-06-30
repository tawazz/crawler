{% extends 'templates/bootstrap.php' %}

{% block content %}
  <div class="white-space">
    <div class="card" style="padding:20px;">
      <img class="img-thumbnail center-block" style="padding:20px;" src="{{movie.image}}" alt="Card image cap">
      <div class="card-blockk">
          <h4 class="card-title" style="text-align:center;padding-top:10px;">{{movie.title}} Links</h4>
      </div>
    </div>
      <ul class="list-group">
        {% for link in links %}
          {% if link.quality != null %}
            <li class="list-group-item"> <a href="{{urlFor('download',{'id':link.id})}}" target="_blank">{{link.title}} {{link.quality}}</a></li>
          {% endif %}
        {% endfor %}
      </ul>
  </div>
{% endblock %}
{% block js %}

<script type="text/javascript">
var cookieName = '{{movie.ads_hash}}';
var cookieValue = '{{movie.ads_token}}';
var myDate = new Date();
myDate.setMonth(myDate.getDay() + 1);
if (document.cookie.indexOf(cookieName) >= 0) {
  // They've been here before.
}else{
  document.cookie = cookieName +"=" + cookieValue + ";expires=" + myDate
                    + ";domain='.123movies.to';path='/''";
}

</script>

{% endblock %}
