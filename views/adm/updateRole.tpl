{% extends '@adm/createRole.tpl' %}
{% block title %}{{ 'role_update'|translate(name: role.name) }}{% endblock %}
{% set target = '/adm/update-role/' ~ role.model.id %}