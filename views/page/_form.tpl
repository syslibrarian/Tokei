{% macro text(name, value, label, description = '', placeholder = '', prefix = '', errorMsg = '') %}
    <div class="text{% if errorMsg != '' %} warning{% endif %}">
        <div class="label"><label for="{{ name }}-id">{{ label }}</label></div>
        <div class="field">
            {% if prefix != '' %}
                <span>{{ prefix }}</span>
            {% endif %}
            <input type="text" value="{{ value }}" placeholder="{{ placeholder }}" name="{{ name }}" id="{{ name }}-id">
            {% if errorMsg != '' %}
                <div class="warningOverlay">{{ errorMsg }}</div>
            {% endif %}
        </div>
        {% if description != '' %}
            <div class="description"><span>{{ description }}</span></div>
        {% endif %}
    </div>
{% endmacro %}

{% macro hidden(name, value) %}
    <input type="hidden" name="{{ name }}" value="{{ value }}">
{% endmacro %}

{% macro textarea(name, value, label, description = '', errorMsg = '') %}
    <div class="text{% if errorMsg != '' %} warning{% endif %}">
        <div class="label"><label for="{{ name }}-id">{{ label }}</label></div>
        <div class="field">
            <textarea name="{{ name }}" id="{{ name }}-id" rows="10">{{ value }}</textarea>
            {% if errorMsg != '' %}
                <div class="warningOverlay">{{ errorMsg }}</div>
            {% endif %}
        </div>
        {% if description != '' %}
            <div class="description"><span>{{ description }}</span></div>
        {% endif %}
    </div>
{% endmacro %}

{% macro checkbox(name, value, label, description = '', errorMsg = '') %}
    <div class="check">
        <div class="field"><label><input type="checkbox" value="1" name="{{ name }}" id="{{ name }}-id" {% if value %}checked{% endif %}> <span>{{ label }}</span></label></div>
        {% if description != '' %}
            <div class="description"><span>{{ description }}</span></div>
        {% endif %}
    </div>
{% endmacro %}

{% macro radio(name, options, value, label, description = '', errorMsg = '') %}
    <div class="radio">
        <div class="label">{{ label }}</div>
        <div class="field">
            {% for option in options %}
                {% if option.name and option.value %}
                    <label><input type="radio" name="{{ name }}" value="{{ option.value }}" {% if option.value == value %}checked{% endif %}> <span>{{ option.name }}</span></label>
                {% else %}
                    <label><input type="radio" name="{{ name }}" value="{{ option }}" {% if option == value %}checked{% endif %}> <span>{{ option }}</span></label>
                {% endif %}
            {% endfor %}
        </div>
        {% if description != '' %}
            <div class="description"><span>{{ description }}</span></div>
        {% endif %}
    </div>
{% endmacro %}

{% macro select(name, options, value, label, description = '', errorMsg = '') %}
    <div class="select">
        <div class="label"><label for="{{ name }}-id">{{ label }}</label></div>
        <div class="field">
            <select name="{{ name }}" id="{{ name }}-id">
                {% for option in options %}
                    {% if option is iterable %}
                        <option value="{{ option.value }}"{% if option.value == value %} selected{% endif %}>{{ option.name }}</option>
                    {% else %}
                        <option value="{{ option }}"{% if option == value %} selected{% endif %}>{{ option }}</option>
                     {% endif %}
                {% endfor %}
            </select>
        </div>
        {% if description != '' %}
            <div class="description"><span>{{ description }}</span></div>
        {% endif %}
    </div>
{% endmacro %}

{% macro passwort(name, label, description = '') %}
    <div class="text">
        <div class="label"><label for="{{ name }}-id">{{ label }}</label></div>
        <div class="field"><input type="password" name="{{ name }}" id="{{ name }}-id"></div>#
        {% if description != '' %}
            <div class="description"><span>{{ description }}</span></div>
        {% endif %}
    </div>
{% endmacro %}

{% macro number(name, value, label, description, min = 0, max = 0) %}
    <div class="number">
        <div class="label"><label for="{{ name }}-id">{{ label }}</label></div>
        <div class="field">
            <input
                type="number"
                value="{{ value }}"
                name="{{ name }}"
                id="{{ name }}-id"
                {% if max > 0 %}
                    min="{{ min }}"
                    max="{{ max }}"
                {% endif %}
            >
        </div>
        {% if description != '' %}
            <div class="description"><span>{{ description }}</span></div>
        {% endif %}
    </div>
{% endmacro %}

{% macro date(name, value, label, decription = '', time = false) %}
    <div class="date">
        <div class="label"><label for="{{ name }}-id">{{ label }}</label></div>
        <div class="field">
            <input
                type="{% if time == true %}datetime-local{% else %}date{% endif %}"
                value="{{ value }}"
                name="{{ name }}"
                id="{{ name }}-id"
            >
        </div>
        {% if description != '' %}
            <div class="description"><span>{{ description }}</span></div>
        {% endif %}
    </div>
{% endmacro %}

{% macro time(name, value, label, description = '') %}
    <div class="time">
        <div class="label"><label for="{{ name }}-id">{{ label }}</label></div>
        <div class="field">
            <input type="time" name="{{ name }}" id="{{ name }}-id">
        </div>
    </div>
    {% if description != '' %}
        <div class="description"><span>{{ description }}</span></div>
    {% endif %}
{% endmacro %}

{% macro hidden(name, value) %}
    <input type="hidden" name="{{ name }}" value="{{ value }}">
{% endmacro %}

{% macro fieldset(label, content) %}
    <fieldset>
        <legend>{{ label }}</legend>
        {{ content|raw }}
    </fieldset>
{% endmacro %}


{% macro form_buttons(label_submit, label_reset) %}
    <div class="buttons">
        <input type="submit" value="{{ label_submit }}">
        <input type="reset" value="{{ label_reset }}">
    </div>
{% endmacro %}

{% macro form_start(uri, method = 'POST', title = '', html_classes = '') %}
    <div class="formular {{ html_classes }}">
        <h1>{{ title }}</h1>
        <form method="{{ method }}" action="{{ uri }}">
{% endmacro %}

{% macro form_end(label_submit, label_reset) %}
            {{ _self.form_buttons(label_submit, label_reset) }}
        </form>
    </div>
{% endmacro %}
