{% extends '@adm/index.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}
{% set intl_category %}tokei.adm.role{% endset %}

{% block title %}{{ 'tokei.adm.role.create'|translateFull }}{% endblock %}
{% set target = target ?? '/adm/create-role/' %}

{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {{ f.text(
        'name',
        role.name,
        errorMsg: errors.name ?? ''
    ) }}

    {% for group in permissionGroups  %}
        {% set permissions_form %}
            {% for permission in group.permissions %}
                {{ f.checkbox(
                    permission.name,
                    permission.value,
                ) }}
            {% endfor %}
        {% endset %}

        {{ f.fieldset(group.name, permissions_form) }}
    {% endfor %}

    {{ t.modelInfo(role.model) }}

    {{ f.form_end() }}
{% endblock %}