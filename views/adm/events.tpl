{% extends "@adm/index.tpl" %}
{% import '_tools.tpl' as t %}
{% import '_content.tpl' as c %}

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
        {{ c.eventList(events) }}

        {{ pagination|raw }}
    </div>
{% endblock %}