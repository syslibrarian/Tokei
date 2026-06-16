{% extends "@adm/index.tpl" %}
{% import '_tools.tpl' as t %}

{% if intl_category == '' %}
    {% set intl_category %}tokei.adm.events{% endset %}
{% endif %}

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
                <li>
                    {{ event.title }} - {{ event.time_start|date }}  (// Stunden: {{ event.hours }} )
                    test
                    {{ t.links(event) }}
                </li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}