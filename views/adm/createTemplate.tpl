{% extends '@adm/events.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}

{% block title %}{{ ('tokei.adm.events.event_template_create')|translateFull }}{% endblock %}
{% set target = target ?? getUri(template.modelClass, 'adm', 'create') %}

{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

        {{ f.text(
            name: 'name',
            value: template.name,
            forTranslate: titleTranslate
        ) }}

        {{ f.text(
            name: 'description',
            value: template.description,
        ) }}

        {{ f.number(
            name: 'length',
            value: template.length,
        ) }}

    {{ f.form_end() }}
{% endblock %}