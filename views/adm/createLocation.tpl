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
        name: 'zip_code',
        value: location.zip_code,
        label: "adm.location_zip_code",
        errorMsg: errors.zip_code ?? ''
    ) }}

    {{ f.number(
        name: "fte",
        value: location.fte,
        label: "adm.location_fte",
        placeholder: "0.0",
        step: "0.25"
    ) }}

    {{ f.number(
        name: "fte_consumed",
        value: location.fte_consumed,
        label: "adm.location_fte_consumed",
        placeholder: "0.0",
        step: "0.25"
    ) }}

    {{ f.number(
        name: "area",
        value: location.area,
        label: "adm.location_area",
        placeholder: "0.0",
        step: "0.25",
    ) }}

    {{ f.form_end('adm.form_submit'|translate, 'adm.form_reset'|translate) }}
{% endblock %}