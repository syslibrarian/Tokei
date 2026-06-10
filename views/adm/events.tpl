{% extends "@adm/index.tpl" %}

{% block page_navigation %}
    <nav class="adm-navigation">
        {{ _tokei.navigation_adm_events|raw }}
    </nav>
{% endblock %}

{% block content %}
    <div class="content dataList">
        <h1>// Events</h1>


        <ol>
            {% for event in events %}
                <li>{{ event.title }} - {{ event.time_start|date }}  (// Stunden: {{ event.hours }} )</li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}