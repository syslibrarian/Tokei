{% extends "@adm/index.tpl" %}

{% block page_navigation %}
    <nav class="adm-navigation">
        {{ _tokei.navigation_adm_events|raw }}
    </nav>
{% endblock %}