{% extends 'templates/bootstrap.php' %}

{% block content %}
  <div class="row">
    {% for movie in movies %}
      {% if loop.index % 4 != 0 %}
      <div class="col-sm-3">
        <div class="card">
          <!--Card image-->
          <a href="{{ urlFor('view',{'id': movie.id } ) }}"><img class="img-fluid" src="{{movie.image}}"  width="100%" alt="Card image cap"/></a>
          <!--/.Card image-->

          <!--Card content-->
          <div class="card-block">
              <!--Title-->
                <a href="{{ urlFor('view',{'id': movie.id } ) }}" ><h4 class="card-title" style="max-height:48px;min-height:48px; text-overflow:epilips;">{{movie.title}}</h4></a>
          </div>
          <!--/.Card content-->
        </div>
      </div>
      {% else %}
        <div class="row">
          <div class="col-sm-3">
            <div class="card">
              <!--Card image-->
              <a href="{{ urlFor('view',{'id': movie.id } ) }}"><img class="img-fluid" src="{{movie.image}}"  width="100%" alt="Card image cap"/></a>
              <!--/.Card image-->

              <!--Card content-->
              <div class="card-block">
                  <!--Title-->
                    <a href="{{ urlFor('view',{'id': movie.id } ) }}" ><h4 class="card-title" style="max-height:48px;min-height:48px; text-overflow:epilips;">{{movie.title}}</h4></a>
              </div>
              <!--/.Card content-->
            </div>
          </div>
        </div>
      {% endif %}
    {% endfor%}
  </div>
{% endblock %}
{% block js %}
{% endblock %}
