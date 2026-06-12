{% if pagination.pages > 1 %}
    <div class="pagination">
        <ol>
            {% if pagination.pageNo > 1 %}
                <li><a href="{{ pagination.getUri(1) }}">{{ 'tokei.pagination_first'|translateFull }}</a></li>
            {% endif %}
            {% if (pagination.pageNo - 1) > 1 %}
                <li><a href="{{ pagination.getUri(pagination.pageNo - 1) }}">{{ 'tokei.pagination_previous'|translateFull }}</a></li>
            {% endif %}
            <li>{{ pagination.pageNo }}</li>
            {% if (pagination.pageNo + 1) < pagination.pages %}
                <li><a href="{{ pagination.getUri(pagination.pageNo + 1) }}">{{ 'tokei.pagination_next'|translateFull }}</a></li>
            {% endif %}
            {% if pagination.pageNo < pagination.pages %}
                <li><a href="{{ pagination.getUri(pagination.pages + 1) }}">{{ 'tokei.pagination_last'|translateFull }}</a></li>
            {% endif %}
        </ol>
    </div>
{% endif %}