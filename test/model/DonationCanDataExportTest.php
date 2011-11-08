<?php

require_once dirname(__FILE__).'/../../model/DonationCanDataExport.php';
require_once dirname(__FILE__).'/../../model/settings/donation_can_general_settings.php';
require_once "MockOptions.php";

/**
 * Test class for DonationCanDataExport.
 */
class DonationCanDataExportTest extends PHPUnit_Framework_TestCase {

    protected $object;
    protected $mockOptions;
    protected $generalSettings;

    protected function setUp() {
        $this->mockOptions = new MockOptions();

        // Create some test data
        $test_cause = array(
            "id" => "test-cause",
            "name" => "Test Cause",
            "description" => "Description for cause",
            "donation_goal" => 1000,
            "currency" => "USD",
            "return_page" => "",
            "cancelled_return_page" => "",
            "continue_button_text" => "Go back!",
            "notify_email" => "email@email.com,email2@email.com",
            "donation_sums" => array("1", "5", "10"),
            "allow_freeform_donation_sums" => 1
        );

        $test_cause_2 = array(
            "id" => "test-cause-2",
            "name" => "Test Cause 2",
            "description" => "Description for cause",
            "donation_goal" => 1000,
            "currency" => "CAD",
            "return_page" => 1,
            "cancelled_return_page" => 5,
            "continue_button_text" => "Go back!",
            "notify_email" => "email@email.com,email2@email.com",
            "donation_sums" => null,
            "allow_freeform_donation_sums" => 0
        );

        $causes = array(
            "test-cause" => $test_cause,
            "test-cause-2" => $test_cause_2
        );

        $this->mockOptions->update_option("donation_can_causes", $causes);


        // Set up donation can general settings
        $settings = new DonationCanGeneralSettings($this->mockOptions);

        $settings->setPayPalEmail("test-user@gmail.com");
        $settings->setPayPalSandboxEmail("sandbox-test-user@gmail.com");
        $settings->setAskForNote(true);
        $settings->setNoteFieldLabel("Leave a note!");
        $settings->setRequireShipping(2);
        $settings->setReturnPage(10);
        $settings->setCancelledPage(20);
        $settings->setContinueButtonText("Continue.");
        $settings->setLogoOnPayPalPage("http://someurl.com/image.png");
        $settings->setHeaderOnPayPalPage("http://someotherurl.com/image.png");
        $settings->setBackgroundOnPayPalPage("#FFFFFF");
        $settings->setHeaderBackgroundOnPayPalPage("#EEEEEE");
        $settings->setHeaderBorderOnPayPalPage("#333300");
        $settings->setNotifyEmail("test-user@gmail.com");
        $settings->setStyle("something here?");
        $settings->setCustom("something here too?");
        $settings->setDefaultCurrency("JPY");
        $settings->setDebugMode(true);
        $settings->setLoggingMode(false);
        $settings->setShowBackLink(true);
        $settings->setSubtractPayPalFees(false);
        $settings->setEmailFrom("some-email@mail.com", "Test User");
        $settings->setEmailTemplate("Hello, world!");
        $settings->setReceiptSubject("Hi there...");
        $settings->setReceiptTemplate("Thanks for your donation!");
        $settings->setSendReceipt(true);
        $settings->setReceiptThreshold(50);
        $settings->setUseHTMLEmails(true);
        $settings->setSortCausesField("name");
        $settings->setSortCausesOrder("DESC");
        $settings->setSortDonationsField("time");
        $settings->setSortDonationsOrder("ASC");
        $settings->addDonationOption(1);
        $settings->addDonationOption(5);
        $settings->addDonationOption(10);

        $settings->save();

        $this->generalSettings = $settings;
        $this->object = new DonationCanDataExport($this->mockOptions);
    }

    protected function tearDown() {
    }

    public function testExportCausesAsCSV() {
        // CSV FORMAT:
        // cause_code,name,description,goal,currency,return_page,cancelled_page,continue_text,notify_email,donation_options,allow_freeform

        $result = $this->object->exportCauses("CSV", "|");

        $expected_csv = "test-cause|Test Cause|Description for cause|1000|USD|||Go back!|email@email.com,email2@email.com|1,5,10|1\n"
            . "test-cause-2|Test Cause 2|Description for cause|1000|CAD|1|5|Go back!|email@email.com,email2@email.com||0\n";

        $this->assertEquals($expected_csv, $result);
    }

