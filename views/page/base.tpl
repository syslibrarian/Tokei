{% import "_tools.tpl" as t %}

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title>{% block title %}{% endblock %}</title>
        <link rel="stylesheet" href="/style/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {% block meta %}{% endblock %}
    </head>
    <body>
        <div id="header">
            {% block header %}
                <header>
                    <div class="logo">
                        <!--<img src="/assets/images/logo.png" srcset="/assets/images/logo_2x.png 2x, /assets/images/logo.png 1x" alt="Logo">-->
                        <h1>Tokei - Entwicklungsdemo</h1>
                    </div>
                    {% block header_navigation %}
                        <nav>
                            {{ _tokei.navigation_header|raw }}
                        </nav>
                    {% endblock %}
                </header>
            {% endblock %}
        </div>
        <div id="page">
            {% block notes %}{% endblock %}
            {% block page %}{% endblock %}
        </div>
        <div id="footer">
            {% block footer %}
                <footer>
                    <section>© by Stadtbibliothek Tempelhof-Schöneberg</section>
                    {% block footer_navigation %}
                        <nav>
                            {{ _tokei.navigation_footer|raw }}
                        </nav>
                    {% endblock %}
                </footer>
            {% endblock %}
        </div>
    </body>
</html>