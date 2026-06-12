{% extends '@adm/createEvent.tpl' %}
{% block title %}{{ 'update'|translate(title: event.model.title) }}{% endblock %}
{% set target = '/adm/events/update/' ~ event.model.id %}