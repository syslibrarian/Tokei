{% extends '@adm/events.tpl' %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'tokei.adm.events.list_institutions'|translateFull }}{% endblock %}

{% block content %}
    {% block pagination %}{{ pagination|raw }}{% endblock %}

    <div class="content dataList">
        <ol>
            {% for institution in institutions %}
                <li>
                    <section class="fieldSystem">
                        <span class="id">{{ institution.id }}</span>
                    </section>
                    <section class="fieldTitle">
                        {# own location side with all informations #}
                        <h2><a href="/adm/events/update-institution/{{ institution.id }}">{{ institution.name }} ({{ institution.educator }})</a></h2>
                    </section>
                    <section class="fieldText">{{ institution.seal }}</section>
                    <section class="fieldTools">
                        <a href="/adm/events/update-institution/{{ institution.id }}"><span class="edit"></span></a>
                        <a href="/adm/events/delete-institution/{{ institution.id }}"><span class="delete"></span></a>
                    </section>
                </li>
            {% else %}
                <li><section class="fieldTitle">{{ "adm.no_entries"|translate }}</section></li>
            {% endfor %}
        </ol>
    </div>
    {{ block('pagination') }}
{% endblock %}