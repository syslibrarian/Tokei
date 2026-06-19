{% extends '@adm/index.tpl' %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'tokei.amd.location.locations_title'|translate }}{% endblock %}

{% block content %}
{% block pagination %}{{ pagination|raw }}{% endblock %}

<div class="content dataList">
    <ol>
        {% for location in locations %}
        <li>
            <section class="fieldSystem">
                <span class="id">{{ location.id }}</span>
            </section>
            <section class="fieldTitle">
                {# own location side with all informations #}
                <h2><a href="/adm/show-location/{{ location.seal }}">{{ location.name }} ({{ location.seal }})</a></h2>
            </section>
            <section class="fieldTools">
                <a href="/adm/update-location/{{ location.id }}"><span class="edit"></span></a>
                <a href="/adm/delete-location/{{ location.id }}"><span class="delete"></span></a>
            </section>
        </li>
        {% else %}
            <li><section class="fieldTitle">{{ "adm.no_entries"|translateFull }}</section></li>
        {% endfor %}
    </ol>
</div>
{{ block('pagination') }}
{% endblock %}