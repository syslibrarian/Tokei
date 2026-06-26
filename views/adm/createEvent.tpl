{% extends '@adm/events.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}

{% block title %}{{ ('tokei.adm.events.event_create_' ~ for)|translateFull }}{% endblock %}
{% set target = target ?? getUri('create') %}

{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {% set generalSection %}
        {% if isBase != true and for != 'event' %}
            {% set titleTranslate %}title_group{% endset %}
        {% endif %}

        {{ f.text(
            name: 'title',
            value: event.title,
            error: errors.title,
            list: isBase != true,
            forTranslate: titleTranslate
        ) }}

        {{ f.listFor('title', dataList) }}

        {{ f.text(
            name: 'description',
            value: event.description,
            error: errors.description
        ) }}

        {% if hiddenFields.seal %}
            {{ f.hiddenField(
                name: 'seal',
                value: hiddenFields.seal,
                show: location.name
            ) }}
        {% else %}
            {{ f.select(
                name: 'seal',
                options: locations,
                value: event.seal,
                error: errors.seal
            ) }}
        {% endif %}

        {{ f.date(
            name: 'startDateTime',
            value: event.startDateTime,
            time: true,
        ) }}

        {% if isBase %}
            {{ f.time(
                name: 'endTime',
                value: event.endTime
            ) }}
        {% else %}
            {{ f.select(
                name: 'endTime',
                options: timeFactors,
                value: event.endTime
            ) }}
        {% endif %}
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

        {% if hiddenFields.online %}
            {{ f.hidden(
                name: 'online',
                value: hiddenFields.online,
            ) }}
        {% else %}
            {{ f.radio(
                name: 'online',
                options: onlineStates,
                value: event.online,
            ) }}
        {% endif %}

        {% if hiddenFields.audience %}
            {{ f.hidden(
                name: 'audience',
                value: hiddenFields.audience,
            ) }}
        {% else %}
            {{ f.select(
                name: 'audience',
                options: audiences,
                value: event.audience,
            ) }}
        {% endif %}
    {% endset %}

    {% set numberSection %}
        {{ f.number(
            name: 'staff',
            value: event.staff,
        ) }}

        {{ f.number(
            name: 'staffExternal',
            value: event.staffExternal,
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