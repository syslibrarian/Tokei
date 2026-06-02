{% extends '@adm/createInstitution.tpl' %}
{% block title %}{{ institution.name }} - {{ 'adm.role_update'|translate }}{% endblock %}
{% set target = '/adm/events/update-institution/' ~ institution.model.id %}
{% block notes %}
    {% if success %}
        {{ note("adm.success"|translate, 'success') }}
    {% endif %}
{% endblock %}