<?php

require_once dirname(__FILE__).'/../../model/DonationCanLogger.php';

/**
 * Test class for DonationCanDataExport.
 */
class DonationCanLoggerTest extends PHPUnit_Framework_TestCase {

    protected $object;
    protected $logFileName = "log";

    protected function setUp() {
        $this->object = new DonationCanLogger($this->logFileName);
    }

    protected function tearDown() {
    }



    public function testWriteToLog() {
        $this->object->write("Line to write to log");

        $file = fopen($this->logFileName, "r");
        $this->assertTrue($file !== false);

        // TODO: read line from log and verify it was written
        
    }
}
?>