{% extends '@adm/createRole.tpl' %}
{% block title %}{{ role.name }} - {{ 'adm.role_update'|translate }}{% endblock %}
{% set target = '/adm/update-role/' ~ role.model.id %}
{% block notes %}
    {% if success %}
        {{ note("adm.success"|translate, 'success') }}
    {% endif %}
{% endblock %}