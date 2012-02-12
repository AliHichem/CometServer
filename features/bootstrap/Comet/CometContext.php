<?php

use Behat\Behat\Context\BehatContext;

class CometContext extends BehatContext
{

    public function __construct(array $parameters)
    {
        // do subcontext initialization
    }

    /**
     * @Given /^I am in the root server directory "([^"]*)"$/
     */
    public function iAmInTheRootServerDirectory($dir)
    {
        chdir(ROOT_COMET);
    }

    /**
     * @Given /^Comet file script "([^"]*)" is executable$/
     */
    public function cometFileScriptIsExecutable($script_name)
    {
        assertTrue(is_executable($script_name));
    }

    /**
     * @Given /^log folder "([^"]*)" is writable$/
     */
    public function logFolderIsWritable($log_folder)
    {
        $this->log_folder = $log_folder;
        assertTrue(is_writable($log_folder));
    }

    /**
     * @When /^I run "([^"]*)"$/
     */
    public function iRun($cmd)
    {
        exec($cmd . " > /dev/null &");
    }

    /**
     * @Then /^I should get a working server$/
     */
    public function iShouldGetAWorkingServer()
    {
        sleep(2); // wait for server to start
        assertTrue(file_exists(ROOT_COMET . "/{$this->log_folder}/comet/comet.pid"));
        assertTrue(file_exists(ROOT_COMET . "/{$this->log_folder}/comet/comet.sock"));
    }

    /**
     * @Then /^I should get a stopped server$/
     */
    public function iShouldGetAStoppedServer()
    {
        sleep(2); // wait for server to stop
        assertFalse(file_exists(ROOT_COMET . "/{$this->log_folder}/comet/comet.pid"));
        assertFalse(file_exists(ROOT_COMET . "/{$this->log_folder}/comet/comet.sock"));
    }

}