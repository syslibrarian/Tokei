{% extends '@adm/index.tpl' %}
{% import '_tools.tpl' as t %}
{% set intl_category %}tokei.adm.user{% endset %}

{% block title %}{{ 'tokei.adm.list_roles'|translateFull }}{% endblock %}

{% block content %}
    <div class="content dataList">
        <ol>
            {% for user in users %}
                <li>
                    <section class="fieldSystem">
                        <span class="id">{{ user.id }}</span>
                    </section>
                    <section class="fieldTitle">
                        <h2><a href="/adm/update-user/{{ user.id }}">{{ user.username }}</a> ({{ user.role.name }})</h2>
                    </section>
                </li>
            {% else %}
                <li><section class="fieldTitle">{{ "adm.no_entries"|translateFull }}</section></li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}