{% extends '@adm/index.tpl' %}
{% import '_tools.tpl' as t %}

{% block title %}{{ 'adm.list_role_title'|translate }}{% endblock %}

{% block content %}
    {% block pagination %}{{ pagination|raw }}{% endblock %}

    <div class="content dataList">
        <ol>
            {% for role in roles %}
                <li>
                    <section class="fieldSystem">
                        <span class="id">{{ role.id }}</span>
                    </section>
                    <section class="fieldTitle">
                        <h2><a href="/adm/update-role/{{ role.id }}">{{ role.name }}</a></h2>
                    </section>
                    <section class="fieldTools">
                        <a href="/adm/update-role/{{ role.id }}"><span class="edit"></span></a>
                        <a href="/adm/delete-role/{{ role.id }}"><span class="delete"></span></a>
                    </section>
                </li>
            {% endfor %}
        </ol>
    </div>
    {{ block('pagination') }}
{% endblock %}