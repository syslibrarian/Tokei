<div class="note error">
    <span></span>
    <section class="errors">
        <p>{{ 'tokei.error.general'|translateFull }}</p>
        <ul>
        {% for field, rules in formErrors %}
            <li class="field">{{ field|translate }}
                <ol>
                    {% for rule in rules %}
                        <li>{{ rule|translateFull }}</li>
                    {% endfor %}
                </ol>
            </li>
        {% endfor %}
        </ul>
    </section>
</div>