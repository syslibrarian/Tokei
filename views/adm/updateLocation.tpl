{% extends '@adm/createLocation.tpl' %}
{% block title %}{{ 'upate'|translate(name: location.model.name) }}{% endblock %}
{% set target = '/adm/update-location/' ~ location.model.id %}
{% block notes %}
    {% if success %}
        {{ note("adm.success"|translate, 'success') }}
    {% endif %}
{% endblock %}