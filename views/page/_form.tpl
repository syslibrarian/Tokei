{% macro text(name, value, placeholder = '', prefix = '', error = '') %}
    <div class="text{% if error != '' %} warning{% endif %}">
        <div class="label"><label for="{{ name }}-id">{{ name|translate }}</label></div>
        <div class="field">
            {% if prefix != '' %}
                <span>{{ prefix }}</span>
            {% endif %}
            <input type="text" value="{{ value }}" placeholder="{{ placeholder }}" name="{{ name }}" id="{{ name }}-id">
        </div>
        <div class="description"><span>{{ (name ~ '_desc')|translate }}</span></div>
    </div>
{% endmacro %}

{% macro hidden(name, value) %}
    <input type="hidden" name="{{ name }}" value="{{ value }}">
{% endmacro %}

{% macro textarea(name, value, error = '') %}
    <div class="text{% if error != '' %} warning{% endif %}">
        <div class="label"><label for="{{ name }}-id">{{ name|translate }}</label></div>
        <div class="field">
            <textarea name="{{ name }}" id="{{ name }}-id" rows="10">{{ value }}</textarea>
        </div>
        <div class="description"><span>{{ (name ~ '_desc')|translate }}</span></div>
    </div>
{% endmacro %}

{% macro checkbox(name, value, error = '') %}
    <div class="check{% if error != '' %} warning{% endif %}">
        <div class="field"><label><input type="checkbox" value="1" name="{{ name }}" id="{{ name }}-id" {% if value %}checked{% endif %}> <span>{{ name|translateFull }}</span></label></div>
        <div class="description"><span>{{ (name ~ '_desc')|translate }}</span></div>
    </div>
{% endmacro %}

{% macro radio(name, options, value, error = '') %}
    <div class="radio{% if error != '' %} warning{% endif %}">
        <div class="label">{{ name|translate }}</div>
        <div class="field">
            {% for option in options %}
                {% if option.name and option.value %}
                    <label><input type="radio" name="{{ name }}" value="{{ option.value }}" {% if option.value == value %}checked{% endif %}> <span>{{ option.name|translateFull }}</span></label>
                {% else %}
                    <label><input type="radio" name="{{ name }}" value="{{ option }}" {% if option == value %}checked{% endif %}> <span>{{ option }}</span></label>
                {% endif %}
            {% endfor %}
        </div>
        <div class="description"><span>{{ (name ~ '_desc')|translate }}</span></div>
    </div>
{% endmacro %}

{% macro select(name, options, value, error = '') %}
    <div class="select {% if error != '' %} warning{% endif %}">
        <div class="label"><label for="{{ name }}-id">{{ name|translate }}</label></div>
        <div class="field">
            <select name="{{ name }}" id="{{ name }}-id">
                {% for option in options %}
                    {% if option is iterable %}
                        <option value="{{ option.value }}"{% if option.value == value %} selected{% endif %}>{{ option.name|translateFull }}</option>
                    {% else %}
                        <option value="{{ option }}"{% if option == value %} selected{% endif %}>{{ option }}</option>
                     {% endif %}
                {% endfor %}
            </select>
        </div>
        <div class="description"><span>{{ (name ~ '_desc')|translate }}</span></div>
    </div>
{% endmacro %}

{% macro passwort(name, error = '') %}
    <div class="text {% if error != '' %} warning{% endif %}">
        <div class="label"><label for="{{ name }}-id">{{ name|translate }}</label></div>
        <div class="field"><input type="password" name="{{ name }}" id="{{ name }}-id"></div>#
        <div class="description"><span>{{ (name ~ '_desc')|translate }}</span></div>
    </div>
{% endmacro %}

{% macro number(name, value, placeholder='', min = 0, max = 0, step = 0, suffix = '', error = '') %}
    {# Add prefix and suffix for clear communication #}
    <div class="number{% if error != '' %} warning{% endif %}">
        <div class="label"><label for="{{ name }}-id">{{ name|translate }}</label></div>
        <div class="field">
            <input
                type="number"
                value="{{ value }}"
                name="{{ name }}"
                id="{{ name }}-id"
                {% if placeholder != '' %}
                    placeholder="{{ placeholder }}"
                {% endif %}
                {% if max > 0 %}
                    min="{{ min }}"
                    max="{{ max }}"
                {% endif %}
                {% if step > 0 %}
                    step="{{ step }}"
                {% endif %}
            >
            {% if suffix != '' %}
                <span>{{ suffix }}</span>
            {% endif %}
        </div>
        <div class="description"><span>{{ (name ~ '_desc')|translate }}</span></div>
    </div>
{% endmacro %}

{% macro date(name, value, time = false, error = '') %}
    <div class="date{% if error != '' %} warning{% endif %}">
        <div class="label"><label for="{{ name }}-id">{{ name|translate }}</label></div>
        <div class="field">
            <input
                type="{% if time == true %}datetime-local{% else %}date{% endif %}"
                value="{{ value }}"
                name="{{ name }}"
                id="{{ name }}-id"
            >
        </div>
        <div class="description"><span>{{ (name ~ '_desc')|translate }}</span></div>
    </div>
{% endmacro %}

{% macro time(name, value, error = '') %}
    <div class="time{% if error != '' %} warning{% endif %}">
        <div class="label"><label for="{{ name }}-id">{{ name|translate }}</label></div>
        <div class="field">
            <input type="time" name="{{ name }}" id="{{ name }}-id" value="{{ value }}">
        </div>
    </div>
    <div class="description"><span>{{ (name ~ '_desc')|translate }}</span></div>
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


{% macro form_buttons(label_submit = null, label_reset = null) %}
    <div class="buttons">
        <input type="submit" value="{{ label_submit ?? 'tokei.form.submit'|translateFull }}">
        <input type="reset" value="{{ label_reset ?? 'tokei.form.reset'|translateFull }}">
    </div>
{% endmacro %}

{% macro form_start(uri, method = 'POST', title = '', html_classes = '') %}
    <div class="formular {{ html_classes }}">
        <h1>{{ title }}</h1>
        <form method="{{ method }}" action="{{ uri }}">
{% endmacro %}

{% macro form_end(label_submit = null, label_reset = null) %}
            {{ _self.form_buttons(label_submit, label_reset) }}
        </form>
    </div>
{% endmacro %}
