{% extends '@adm/events.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'adm.events.evemt_create'|translate }}{% endblock %}
{% set target = target ?? '/adm/events/create/' %}

{% block notes %}
    {% if errors %}
        {{ note("adm.error"|translate, 'error') }}
    {% endif %}
    {% if success %}
        {{ note("adm.success"|translate, 'success') }}
    {% endif %}
{% endblock %}


{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {% set generalSection %}
        {{ f.text(
            name: 'title',
            value: event.title,
            label: 'adm.events.event_title'|translate,
            errorMsg: errors.title
        ) }}

        {{ f.text(
            name: 'description',
            value: event.description,
            label: 'adm.events.event_description'|translate,
            errorMsg: errors.description
        ) }}

        {{ f.select(
            name: 'seal',
            options: locations,
            value: event.seal,
            label: 'adm.events.event_seal'|translate,
            errorMss: errors.seal
        ) }}
    {% endset %}

    {% set informationSection %}
        {{ f.select(
            name: 'type',
            options: types,
            value: event.type,
            label: 'adm.events.event_types'|translate,
            description: 'adm.events.event_types_description'
        ) }}

        {{ f.radio(
            name: 'state',
            options: states,
            value: event.state,
            label: 'adm.events.event_state'|translate,
            description: 'adm.events.event_state_description'
        ) }}

        {{ f.radio(
            name: 'online',
            options: onlineStates,
            value: event.online,
            label: 'adm.events.event_online'|translate,
            description: 'adm.events.event_online_description'
        ) }}
    {% endset %}

    {% set numberSection %}
        {{ f.date(
            name: 'startDateTime',
            value: event.startDateTime,
            label: 'adm.events.event_start_time'|translate,
            description: 'adm.events.event_start_time_description'|translate,
            time: true
        ) }}

        {{ f.time(
            name: 'endTime',
            value: event.endTime,
            label: 'adm.events.event_end_time'|translate,
            description: 'adm.events.event_end_time_description'|translate
        ) }}

        {{ f.number(
            name: 'staff',
            value: event.staff,
            label: 'adm.events.event_staff'|translate,
            description: 'adm.events.event_staff_description'|translate
        ) }}

        {{ f.number(
            name: 'staffExternal',
            value: event.staff,
            label: 'adm.events.event_staff_external'|translate,
            description: 'adm.events.event_staff_external_description'|translate
        ) }}

        {{ f.number(
            name: 'attendees',
            value: event.attendees,
            label: 'adm.events.event_attendees_external'|translate,
            description: 'adm.events.event_attendees_external_description'|translate
        ) }}
    {% endset %}

    {{ f.fieldset(
        'adm.events.event_general'|translate,
        generalSection
    ) }}

    {{ f.fieldset(
        'adm.events.event_information'|translate,
        informationSection
    ) }}

    {{ f.fieldset(
        'adm.events.event_information'|translate,
        numberSection
    ) }}

    {{ f.form_end('adm.form_submit'|translate, 'adm.form_reset'|translate) }}
{% endblock %}