{% extends "base.tpl" %}

{% import '_content.tpl' as c %}

{% block meta %}
    {{ parent() }}
    {{ translateBase(intl_category ?? 'tokei.adm') }}
{% endblock %}

{% block header_navigation %}
    <nav>
        {{ _page.navigation.adm_header|raw }}
    </nav>
{% endblock %}

{% block notes %}
    {% if _status.value == 'error' and _page.formErrors %}
        {% include '_errorNote.tpl' %}
    {% elseif _status.value == 'error' %}
        {{ note("tokei.adm.error"|translateFull, 'error') }}
    {% elseif _status.value == 'success' %}
        {{ note("tokei.adm.success"|translateFull, 'success') }}
    {% endif %}
{% endblock %}

{% block page %}
    <div class="adm-container">
        {% block page_navigation %}
            <nav class="adm-navigation">
                {{ _page.navigation.adm_section|raw }}
            </nav>
        {% endblock %}

        <div class="adm-content">
            {% block content %}
                <div class="content">
                    <h1>{{ 'tokei.adm.index'|translateFull }}</h1>
                    <h2>{{ 'tokei.adm.index_overview'|translateFull }}</h2>
                    // Was möchte die DB hier gerne sehen? Grafiken? Performance Angaben zum eigenen Standort?

                    // Vor dem Start für die Januar-Statistim 2027

                    <h2>{{ 'tokei.adm.index_events'|translateFull }}</h2>
                    {{ c.eventList(events) }}
                </div>
            {% endblock %}
            {% block pagination %}
                {{ pagination|raw }}
            {% endblock %}
        </div>
    </div>
{% endblock %}
