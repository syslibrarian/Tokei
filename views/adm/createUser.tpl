{% extends '@adm/index.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}
{% set intl_category %}tokei.adm.user{% endset %}

{% block title %}{{ 'tokei.adm.user.create'|translateFull }}{% endblock %}
{% set target = target ?? '/adm/create-user/' %}

{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

    {{ f.text(
        name: 'username',
        value: user.username,
    ) }}

    {{ f.text(
        name: 'email',
        value: user.email,
    ) }}

    {{ f.text(
        name: 'name',
        value: user.name,
    ) }}

    {{ f.text(
        name: 'surname',
        value: user.surname,
    ) }}

    {{ f.select(
        name: 'seal',
        options: locations,
        value: user.seal
    ) }}

    {{ f.select(
        name: 'role',
        options: roles,
        value: user.role_id
    )}}

    {% if user.change_password is defined %}
        {{ f.checkbox(
            name: 'change_password',
            value: user.change_password
        ) }}
    {% endif %}

    {{ f.password(
        name: 'password',
    ) }}

    {{
        f.password(
            name: 'passwordRepeat',
        )
    }}

    {{ f.form_end() }}
{% endblock %}