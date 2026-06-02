{% extends '@adm/events.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'adm.events.institution_create'|translate }}{% endblock %}
{% set target = target ?? '/adm/events/create-institution/' %}

{% block notes %}
    {% if errors %}
        {{ note("adm.error"|translate, 'error') }}
    {% endif %}
{% endblock %}


{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {{ f.select(
        name: 'seal',
        options: locations,
        value: institution.seal,
        label: 'adm.locations'|translate,
        errorMsg: errors.seal ?? ''
    ) }}

    {{ f.radio(
        name: 'type',
        options: types,
        value: institution.type,
        label: 'adm.events.institution.type'|translate,
        errorMsg: errors.type ?? ''
    ) }}

    {{ f.text(
        name: 'name',
        value: institution.name,
        label:'adm.events.institution.name'|translate,
        errorMsg: errors.name ?? ''
    ) }}

    {{ f.text(
        name: 'educator',
        value: institution.educator,
        label: 'adm.events.institution.educator'|translate,
        errorMsg: errors.educator ?? ''
    ) }}

    {{ f.text(
        name: 'email',
        value: institution.email,
        label: 'adm.events.institution.email'|translate,
        errorMsg: errors.email ?? ''
    ) }}

    {{ f.text(
        name: 'phone',
        value: institution.phone,
        label: 'adm.events.institution.phone'|translate,
        errorMsg: errors.phone ?? ''
    ) }}


    <span>
        {% if institution.model.modified or institution.model.created %}
            {% if institution.model.modified %}
                zu letzte bearbeitet {{ institution.model.modified|date('m.d.y - H:m') }}
            {% else %}
                Erstellt am {{ institution.model.created|date('m.d.y - H:m') }} {# Sprachvariable #}
            {% endif %}
        {% endif %}
    </span>

    {{ f.form_end('adm.form_submit'|translate, 'adm.form_reset'|translate) }}
{% endblock %}