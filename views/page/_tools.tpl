{% macro doPagination(url, currentPageNumber, maxPageNumber) %}
    {% if maxPageNumber > 1 %}
        <div class="pagination">
            <ol>
                {% if currentPageNumber > 1 %}
                    <li><a href="{{ url }}/1">First</a></li>
                {% endif %}>
                {% if (currentPageNumber - 1) > 1 %}
                    <li><a href="{{ url }}/{{ currentPageNumber - 1 }}">Previous</a></li>
                {% endif %}
                    <li>{{ currentPageNumber }}</li>
                {% if (currentPageNumber + 1) < maxPageNumber %}
                    <li><a href="{{ url }}/{{ currentPageNumber + 1 }}">Next</a></li>
                {% endif %}
                {% if currentPageNumber < maxPageNumber %}
                    <li><a href="{{ url }}/{{ maxPageNumber }}">Last</a></li>
                {% endif %}
            </ol>
        </div>
    {% endif %}
{% endmacro %}