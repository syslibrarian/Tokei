{% extends '@adm/createLocation.tpl' %}
{% block title %}{{ location.name }} - {{ 'adm.location_update'|translate }}{% endblock %}
{% set target = '/adm/update-location/' ~ location.model.id %}
{% block notes %}
    {% if success %}
        {{ note("adm.success"|translate, 'success') }}
    {% endif %}
{% endblock %}