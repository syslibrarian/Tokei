{% extends '@adm/createTemplate.tpl' %}
{% block title %}{{ 'tokei.adm.events.template_update'|translateFull(title: event.model.name) }}{% endblock %}
{% set target = getUri(template.model, 'adm', 'update') %}