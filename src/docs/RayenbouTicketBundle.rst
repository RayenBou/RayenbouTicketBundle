RayenbouTicketBundle
====================

This project provides a Symfony integration for a ticketing system, allowing for the fetching of individual tickets and lists of tickets from a specified API. It utilizes the Symfony HttpClient component to communicate with the API and handles authentication through bearer tokens with JWT Token.

This project exists to be used with `Dashboard Bundle <https://github.com/RayenBou/RayenbouDashboardBundle>`_.

The DashboardBundle part can be installed on your app, while the TicketBundle part can be installed on any other app.

This documentation provides a step-by-step guide to setting up the ticket environment for your project.

Right now, the project is in Alpha and currently doesn't have any recipe, but it might change soon.

How to use it
-------------

1. Register `TICKET_URL`, `TICKET_USERNAME`, and `TICKET_PASSWORD` in your .Env (create a username and password with the `Dashboard Bundle <https://github.com/RayenBou/RayenbouTicketBundle>`_).
2. Go to `/ticket/`.
3. Send a ticket, answer through a messenger-like conversation.
4. If the ticket is closed, you can no longer answer it.

Initial Setup
-------------

1. **Composer**

   .. code-block:: bash

      composer require rayenbou/dashboard-bundle

   An error might occur at the cache:clear saying "authentication is missing". Don't worry about this, you just have to follow the Parameter setting. Currently working on the recipe to avoid this problem.

2. **Parameter Settings**

   In `config/packages/rayenbou_ticket.yaml`:

   .. code-block:: yaml

      rayenbou_ticket:
           authentication:
               url: '%env(TICKET_URL)%'
               username: '%env(TICKET_USERNAME)%'
               password: '%env(TICKET_PASSWORD)%'
           settings:
               verify_peer: false

   In `config/routes/rayenbou_ticket.yaml`:

   .. code-block:: yaml

      rayenbou_ticket:
           resource: "@RayenbouTicketBundle/Resources/config/routing.yaml"

   In `.env`:

   .. code-block:: env

      TICKET_URL=
      TICKET_USERNAME=
      TICKET_PASSWORD=

Dev Environment
---------------

The key `verify_peer` under setting in the `config/packages/rayenbou_ticket.yaml` is default to `false` to work on a self-validated TLS environment, feel free to deactivate it if you work in another environment.

Tests
-----

Unit tests and Integration tests are on their way.

Evolution
---------

1. Possibility to override all templates and Controller.

Contributing
------------

Contributions to this project are welcome. Please ensure to follow the existing coding style and add unit tests for any new or changed functionality.

Please use `PHPstan` and `PHP-CS-FIXER`.

License
-------

This project is licensed under the MIT License - see the LICENSE file for details.