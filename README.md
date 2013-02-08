WordPress Email Signup
======================

A WordPress plugin that provides a simple email signup widget that can be sent to a number of third-party services, including CheetahMail and ExactTarget. Provides a framework that allows you to easily create addition third-party connectors.

Template Overrides
------------------

This plugin looks in your them folder for a file called _email-signup.widget.html.php_, and will use that code as the widget template.

Included Services
-----------------

1. CheetahMail
2. ExactTarget

Creating New Services
---------------------

Create a new file in _lib/services_ that contains a class that extends the base service class.