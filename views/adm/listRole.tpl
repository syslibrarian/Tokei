{% extends '@adm/index.tpl' %}
{% import '_tools.tpl' as t %}
{% set intl_category %}tokei.adm.role{% endset %}

{% block title %}{{ 'tokei.adm.list_roles'|translateFull }}{% endblock %}

{% block content %}
    <div class="content">
        <ol class="data-list">
            {% for role in roles %}
                <li>
                    <section class="title">
                        <h2><a href="/adm/update-role/{{ role.id }}">{{ role.name }}</a></h2>
                    </section>
                    <section class="tools">
                        <a href="/adm/update-role/{{ role.id }}"><span class="edit"></span></a>
                        <a href="/adm/delete-role/{{ role.id }}"><span class="delete"></span></a>
                    </section>
                </li>
            {% else %}
                <li><section class="fieldTitle">{{ "adm.no_entries"|translateFull }}</section></li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}