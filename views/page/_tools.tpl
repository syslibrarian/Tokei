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

{% macro links(model, suffix = '', withBase = true, withCurrent = false) %}
    test
    {% if suffix != '' %}{% set suffix %}{{ suffix }}-{% endset %}{% endif %}
    <section class="fieldTools">
        <a href="{{ getUri(withBase, withCurrent, suffix ~ 'update', id:model.id) }}"><span class="edit"></span></a>
        <a href="{{ getUri(withBase, withCurrent, suffix ~ 'delete', id:model.id) }}"><span class="delete"></span></a>
    </section>
{% endmacro %}