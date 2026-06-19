{% extends '@adm/events.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}
{% set intl_category %}tokei.adm.institution{% endset %}

{% block title %}{{ 'tokei.adm.institution.create'|translateFull }}{% endblock %}
{% set target = target ?? '/adm/events/create-institution/' %}

{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {{ f.select(
        name: 'seal',
        options: locations,
        value: institution.seal,
        errorMsg: errors.seal ?? ''
    ) }}

    {{ f.radio(
        name: 'type',
        options: types,
        value: institution.type,
        errorMsg: errors.type ?? ''
    ) }}

    {{ f.text(
        name: 'name',
        value: institution.name,
        errorMsg: errors.name ?? ''
    ) }}

    {{ f.text(
        name: 'educator',
        value: institution.educator,
        errorMsg: errors.educator ?? ''
    ) }}

    {{ f.text(
        name: 'email',
        value: institution.email,
        errorMsg: errors.email ?? ''
    ) }}

    {{ f.text(
        name: 'phone',
        value: institution.phone,
        errorMsg: errors.phone ?? ''
    ) }}


    {{ t.modelInfo(institution.model) }}

    {{ f.form_end() }}
{% endblock %}