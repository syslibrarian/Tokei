{% extends "base.tpl" %}

{% block meta %}
    {{ parent() }}
    {{ translateBase(intl_category ?? 'tokei.adm') }}
{% endblock %}

{% block header_navigation %}
    <nav>
        {{ _tokei.navigation_adm_header|raw }}
    </nav>
{% endblock %}

{% block notes %}
    {% if errors %}
        {{ note("tokei.adm.error"|translateFull, 'error') }}
    {% endif %}
    {% if success %}
        {{ note("tokei.adm.success"|translateFull, 'success') }}
    {% endif %}
{% endblock %}

{% block page %}
    <div class="adm-container">
        {% block page_navigation %}
            <nav class="adm-navigation">
                {{ _tokei.navigation_adm_general|raw }}
            </nav>
        {% endblock %}

        <div class="adm-content">
            {% block content %}
                <div class="content">
                    <h1>{{ 'tokei.adm.index'|translateFull }}</h1>
                </div>
            {% endblock %}
            {% block pagination %}
                {{ pagination|raw }}
            {% endblock %}
        </div>
    </div>
{% endblock %}
