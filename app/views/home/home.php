{% extends 'templates/foundation.php' %}

{% block content %}
  <div class="row">
    {% for movie in movies %}
      <div class="medium-3 columns">
            <div class="row">
              <div class="small-12 columns">
                <div class="thumbnail">
                    <img src="{{movie.image}}" alt="artwork" width="200" />
                </div>
              </div>
              <div class="small-12 columns">
                  <a href="{{ urlFor('view',{'id': movie.id } ) }}">{{movie.title}}</a>
              </div>
            </div>
      </div>
    {% endfor%}
  </div>
{% endblock %}
{% block js %}
{% endblock %}
