{% extends '@adm/createEvent.tpl' %}
{% block title %}{{ event.model.title }} - {{ 'adm.event_update'|translate }}{% endblock %}
{% set target = '/adm/events/update/' ~ event.model.id %}