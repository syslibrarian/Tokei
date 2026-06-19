{% extends '@adm/index.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}
{% set intl_category %}tokei.adm.location{% endset %}

{% block title %}{{ 'tokei.adm.location.create'|translateFull }}{% endblock %}
{% set target = target ?? '/adm/create-location/' %}

{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {{ f.text(
        name: "name",
        value: location.name,
        errorMsg: errors.name ?? ''
    ) }}

    {{ f.text(
        name: "seal",
        value: location.seal,
        errorMsg: errors.seal ?? ''
    ) }}

    {{ f.text(
        name: "klrCode",
        value: location.klrCode,
        errorMsg: errors.seal ?? ''
    ) }}

    {{ f.text(
        name: "street",
        value: location.street,
        errorMsg: errors.street ?? ''
    ) }}

    {{ f.text(
        name: "city",
        value: location.city,
        errorMsg: errors.city ?? ''
    ) }}

    {{ f.text(
        name: 'postal_code',
        value: location.postal_code,
        errorMsg: errors.postal_code ?? ''
    ) }}

    {{ f.number(
        name: "fte",
        value: location.fte,
        placeholder: "0.0",
        step: "0.25",
        suffix: 'fte_suffix'|translate
    ) }}

    {{ f.number(
        name: "fte_consumed",
        value: location.fte_consumed,
        placeholder: "0.0",
        step: "0.25",
        suffix: 'fte_suffix'|translate
    ) }}

    {{ f.number(
        name: "area",
        value: location.area,
        placeholder: "0.0",
        step: "0.25",
        suffix: "area_suffix"|translate
    ) }}

    {{ t.modelInfo(location.model) }}

    {{ f.form_end() }}
{% endblock %}