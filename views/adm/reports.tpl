{% extends "@adm/index.tpl" %}
{% set intl_category %}tokei.adm.reports{% endset %}

{% block page_navigation %}
<nav class="adm-navigation">
    {{ _tokei.navigation_adm_reports|raw }}
</nav>
{% endblock %}

{% block content %}
    <div class="content dataList">
        <header>
            {% if location %}
                <h1>{% block title %}{{ location.name }} ({{ location.seal }}) - {{ year }}{% endblock %}</h1>
            {% else %}
                <h1></h1>
            {% endif %}
        </header>
        <ol>
            {% for report in reports %}
                <li><a href="/adm/reports/update/{{ report.time_code }}/{{ report.seal }}/">{{ report.time_code }}</a></li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}
