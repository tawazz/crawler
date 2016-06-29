{% extends 'templates/bootstrap.php' %}

{% block content %}
  <div class="row">
      <form class="form-inline" action="{{urlFor('post.add')}}" method="post">
        <div class="md-form form-group">
            <input placeholder="Paste 123Movies watch url" type="text" name="url" class="form-control">
            <label for="form5">Enter show/movie url</label>
        </div>
        <div class="md-form form-group">
          <input type="submit"  value="Add" class="btn btn-primary">
        </div>
      </form>
  </div>
{% endblock %}

{% block js %}
{% endblock %}
