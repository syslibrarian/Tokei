{% extends '@adm/index.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'adm.create_location'|translate }}{% endblock %}
{% set target = target ?? '/adm/create-location/' %}

{% block notes %}
    {% if errors %}
        {{ note("adm.error"|translate, 'error') }}
    {% endif %}
{% endblock %}


{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {{ f.text(
        name: "name",
        value: location.name,
        label: "adm.location_name",
        errorMsg: errors.name ?? ''
    ) }}

    {{ f.text(
        name: "seal",
        value: location.seal,
        label: "adm.location_seal",
        errorMsg: errors.seal ?? ''
    ) }}

    {{ f.text(
        name: "street",
        value: location.street,
        label: "adm.location_street",
        errorMsg: errors.street ?? ''
    ) }}

    {{ f.text(
        name: "city",
        value: location.city,
        label: "adm.location_city",
        errorMsg: errors.city ?? ''
    ) }}

    {{ f.text(
        name: 'postal_code',
        value: location.postal_code,
        label: "adm.location_postal_code",
        errorMsg: errors.postal_code ?? ''
    ) }}

    {{ f.number(
        name: "fte",
        value: location.fte,
        label: "adm.location_fte",
        placeholder: "0.0",
        step: "0.25",
        suffix: 'adm.location_fte'
    ) }}

    {{ f.number(
        name: "fte_consumed",
        value: location.fte_consumed,
        label: "adm.location_fte_consumed",
        placeholder: "0.0",
        step: "0.25",
        suffix: 'adm.location_fte'
    ) }}

    {{ f.number(
        name: "area",
        value: location.area,
        label: "adm.location_area",
        placeholder: "0.0",
        step: "0.25",
        suffix: "m²"
    ) }}

    <span> {# todo: relocated maybe in base template #}
        {% if location.model.modified or location.model.created %}
            {% if location.model.modified %}
                zu letzte bearbeitet {{ location.model.modified|date('m.d.y - H:m') }}
            {% else %}
                Erstellt am {{ location.model.created|date('m.d.y - H:m') }} {# Sprachvariable #}
            {% endif %}
        {% endif %}
    </span>

    {{ f.form_end('adm.form_submit'|translate, 'adm.form_reset'|translate) }}
{% endblock %}