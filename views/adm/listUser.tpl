{% extends '@adm/index.tpl' %}
{% import '_tools.tpl' as t %}
{% set intl_category %}tokei.adm.user{% endset %}

{% block title %}{{ 'tokei.adm.list_roles'|translateFull }}{% endblock %}

{% block content %}
    <div class="content">
        <ol class="data-list">
            {% for user in users %}
                <li>
                    <section class="title">
                        <h2><a href="/adm/update-user/{{ user.id }}">{{ user.username }}</a> ({{ user.role.name }})</h2>
                    </section>
                    <section class="tools"></section>
                </li>
            {% else %}
                <li><section class="title">{{ "adm.no_entries"|translateFull }}</section></li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}