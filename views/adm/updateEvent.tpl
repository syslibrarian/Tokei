{% extends '@adm/createEvent.tpl' %}
{% block title %}{{ 'tokei.adm.events.update'|translateFull(title: event.model.title) }}{% endblock %}
{% set target = '/adm/events/update/' ~ event.model.id %}