{% extends "base.tpl" %}

{% block header_navigation %}
    <nav>
        {{ _tokei.navigation_adm_header|raw }}
    </nav>
{% endblock %}

{% block page %}
    <div class="adm-container">
        {% block page_navigation %}
            <nav class="adm-navigation">
                {{ _tokei.navigation_adm_general|raw }}
            </nav>
        {% endblock %}

        <div>
        {% block content %}
            <div class="content">
                <h1>Übersicht</h1>
            </div>
        {% endblock %}
        </div>
    </div>
{% endblock %}