{% extends '@adm/events.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'event_create'|translate }}{% endblock %}
{% set target = target ?? '/adm/events/create/' %}

{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {% set generalSection %}
        {{ f.text(
            name: 'title',
            value: event.title,
            error: errors.title
        ) }}

        {{ f.text(
            name: 'description',
            value: event.description,
            error: errors.description
        ) }}

        {{ f.select(
            name: 'seal',
            options: locations,
            value: event.seal,
            error: errors.seal
        ) }}

        {{ f.date(
            name: 'startDateTime',
            value: event.startDateTime,
            time: true,
        ) }}

        {{ f.time(
            name: 'endTime',
            value: event.endTime,
        ) }}
    {% endset %}

    {% set informationSection %}
        {{ f.select(
            name: 'type',
            options: types,
            value: event.type,
        ) }}

        {{ f.radio(
            name: 'state',
            options: states,
            value: event.state,
        ) }}

        {{ f.radio(
            name: 'online',
            options: onlineStates,
            value: event.online,
        ) }}

        {{ f.select(
            name: 'audience',
            options: audiences,
            value: event.audience,
        ) }}
    {% endset %}

    {% set numberSection %}
        {{ f.number(
            name: 'staff',
            value: event.staff,
        ) }}

        {{ f.number(
            name: 'staffExternal',
            value: event.staff,
        ) }}

        {{ f.number(
            name: 'attendees',
            value: event.attendees,
        ) }}
    {% endset %}

    {{ f.fieldset(
        'event_general'|translate,
        generalSection
    ) }}

    {{ f.fieldset(
        'information'|translate,
        informationSection
    ) }}

    {{ f.fieldset(
        'statistic'|translate,
        numberSection
    ) }}

    {{ t.modelInfo(event.model) }}

    {{ f.form_end() }}
{% endblock %}