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