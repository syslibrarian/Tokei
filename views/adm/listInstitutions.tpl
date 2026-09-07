{% extends '@adm/events.tpl' %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'tokei.adm.events.list_institutions'|translateFull }}{% endblock %}

{% block content %}
    <div class="content">
        <ol class="data-list">
            {% for institution in institutions %}
                <li>
                    <section class="title">
                        {# own location side with all informations #}
                        <h2><a href="/adm/events/update-institution/{{ institution.id }}">{{ institution.name }} ({{ institution.educator }})</a></h2>
                    </section>
                    <section class="fieldText">{{ institution.seal }}</section>
                </li>
            {% else %}
                <li><section class="fieldTitle">{{ "adm.no_entries"|translate }}</section></li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}