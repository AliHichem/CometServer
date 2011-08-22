CometServer: allows a web server to push data to a browser
==========================================================

Comet application (also known as "Push server") written in php, using Ajax with the [long polling](http://en.wikipedia.org/wiki/Push_technology#Long_polling) technology 
which is the most popular technique in the [Comet Programming](http://en.wikipedia.org/wiki/Comet_%28programming%29). It provides a simple way to allow http server to
push data to browser without using the classic [Periodic refresh](http://ajaxpatterns.org/Periodic_Refresh) approach.

CometServer includes tow main components:

 * Comet server (or Push server):
  
  Based on AF_UNIX socket for local communication protocol family and [InterProcess Communication](http://en.wikipedia.org/wiki/Inter-process_communication), the server 
is designed to transcend the remote socket limitation that require to allocate port on server which is not allowed 
by many web hosting service.

 * Comet client:

  Tool to make communication easier between server daemon and http server.

Requirements
-----------

 * Operating system: Unix-like
 * php version 5.0 or higher  
 * php libraries : posix, pcntl, xml
 * php command language interface 
 * jQuery FW if you will use the native Comet helper "CometWebJsHelper.class.php".

Installation
-----------

download application

    $ git clone git://github.com/AliHichem/CometServer.git CometServer

add execution to "Comet"

    $ chmod a+x CometServer/Comet

make "CometServer" folder writable 

    $ chmod a+w -R CometServer

Usage
-----

Available commands are : start/stop/status/write/read/dump/help
use help to get more informations.
 
start server

    $ ./Comet start

depending on what you want to do with your CometServer, you may have to include CometServer classes to your 
web project:

    <?php require_once "path_to_lib_folder_of_cometserver/lib/CometAutoloader.class.php"; ?>

Exemples
-------

There is already a good example under "examples" that demonstrate how to communicate with CometServer from your 
Http server, see jGrowl_notifier.
