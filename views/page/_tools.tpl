{% macro modelInfo(model) %}
    <span>
        {% if model.modified or model.created %}
            {% if model.modified %}
                {{ 'tokei.adm.modified'|translate(date: model.modified) }}
            {% elseif model.created %}
                {{ 'tokei.adm.ceated'|translate(date: model.created) }}
            {% endif %}
        {% endif %}
    </span>
{% endmacro %}