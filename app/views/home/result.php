{% extends 'templates/default.php' %}

{% block content %}
  <div class="white-space">

      {% for movie in movies %}
      <img src="{{movie.image}}" alt="artwork" />
      <h2>{{movie.title}} Links</h2>
        {% for info in movie.info %}
          {% if info.quality != null %}
            <li> <a href="{{info.file_url}}" url="{{info.file_url}}" target="_blank">{{info.title}} {{info.quality}}</a></li>
          {% endif %}
        {% endfor %}
      {% endfor %}
  </div>
{% endblock %}
{% block js %}

  <script type="text/javascript">
    var link = $('a[download]');
  </script>

{% endblock %}
