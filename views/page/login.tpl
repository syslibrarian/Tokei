{% extends "base.tpl" %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}

{% set intl_category %}tokei.main{% endset %}
{% block title %}{{ 'tokei.main.login'|translateFull }}{% endblock %}
{% block notes %}
    {% if errors %}
        {{ note("tokei.main.login_failed"|translateFull, 'error') }}
    {% endif %}
{% endblock %}

{% block page %}
    {{ f.form_start(uri: '/login', title: block('title'), html_classes: 'content') }}
        {{ f.text(
            name: 'username',
            error: errors.username
        ) }}

        {{ f.password(
            name: 'password',
            error: errors.password
        ) }}
    {{ f.form_end() }}
{% endblock %}
