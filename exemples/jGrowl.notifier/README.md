CometServer: jGrowl notifier
============================

jGrowl notifier allows to add a real time notification system in your web application. Using CometServer, users 
can get a notification in real time without need to refresh the web page. It is little bit similar to the famous 
FaceBook notification system.

Notification messages can contain a raw html data, when pushing/changing messages in the CometServer, the system
will send a notification message to users.

Every message is specified by a unique key. the key can be a string, numeric or a numeric array, note that associative
arrays are not supported. In this example, key is an array: "array('ali.hichem@mail.com')"


Installation
-----------

Install and start CometServer as described in the main README.md file.
If you move the "jGrowl.notifier" folder to an other location (under your root http directory for example), you 
will have to set the appropriate path of the autoloader "CometAutoloader.class.php" in "index.php","read.php" 
and "write.php".

Usage
-----

Assuming that you place "jGrowl.notifier" under the root http folder, you will visit the index page with:

    http://localhost/jGrowl.notifier/

Keep this page opened and Open another windows in order to keep the first main window visible so you can see 
the notification.

    http://localhost/jGrowl.notifier/write.php

Click on submit to send the default message and see the changes in the first main window: A jGrowl message will
appear.

Refresh link can be used to force system to push the last message without sending form the form.
