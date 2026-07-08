{% extends '@adm/index.tpl' %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'tokei.amd.location.locations_title'|translate }}{% endblock %}

{% block content %}
    <div class="content">
        <ol class="data-list">
            {% for location in locations %}
            <li>
                <section class="title">
                    {# own location side with all informations #}
                    <h2><a href="/adm/show-location/{{ location.seal }}">{{ location.name }} ({{ location.seal }})</a></h2>
                </section>
                <section class="tools model-tools">
                    <a href="/adm/update-location/{{ location.id }}"><span class="update"></span></a>
                </section>
            </li>
            {% else %}
                <li><section class="title">{{ "adm.no_entries"|translateFull }}</section></li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}