{% extends '@adm/createRole.tpl' %}
{% block title %}{{ 'update'|translate(name: role.name) }}{% endblock %}
{% set target = '/adm/update-role/' ~ role.model.id %}