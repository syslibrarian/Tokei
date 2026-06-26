<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8"/>
        <title>{{ block('title') }}</title>
        <link rel="stylesheet" href="/style/print.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        {% block content %}
            <div class="content klr">
                <header>
                    <h1>{% block title %}{{ 'tokei.klr.title'|translateFull(year: year) }}{% endblock %}</h1>
                </header>

                {% for product in printer.products %}
                    <table class="klr-table">
                        <caption> {{ product.title|translateFull }}</caption>
                        <thead>
                        <tr>
                            <th></th>
                            {% for month in printer.months %}
                                <th>{{ month }}</th>
                            {% endfor %}
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            {% for location in product.locations %}
                                <td>{{ location.klrCode }}</td>
                                {% for month in location.data %}
                                    <td class="{% if month.marked() %}corrected{% elseif month.empty %}empty{% endif %}">
                                        {{ month.value }}
                                    </td>
                                {% endfor %}
                            {% endfor %}
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            {% for month in product.sum %}
                                <td>{{ month.value }}</td>
                            {% endfor %}
                        </tr>
                        </tfoot>
                    </table>
                {% endfor %}
            </div>
        {% endblock %}
    </body>
</html>