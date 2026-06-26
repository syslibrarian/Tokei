{% extends '@adm/index.tpl' %}
{% import '_tools.tpl' as t %}
{% import '_content.tpl' as printContent %}
{% set intl_category %}tokei.location{% endset %}

{% block content %}
    <header class="airy-header">
        <h1>{% block title %}{{ location.name }}{% endblock %}</h1>
        <span>{{ 'title_addition'|translate(seal: location.seal, code: location.klr_code) }}</span>
    </header>

    <div class="content overview">
        <header>
            <h2>{{ 'overview'|translate }}</h2>
        </header>
        <section class="card">
            <h3>{{ 'address'|translate }}</h3>
            {{ location.street }}<br>
            {{ location.postal_code }} {{ location.city }}
        </section>
        <section class="card">
            <h3>{{ 'base_information'|translate }}</h3>
            <dl>
                <dt>{{ 'fte'|translate }}</dt>
                <dd>{{ 'fte_definition'|translate(fte: location.fte|number_format(2), consumed: location.fte_consumed|number_format(2)) }}</dd>
            </dl>
            <dl>
                <dt>{{ 'area'|translate }}</dt>
                <dd>{{ 'area_definition'|translate(area: location.area|number_format(2)) }}</dd>
            </dl>
        </section>
    </div>

    <div class="content report">
        <header>
            <h2>{{ 'report_last'|translate }}{{ t.inlineTools(model, getUri(true, false, uri: '/reports/update/', timeCode: lastReport.time_code, seal: lastReport.seal)) }}</h2>
        </header>
        {{ printContent.reportSheet(lastReport) }}
    </div>

    <div class="content events">
        <header>
            <h2>{{ 'events_last'|translate }}</h2>
            <span>{{ 'events_list_information'|translate }}</span>
        </header>
        {{ printContent.eventList(events) }}
    </div>

    <div class="content reports">
        <header>
            <h2>{{ 'reports_for_location'|translate(year: _tokei.year )}}</h2>
        </header>
        {{ printContent.reportList(reports) }}
    </div>
{% endblock %}
