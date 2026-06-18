{% macro modelInfo(model) %}
    <span>
        {% if model.modified or model.created %}
            {% if model.modified %}
                {{ 'tokei.adm.modified'|translateFull(date: model.modified|date('d.m.Y - h:i')) }}
            {% elseif model.created %}
                {{ 'tokei.adm.created'|translateFull(date: model.created|date('d.m.Y - h:i')) }}
            {% endif %}
        {% endif %}
    </span>
{% endmacro %}

{% macro modelTools(textUpdate = '', textDelte = '', updateUri = '', deleteUri = '') %}
    <section class="model-tools">
        <a href="{{ updateUri }}"><span class="update"></span></a>
        {# <a href="{{ getUri(withBase, withCurrent, uri,  suffix: suffix ~ 'delete', id:model.id) }}"><span class="delete">1</span></a> #}
    </section>
{% endmacro %}

{% macro inlineTools(model, uri = '', hasClose = false) %}
    <span class="inline-tools">
        <a href="{{ uri }}"><span class="update"></span></a>
        {# here more work for inlinetools
            <span class="close"></span>
        #}
    </span>
{% endmacro %}