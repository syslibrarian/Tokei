{% extends "@adm/index.tpl" %}
{% set intl_category %}tokei.location{% endset %}

{% block content %}
    <header class="airyHeader">
        <h1>{% block title %}{{ location.name }}{% endblock %}</h1>
        <span>{{ 'title_addition'|translate(seal: location.seal, code: location.klr_code) }}</span>
    </header>

    <div class="content card">
        <header>
            <h2>{{ 'overview'|translate }}</h2>
        </header>
        <div class="address">
            <h3>{{ 'address'|translate }}</h3>
            {{ location.street }}<br>
            {{ location.postal_code }} {{ location.city }}
        </div>
        <div class="information">
            <h3>{{ 'base_information'|translate }}</h3>
            <ol>
                <li>{{ 'fte'|translate(fte: location.fte|number_format(2), consumed: location.fte_consumed|number_format(2)) }}
                <li>{{ 'area'|translate(area: location.area|number_format(2)) }}</li>
            </ol>
        </div>
    </div>

    <div class="content events">
        <header>
            <h2>{{ 'events_last'|translate }}</h2>
            <span>{{ 'events_list_information'|translate }}</span>
        </header>
        <ol>
            {% for event in events %}
                <li>{{ event.time_start|date }} {{ event.title }}</li>
            {% else %}
                <li>// Keine Events inen letzten 30 Tagen</li>
            {% endfor %}
        </ol>
    </div>

    <div class="content report">
        <h2>{{ 'report_last'|translate }}</h2>
        <ol>
            {% if lastReport %}
                <li><a href="/adm/reports/update/{{ lastReport.time_code }}/{{ lastReport.seal }}/">{{ lastReport.time_code }}</a></li>
            {% else %}
                <li>//Report noch nicht erstellt</li>
            {% endif %}
        </ol>
    </div>

    <div class="content reports">
        <h2>// Monatstatistikblätter (laufend Jahr)</h2>
        <ol>
            {% for report in reports %}
                <li><a href="/adm/reports/update/{{ report.time_code }}/{{ report.seal }}/">{{ report.time_code }}</a></li>
                {% else %}
                <li>//Reports noch nicht erstellt</li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}
