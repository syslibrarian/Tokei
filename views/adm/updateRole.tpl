{% extends '@adm/createRole.tpl' %}
{% block title %}{{ 'role_update'|translate(name: role.name) }}{% endblock %}
{% set target = '/adm/update-role/' ~ role.model.id %}
{% block notes %}
    {% if success %}
        {{ note("adm.success"|translate, 'success') }}
    {% endif %}
{% endblock %}