    public function testExportCausesAsXML() {
        $expected_xml =
"<cause id=\"test-cause\">
\t<name>Test Cause</name>
\t<description>Description for cause</description>
\t<goal>1000</goal>
\t<currency>USD</currency>
\t<return_page></return_page>
\t<cancelled_page></cancelled_page>
\t<continue_link_text>Go back!</continue_link_text>
\t<notify_email>
\t\t<email>email@email.com</email>
\t\t<email>email2@email.com</email>
\t</notify_email>
\t<donation_options>
\t\t<option>1</option>
\t\t<option>5</option>
\t\t<option>10</option>
\t</donation_options>
\t<allow_freeform_donation_sums>1</allow_freeform_donation_sums>
</cause>
<cause id=\"test-cause-2\">
\t<name>Test Cause 2</name>
\t<description>Description for cause</description>
\t<goal>1000</goal>
\t<currency>CAD</currency>
\t<return_page>1</return_page>
\t<cancelled_page>5</cancelled_page>
\t<continue_link_text>Go back!</continue_link_text>
\t<notify_email>
\t\t<email>email@email.com</email>
\t\t<email>email2@email.com</email>
\t</notify_email>
\t<donation_options></donation_options>
\t<allow_freeform_donation_sums>0</allow_freeform_donation_sums>
</cause>
";

        $result = $this->object->exportCauses("XML");

        $this->assertEquals($expected_xml, $result);
    }

    function testExportGeneralSettings() {
        $settings = $this->object->exportSettings("|");

        // Settings are returned in a CSV format as key-value pairs,
        // sorted alphabetically by key
        $expected =
              "ask_for_note|1\n"
            . "bg_on_paypal_page|#FFFFFF\n"
            . "cancel_return_page|20\n"
            . "continue_button_text|Continue.\n"
            . "currency|JPY\n"
            . "custom|something here too?\n"
            . "debug_mode|1\n"
            . "donation_sums|1,5,10\n" // comma separated list
            . "email_from|some-email@mail.com\n"
            . "email_from_name|Test User\n"
            . "email_template|Hello, world!\n"
            . "enable_logging|\n"
            . "header_bg_on_paypal_page|#EEEEEE\n"
            . "header_border_on_paypal_page|#333300\n"
            . "header_on_paypal_page|http://someotherurl.com/image.png\n"
            . "link_back|1\n"
            . "logo_on_paypal_page|http://someurl.com/image.png\n"
            . "note_field_label|Leave a note!\n"
            . "notify_email|test-user@gmail.com\n"
            . "paypal_email|test-user@gmail.com\n"
            . "paypal_sandbox_email|sandbox-test-user@gmail.com\n"
            . "receipt_subject|Hi there...\n"
            . "receipt_template|Thanks for your donation!\n"
            . "receipt_threshold|50\n"
            . "require_shipping|2\n"
            . "return_page|10\n"
            . "send_receipt|1\n"
            . "sort_causes_field|name\n"
            . "sort_causes_order|DESC\n"
            . "sort_donations_field|time\n"
            . "sort_donations_order|ASC\n"
            . "style|something here?\n"
            . "subtract_paypal_fees|\n"
            . "use_html_emails|1\n";

        $this->assertEquals($expected, $settings);
    }

    function testExportToFile() {
        $this->object->exportAll("dump");

        // Verify that a zip file was created, then extract it
        $this->assertFileExists("dump.zip");
        
        $zip = new ZipArchive;
        $res = $zip->open("dump.zip");
        if ($res === TRUE) {
            $zip->extractTo("dump/");
            $zip->close();
        }

        // Verify that a directory with given path was created
        $this->assertFileExists("dump");

        // Verify that the directory contains files for each exported table
        $this->assertFileExists("dump/causes.txt");
        $this->assertFileExists("dump/settings.txt");
        //TODO donations
        //TODO styles
    }
}
?>