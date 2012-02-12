Feature: Comet
  In order to communicate with the Comet server
  I need to be able to write/read/dump data from it

Scenario: Start server
  Given I am in the root server directory "CometServer"
  And   Comet file script "Comet" is executable
  And   log folder "log" is writable
  When  I run "./Comet start"
  Then  I should get a working server

Scenario: Stop server
  Given I am in the root server directory "CometServer"
  And   Comet file script "Comet" is executable
  And   log folder "log" is writable
  When  I run "./Comet stop"
  Then  I should get a stopped server
