{% macro modelInfo(model) %}
    <span>
        {% if model.modified or model.created %}
            {# System workds best in UTC #}
            {% if model.modified %}
                {{ 'tokei.adm.modified'|translateFull(date: model.modified|dateLong)) }}
            {% elseif model.created %}
                {{ 'tokei.adm.created'|translateFull(date: model.created|dateLong)) }}
            {% endif %}
        {% endif %}
    </span>
{% endmacro %}

{% macro modelTools(model, context, textUpdate = '', textDelte = '') %}
    <section class="model-tools">
        <a href="{{ getUri(model, context, 'update') }}"><span class="update"></span></a>
        {# <a href="{{ getUri(withBase, withCurrent, uri,  suffix: suffix ~ 'delete', id:model.id) }}"><span class="delete">1</span></a> #}
    </section>
{% endmacro %}

{% macro inlineTools(model, context, hasClose = false) %}
    <span class="inline-tools">
        <a href="{{ getUri(model, context, 'update') }}"><span class="update"></span></a>
        {# here more work for inlinetools
            <span class="close"></span>
        #}
    </span>
{% endmacro %}