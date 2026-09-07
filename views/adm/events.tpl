{% extends "@adm/index.tpl" %}
{% import '_tools.tpl' as t %}
{% import '_content.tpl' as c %}

{% if intl_category == '' %}
    {% set intl_category %}tokei.adm.events{% endset %}
{% endif %}

{% block content %}
    <div class="content dataList">
        {{ c.eventList(events) }}
    </div>
{% endblock %}