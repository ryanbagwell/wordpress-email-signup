WordPress Email Signup
======================

A flexible WordPress plugin that provides a simple email signup widget that can be integrated with a number of third-party services, including CheetahMail, ExactTarget and Listrack. Provides a framework that allows you to easily create addition third-party connectors.

Compatible third-party e-mail services
--------------------------------------

The plugin works with the following third-party services:

1. CheetahMail
2. ExactTarget
3. Listrack

Other connections with third-party services can be added by creating a new file in _lib/services_ that contains a class that extends the base service class.

Template Overrides
------------------

By default, this plugin uses a generic form for the widget HTML. To override it, create a new file with your custom HTML called _email-signup.widget.html.php_. in your theme folder.




