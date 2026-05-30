{% extends '@adm/index.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'adm.create_role_title'|translate }}{% endblock %}
{% set target = target ?? '/adm/create-role/' %}

{% block notes %}
    {% if errors %}
        {{ note("adm.error"|translate, 'error') }}
    {% endif %}
{% endblock %}


{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {{ f.text(
        'name',
        role.name,
        'adm.role_name',
        errorMsg: errors.name ?? ''
    ) }}

    {% for group in permissionGroups  %}
        {% set permissions_form %}
            {% for permission in group.permissions %}
                {{ f.checkbox(
                    permission.name,
                    permission.value,
                    'adm.role_permission_' ~ permission.name
                ) }}
            {% endfor %}
        {% endset %}

        {{ f.fieldset(group.name, permissions_form) }}
    {% endfor %}

    {{ f.form_end('adm.form_submit'|translate, 'adm.form_reset'|translate) }}
{% endblock %}