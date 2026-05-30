<ol>
    {% for item in navigation.items %}
            {% if item.is_active %}
                <li>
                        <a href="{{ item.target }}"
                            {% if item.target == activeTarget %}class="active"{% endif %}>
                            {{ item.name }}
                        </a>
                </li>
            {% endif %}
    {% endfor %}
</ol>