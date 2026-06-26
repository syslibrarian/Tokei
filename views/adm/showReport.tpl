{% extends "@adm/reports.tpl" %}
{% import "_tools.tpl" as tool %}
{% import "_content.tpl" as printer %}

{% import '_tools.tpl' as t %}
{% set intl_category %}tokei.location{% endset %}

{% block page_navigation %}
    <nav class="adm-navigation">
        {{ _tokei.navigation_adm_reports|raw }}
    </nav>
{% endblock %}

{% block content %}
    <header class="airy-header">
        <h1>
            {% block title %}{{ 'report_title'|translate(name: location.name, month: report.month, year: report.year) }}{% endblock %}
            {{ tool.inlineTools(report, getUri(true, false, uri: '/update/', timeCode: report.time_code, seal: report.seal)) }}
        </h1>
        <span>{{ 'title_addition'|translate(seal: location.seal, code: location.klr_code) }}</span>
    </header>

    <div class="content report">
        {{ printer.reportSheet(report) }}
    </div>
{% endblock %}

