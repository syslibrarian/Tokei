{% extends "@adm/index.tpl" %}
{% import '_tools.tpl' as t %}

{% if intl_category == '' %}
    {% set intl_category %}tokei.adm.events{% endset %}
{% endif %}

{% block content %}
    <div class="content dataList">
        <ol class="data-list">
            {% for template in templates %}
                <li>
                    <section class="title">
                        <h3>{{ template.name }}</h3>
                        <p>{% if template.description %}{{ template.description }}{% endif %}</p>
                    </section>
                    <section class="information">
                        <dl>
                            <dt>{{ 'template.length'|translateFull }}</dt>
                            <dd>{{ template.length|number_format }}</dd>
                        </dl>
                    </section>
                    {{ t.modelTools(template, 'adm') }}
                </li>
            {% else %}
                <li>{{ 'no-templates'|translateFull }}</li>
            {% endfor %}
        </ol>
    </div>
{% endblock %}