{% macro eventReport(containers) %}
    <table class="events-table">
        <thead>
        <tr>
            <th></th>
            <th>{{ 'tokei.location.events_amount'|translateFull }}</th>
            <th>{{ 'tokei.location.events_attendees'|translateFull }}</th>
            <th>{{ 'tokei.location.events_hours'|translateFull }}</th>
            <th>{{ 'tokei.location.events_staff'|translateFull }}</th>
            <th>{{ 'tokei.location.events_staff_external'|translateFull }}</th>
            <th>{{ 'tokei.location.events_work_hours'|translateFull }}</th>
        </tr>
        </thead>
        <tbody>
        {% for container in containers %}
            <tr>
                <td><b>{{ container.name|translateFull }}</b></td>
                <td>{{ container.amount|number_format }}</td>
                <td>{{ container.attendees|number_format }}</td>
                <td>{{ container.hours|number_format(2) }}</td>
                <td>{{ container.workHours|number_format(2) }}</td>
                <td>{{ container.workHoursExternal|number_format(2) }}</td>
                <td>{{ (container.workHours + container.workHoursExternal)|number_format(2) }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endmacro%}

{% macro eventList(events) %}
    {% import '_tools.tpl' as tool %}
    <ol class="data-list">
        {% for event in events %}
            <li>
                <section class="title">
                    <h3>{% if event.is_education %}<span class="education"></span>{% endif %}{{ event.title }}</h3>
                    <p>{{ event.time_start|date('d.m.Y - H:i') }} {% if event.description %} - {{ event.description }}{% endif %}</p>
                </section>
                <section class="information">
                    <dl>
                        <dt>{{ 'tokei.location.events_hours'|translateFull }}</dt>
                        <dd>{{ event.hours|number_format(2) }}</dd>
                    </dl>
                    <dl>
                        <dt>{{ 'tokei.location.events_attendees'|translateFull }}</dt>
                        <dd>{{ event.attendees|number_format }}</dd>
                    </dl>
                </section>
                {{ tool.modelTools(
                    '',
                    '',
                    getUri(false, false, uri: 'adm/events/update/', id: event.id)
                ) }}
            </li>
        {% else %}
            <li>{{ 'no-events'|translateFull }}</li>
        {% endfor %}
    </ol>
{% endmacro%}

{% macro reportList(reports) %}
    {% import '_tools.tpl' as tool %}
    <ol class="data-list reports">
        {% for report in reports %}
            <li>
                <section class="title">
                    <h3>
                        <a href="{{ getUri(false, false, 'adm/reports/show-report', timeCode: report.time_code, seal: report.seal) }}">
                            {{ 'tokei.location.report_sheet'|translateFull(month: translateFull('tokei.month' ~ report.month)) }}
                        </a>
                        <span class="report-tool">
                            <a href="{{ getUri(false, false, 'adm/reports/close-report/', month: report.month, year: report.year) }}">
                                {% if report.report_status == 1 %}
                                    <span class="open"></span>
                                {% elseif report.report_status == 3 %}
                                    <span class="update"></span>
                                {% else %}
                                    <span class="close"></span>
                                {% endif %}
                            </a>
                        </span>
                    </h3>
                </section>
                <section class="information">
                    <dl>
                        <dt>{{ 'tokei.location.visits'|translateFull }}</dt>
                        <dd>{{ 'tokei.location.visits_definition_short'|translateFull(total: report.visits_total|number_format) }}</dd>
                    </dl>
                    <dl>
                        <dt>{{ 'tokei.location.circulations'|translateFull }}</dt>
                        <dd>{{ report.circulations|number_format }}</dd>
                    </dl>
                    <dl>
                        <dt>{{ 'tokei.location.media_packages'|translateFull }}</dt>
                        <dd>{{ report.media_packages|number_format }}</dd>
                    </dl>
                </section>
                {{ tool.modelTools('', '', getUri(false, false, uri: 'adm/reports/update/', timeCode: report.time_code, seal: report.seal)) }}
            </li>
        {% else %}
            <li>{{ 'tokei.location.no_reports'|translateFull }}</li>
        {% endfor %}
    </ol>
{% endmacro%}

{% macro reportSheet(report) %}
    {% import '_tools.tpl' as tool %}
    <section class="card">
        <h3>{{ 'tokei.location.report_information'|translateFull }}</h3>
        <dl>
            <dt>{{ 'tokei.location.visits'|translateFull }}</dt>
            <dd>{{ 'tokei.location.visits_definiton'|translateFull(total: report.visits_total|number_format, sensor: report.visits|number_format, manual: report.visits_manual|number_format) }}</dd>
            <dt>{{ 'tokei.location.circulations'|translateFull }}</dt>
            <dd>{{ report.circulations|number_format }}</dd>
            <dt>{{ 'tokei.location.media_packages'|translateFull }}</dt>
            <dd>{{ report.media_packages|number_format }}</dd>
        </dl>
    </section>

    <section class="card">
        <h3>{{ 'tokei.location.time_information'|translateFull }}</h3>
        <dl>
            <dt>{{ 'tokei.location.time_open'|translateFull }}</dt>
            <dd>{{ 'tokei.location.time_open_definition'|translateFull(hours: report.open_hours|number_format, open_library: report.open_library_hours|number_format) }}</dd>
        </dl>
        <dl>
            <dt>{{ 'tokei.location.shifts'|translateFull }}</dt>
            <dd>{{ 'tokei.location.shifts_definition'|translateFull(shifts: report.shifts|number_format, recieved: report.covers_recieved|number_format, given: report.covers_given|number_format) }}</dd>
        </dl>
    </section>

    <section class="card">
        <h3>{{ 'tokei.location.additonal_staff'|translateFull }}</h3>
        <dl>
            <dt>{{ 'tokei.location.staff_external'|translateFull }}</dt>
            <dd>{{ 'tokei.location.staff_definition'|translateFull(staff: report.staff_external|number_format, hours: report.staff_external_hours|number_format(2)) }}</dd>
        </dl>
        <dl>
            <dt>{{ 'tokei.location.staff_grant'|translateFull }}</dt>
            <dd>{{ 'tokei.location.staff_definition'|translateFull(staff: report.staff_grant|number_format, hours: report.staff_grant_hours|number_format(2)) }}</dd>
        </dl>
        <dl>
            <dt>{{ 'tokei.location.staff_volunteer'|translateFull }}</dt>
            <dd>{{ 'tokei.location.staff_definition'|translateFull(staff: report.staff_volunteer|number_format, hours: report.staff_volunteer_hours|number_format(2)) }}</dd>
        </dl>
    </section>

    <section class="card">
        <h3>{{ 'tokei.location.events_report'|translateFull }}</h3>
        {{ _self.eventReport(report.events.containers) }}
    </section>
{% endmacro%}