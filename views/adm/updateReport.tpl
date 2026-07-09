{% extends '@adm/reports.tpl' %}
{% import '_form.tpl' as f %}
{% import '_tools.tpl' as t %}
{% import '_content.tpl' as printContent %}

{% block title %}{{ 'update'|translate(name: location.name, year: report.model.year, month: report.model.month) }}{% endblock %}
{% set target = '/adm/reports/update/' ~ report.model.time_code ~ '/' ~ report.model.seal %}

{% block notes %}
    {% if errors %}
        {{ note("adm.error"|translateFull, 'error') }}
    {% endif %}
    {% if success %}
        {{ note("success"|translate, 'success') }}
    {% endif %}
{% endblock %}


{% block content %}
    {{ f.form_start(uri: target, title: block('title'), html_classes: 'content') }}

        {% set klrSection %}
            {{ f.number(
                name: 'circulations',
                value: report.circulations,
                label: 'adm.reports.circulations',
                description: 'adm.reports.circulations.description'
            ) }}

            {{ f.number(
                name: 'visits',
                value: report.visits,
                label: 'adm.reports.visits',
                description: 'adm.reports.visits.description'
            ) }}

            {{ f.number(
                name: 'visitsManual',
                value: report.visitsManual,
                label: 'adm.reports.visitsManual',
                description: 'adm.reports.visitsManual.description'
            ) }}
        {% endset %}

        {% set workSection %}
            {{ f.number(
                name: 'openHours',
                value: report.openHours,
                label: 'adm.reports.openHours',
                description: 'adm.reports.openHours.description'
            ) }}

            {{ f.number(
                name: 'openLibraryHours',
                value: report.openLibraryHours,
                label: 'adm.reports.openLibraryHours',
                description: 'adm.reports.openLibraryHours.description'
            ) }}

            {{ f.number(
                name: 'shifts',
                value: report.shifts,
                label: 'adm.reports.shifts',
                description: 'adm.reports.shifts.description'
            ) }}

            {{ f.number(
                name: 'coversReceived',
                value: report.coversReceived,
                label: 'adm.reports.coversReceived',
                description: 'adm.reports.coversReceived.description'
            ) }}

            {{ f.number(
                name: 'coversGiven',
                value: report.coversGiven,
                label: 'adm.reports.coversGiven',
                description: 'adm.reports.coversGiven.description'
            ) }}
        {% endset %}

        {% set externalSection %}
            {{ f.number(
                name: 'staffExternal',
                value: report.staffExternal,
             ) }}

            {{ f.number(
                name: 'staffExternalHours',
                value: report.staffExternalHours,
            ) }}

            {{ f.number(
                name: 'staffGrant',
                value: report.staffGrant,
            ) }}

            {{ f.number(
                name: 'staffGrantHours',
                value: report.staffGrantHours,
            ) }}

            {{ f.number(
                name: 'staffVolunteer',
                value: report.staffVolunteer,
            ) }}

            {{ f.number(
                name: 'staffVolunteerHours',
                value: report.staffVolunteerHours,
            ) }}
        {% endset %}

        {% set otherSection %}
            {{ f.number(
                name: 'mediaPackages',
                value: report.mediaPackages,
                label: 'adm.reports.mediaPackages',
                description: 'adm.reports.mediaPackages.description'
            ) }}
        {% endset %}

        {{ f.fieldset(
            'klr'|translate,
            klrSection
        ) }}

        {{ f.fieldset(
            'work'|translate,
            workSection
        ) }}

        {{ f.fieldset(
            'external'|translate,
            externalSection
        ) }}

        {{ f.fieldset(
            'other'|translate,
            otherSection
        ) }}

    {{ f.form_end() }}

    <div class="content events">
        <header>
            <h2>{{ 'tokei.location.events_report'|translateFull }}</h2>
        </header>
        {{ printContent.eventReport(report.model.events.containers) }}
    </div>
{% endblock %}