{% extends '@adm/createLocation.tpl' %}
{% block title %}{{ 'update'|translate(name: location.model.name) }}{% endblock %}
{% set target = '/adm/update-location/' ~ location.model.id %}