{% extends "@adm/index.tpl" %}
{% import "_tools.tpl" as t %}
{% import "_content.tpl" as c %}

{% if intl_category == '' %}
    {% set intl_category %}tokei.adm.reports{% endset %}
{% endif %}

{% block page_navigation %}
<nav class="adm-navigation">
    {{ _tokei.navigation_adm_reports|raw }}
</nav>
{% endblock %}

{% block notes %}
    {{ parent() }}
    {% if klrStatus == 'error' %}
        {{ note('tokei.adm.reports.klr_error'|translateFull, 'error') }}
    {% elseif klrStatus == 'success' %}
        {{ note('tokei.adm.reports.klr_success'|translateFull, 'success') }}
    {% endif %}
{% endblock %}

{% block content %}
    <header class="airy-header">
        <h1>
            {% block title %}
                {% if locations !== null %}
                    {{ 'title_year'|translate(year: year) }}
                {% else %}
                    {{ 'title_seal'|translate(name: location.name, year: year) }}
                {% endif %}
            {% endblock %}
        </h1>
    </header>

    {% if locations !== null %}
        {% for location in locations %}
            <div class="content reports">
                <header>
                    <h2>{{ 'for_location'|translate(name: location.name )}}</h2>
                </header>
                {{ c.reportList(attribute(reports, location.seal)) }}
            </div>
        {% endfor %}
    {% else %}
        <div class="content reports">
            {{ c.reportList(reports) }}
        </div>
    {% endif %}
{% endblock %}
