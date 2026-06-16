{% extends "@adm/reports.tpl" %}

{% block page_navigation %}
    <nav class="adm-navigation">
        {{ _tokei.navigation_adm_reports|raw }}
    </nav>
{% endblock %}

{% block content %}
    <div class="content">
        <header>
            {% if location %}
                <h1>{% block title %}//KLR Übersicht {% endblock %}</h1>
            {% else %}
                <h1></h1>
            {% endif %}
        </header>


        {% for month in months %}
            {{  }}
            <ol>
                <li>{{ month.month }}</li>
                <li>{{ month.seal }}</li>
                <li>Ausleihen: {{ month.circulations}}</li>
                <li>Besuche: {{ month.visits }}</li>
                <li>Teilnehmende {{ month.attendees }}</li>
                <li>status: {{ month.status }}</li>
            </ol>
        {% endfor %}

    </div>
{% endblock %}
