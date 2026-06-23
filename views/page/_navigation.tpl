<ol>
    {% for item in navigation.items %}
            {% if item.is_active and hasPermission(item.needed_permission) %}
                <li>
                        <a href="{{ item.target }}"
                            {% if item.target == activeTarget %}class="active"{% endif %}>
                            {{ item.name|translateFull }}
                        </a>
                </li>
            {% endif %}
    {% endfor %}
</ol>