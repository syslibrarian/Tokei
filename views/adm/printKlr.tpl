<!DOCTYPE html>
<html lang="de">
    <head>
        <title>KLR-Print</title>
    </head>
    <body>
        <div>
                <header>
                    <h1>{{ "adm.reports.klr"|translateFull }} - {{ year }}</h1>
                </header>


                {% for month in months %}
                    <ol>
                        <li>{{ month.month }}</li>
                        <li>{{ month.seal }}</li>
                        <li>Ausleihen: {{ month.circulations}}</li>
                        <li>Besuche: {{ month.visits }}</li>
                        <li>Teilnehmende {{ month.attendees }}</li>
                        <li>status: {{ month.status }}</li>
                    </ol>
                {% endfor %}
        </div>
    </body>
</html>