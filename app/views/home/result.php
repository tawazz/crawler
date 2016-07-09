{% extends 'templates/bootstrap.php' %}

{% block content %}
  <div class="white-space">
    <div class="card" style="padding:20px;">
      <img class="img-thumbnail center-block" style="padding:20px;" src="{{movie.image}}" alt="Card image cap">
      <div class="card-blockk">
          <h4 class="card-title" style="text-align:center;padding-top:10px;">{{movie.title}}</h4>
      </div>
    </div>
      <ul class="list-group">
        {% for link in links %}
            <a href="{{urlFor('download',{'id':link.id})}}" target="_blank"><li class="list-group-item">Episode {{link.title}} </li></a>
        {% endfor %}
      </ul>
  </div>
{% endblock %}
{% block js %}

{% endblock %}
