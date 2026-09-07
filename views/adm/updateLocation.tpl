{% extends '@adm/createLocation.tpl' %}
{% block title %}{{ 'upate'|translate(name: location.model.name) }}{% endblock %}
{% set target = '/adm/update-location/' ~ location.model.id %}