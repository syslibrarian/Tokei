{% extends '@adm/createInstitution.tpl' %}
{% block title %}{{ 'institution_update'|translate(name: institution.model.name) }}{% endblock %}
{% set target = '/adm/events/update-institution/' ~ institution.model.id %}
{% block notes %}
    {% if success %}
        {{ note("adm.success"|translate, 'success') }}
    {% endif %}
{% endblock %}