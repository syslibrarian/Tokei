{% extends "@adm/index.tpl" %}

{% block content %}
    <div class="content dataList">
        <header>
            {% if location %}
                <h1>{% block title %}{{ location.name }} ({{ location.seal }}){% endblock %}</h1>
            {% else %}
                <h1></h1>
            {% endif %}
        </header>
        <ol>
            <li>//Address: {{ location.street }}<br> {{ location.postal_code }} {{ location.city }}</li>
            <li>//Eckdaten: {{ location.fte }} //Stellenanteile - {{ location.fte_consumed }} //Stellenanteile besetzt</li>
            <li>//Eckdate: {{ location.area }} qm</li>
        </ol>

        <hr>
        <h2>//Veransltungen der letzten 30 Tage</h2>
        <ol>
            {% for event in events %}
                <li>{{ event.time_start|date }} {{ event.title }}</li>
            {% else %}
                <li>// Keine Events inen letzten 30 Tagen</li>
            {% endfor %}
        </ol>

        <hr>
        <h2>// Monatstatik (Vormonat)</h2>
        <ol>
            {% if lastReport %}
                <li><a href="/adm/reports/update/{{ lastReport.time_code }}/{{ lastReport.seal }}/">{{ lastReport.time_code }}</a></li>
            {% else %}
                <li>//Report noch nicht erstellt</li>
            {% endif %}
        </ol>

        <hr>
        <h2>// Monatstatistikblätter (laufend Jahr)</h2>
        <ol>
            {% for report in reports %}
                <li><a href="/adm/reports/update/{{ report.time_code }}/{{ report.seal }}/">{{ report.time_code }}</a></li>
                {% else %}
                <li>//Reports noch nicht erstellt</li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}
