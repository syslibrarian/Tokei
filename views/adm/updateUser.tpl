{% extends '@adm/createUser.tpl' %}
{% block title %}{{ 'user_update'|translate(name: user.namename) }}{% endblock %}
{% set target = '/adm/update-user/' ~ user.model.id %}
