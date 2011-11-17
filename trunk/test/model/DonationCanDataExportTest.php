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
        $this->object = new DonationCanDataExport($this->mockOptions);
    }

    protected function tearDown() {
    }

    //
    // FUNCTIONS FOR CREATING TEST DATA
    //

    function createCausesForTest() {
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
    }

    function createGeneralSettings() {
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
    }

    function createStylesForTest() {
        // Styles need to include the default styles and a couple of
        // custom styles. Export will only export the custom ones.

        $widget_styles["default"] = array(
                "name" => "Default",
                "id" => "default",
                "locked" => true,
                "elements" => array(
                    "1" => array("type" => "title"),
                    "2" => array("type" => "description"),
                    "3" => array("type" => "progress", "text-format" => "<span class=\"currency\">%CURRENCY%</span><span class=\"raised\">%CURRENT%</span><span class=\"raised-label\">Raised</span><span class=\"goal\">%TARGET%</span><span class=\"goal-label\">Target</span>"),
                    "4" => array("type" => "cause-selection"),
                    "5" => array("type" => "donation-options"),
                    "6" => array("type" => "anonymous", "prompt" => "Anonymous donation"),
                    "7" => array("type" => "submit"),
                    "8" => array("type" => "donation-list")
                ),
                "css" => array(
                    "" => "border: 1px #ddd solid; border-radius: 5px; -moz-border-radius: 5px; padding: 10px; background-color: #f5f5f5; color: #333;",
                    "h3" => "margin-top: 0px;",
                    ".description" => "margin: 10px 0px 0px 0px;",
                    ".donation_meter" => "background-color: #fafafa; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; margin: 10px -10px 10px -10px; padding: 10px;",
                    ".progress-meter" => "border: 0px; height: 10px;",
                    ".progress-meter .past-goal" => "background-color: #ddee00;",
                    ".progress-container" => "background-color: #ddd; height: 10px; border-radius: 4px; -moz-border-radius: 4px;",
                    ".progress-bar" => "background-color: #87C442; height: 10px; border-radius: 4px; -moz-border-radius: 4px;",
                    ".progress-text" => "position: relative; margin-top: 10px; font-size: 8pt; color: #444; height: 30px;",
                    ".currency" => "position: absolute; display: block; left: 0px; top: 0px;",
                    ".raised" => "position: absolute; top: 0px; left: 10px; font-weight: bold; display: block;",
                    ".raised-label" => "position: absolute; top: 15px; left: 0px; text-transform: uppercase; color: #777; display: block;",
                    ".goal" => "position: absolute; top: 0px; right: 0px; font-weight: bold; display: block;",
                    ".goal-label" => "position: absolute; top: 15px; right: 0px; text-transform: uppercase; color: #777; display: block;",
                    ".donation-options select" => "width: 100%;",
                    ".submit-donation" => "width: 100%;",
                    ".submit-donation input" => "margin: 10px auto 0px auto; width: 147px; display: block;",
                    ".backlink" => "text-align: center; margin-top: 15px;",
                    ".donations-list-container" => "margin: 10px -10px 0px -10px; padding: 10px; border-top: 1px solid #ddd;",
                    ".donations-list" => "margin: 0px; padding: 0px; font-size: 10pt; list-style: none;",
                    ".donations-list li" => "list-style: none; background: transparent; padding: 0px !important; margin: 5px 0px 5px 0px !important; font-size: 9pt;",
                    ".donation-date" => "color: #888; font-size: 8pt; display: block;",
                    ".donation-can-cause-selection select" => "width: 100%;"
                )
            );

        $widget_styles["default_2"] = array(
                "name" => "Default Vertical",
                "id" => "default_2",
                "locked" => true,
                "elements" => array(
                    "1" => array("type" => "progress", "direction" => "vertical", "text-format" => "<span class=\"percentage\">%PERCENTAGE% %</span> <span class=\"of-label\">of</span> <span class=\"currency\">%CURRENCY%</span><span class=\"goal\">%TARGET%</span>"),
                    "2" => array("type" => "title"),
                    "3" => array("type" => "description"),
                    "4" => array("type" => "donation-options", "list-format" => "buttons"),
                    "5" => array("type" => "anonymous", "prompt" => "Anonymous donation"),
                    "6" => array("type" => "donation-list")
                ),
                "css" => array(
                    "" => "text-align: left; border: 1px solid #ccc; border-radius: 5px; -moz-border-radius: 5px; padding: 0px 10px 10px 0px; background-color: #f5f5f5; font-family: Verdana; font-size: 8pt; color: #333;",
                    "h3" => "margin: 10px auto 10px auto; text-align: left; font-family: Arial;",
                    ".description" => "text-align: left; margin: 10px 0px 0px 0px;",
                    ".donation-form" => "overflow: auto;",
                    ".donation_meter" => "width: 50px; float: left; margin: 0px 10px 0px 0px; text-align: center; background-color: #fff; border-top-left-radius: 5px; border-bottom-right-radius: 5px; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;",
                    ".progress-meter" => "border: 0px; height: 200px; width: 20px; margin: auto;",
                    ".progress-container" => "background-color: #eee; border: 0px; height: 200px; width: 20px; border-radius: 4px; -moz-border-radius: 4px; position: relative;",
                    ".progress-bar" => "background-color: #87C442; position: absolute; bottom: 0px; left: 0px; width: 20px; border-radius: 4px; -moz-border-radius: 4px;",
                    ".donation-options" => "margin: 10px 0px 10px 0px;",
                    ".donation-callout" => "display: none;",
                    ".donation-button-list" => "width: auto;",
                    ".button" => "display: block; padding: 5px; background-color: #e5e5e5; margin: 8px 0px 7px 0px; border: 0px; text-align: left; cursor: pointer;",
                    ".backlink" => "text-align: center; margin-top: 15px;",
                    ".progress-text" => "margin-top: 5px; font-size: 8pt;",
                    ".raised-label" => "display: none;",
                    ".percentage" => "display: block; text-align: center; font-weight: bold; color: #888;",
                    ".goal-label" => "display: none;",
                    ".of-label" => "display: block; text-align: center; color: #999; font-size: 8pt;",
                    ".currency" => "color: #999; font-size: 8pt;",
                    ".goal" => "color: #999; text-align: center; font-size: 8pt;",
                    ".donations-list-container" => "overflow: auto; clear: left; margin: 0px; padding: 0px;",
                    ".donations-list-inner" => "margin: 10px 0px 0px 0px; padding: 10px;",
                    ".donations-list" => "font-size: 10pt; list-style: none;",
                    ".donations-list li" => "list-style: none; background: transparent; padding: 0px !important; margin: 5px 0px 5px 0px !important; font-size: 9pt;",
                    ".donation-date" => "color: #888; font-size: 8pt; display: block;"
                )
            );

        $widget_styles["test_1"] = array(
                "name" => "Test Style 1",
                "id" => "test_1",
                "locked" => false,
                "elements" => array(
                    "1" => array("type" => "progress", "direction" => "vertical", "text-format" => "<span class=\"percentage\">%PERCENTAGE% %</span> <span class=\"of-label\">of</span> <span class=\"currency\">%CURRENCY%</span><span class=\"goal\">%TARGET%</span>"),
                    "2" => array("type" => "title"),
                    "3" => array("type" => "donation-options", "list-format" => "buttons"),
                    "4" => array("type" => "donation-list"),
                    "5" => array("type" => "text", "text" => "Hello, world!")
                ),
                "css" => array(
                    "" => "text-align: left;",
                    "h3" => "margin: 10px auto 10px auto; text-align: left; font-family: Arial;",
                    ".description" => "text-align: left; margin: 10px 0px 0px 0px;",
                    ".donation-form" => "overflow: auto;"
               )
            );

            $widget_styles["superman_style"] = array(
                "name" => "Superman Style",
                "id" => "superman_style",
                "locked" => false,
                "elements" => array(
                    "1" => array("type" => "progress", "direction" => "horizontal", "text-format" => "%PERCENTAGE% % of %CURRENCY% %TARGET%"),
                ),
                "css" => array(
                    "" => "text-align: left;"
               )
            );


        $this->mockOptions->update_option("donation_can_widget_styles", $widget_styles);
    }

    //
    // TESTS
    //

    public function testExportCausesAsCSV() {
        $this->createCausesForTest();

        // CSV FORMAT:
        // cause_code,name,description,goal,currency,return_page,cancelled_page,continue_text,notify_email,donation_options,allow_freeform

        $result = $this->object->exportCauses("CSV", "|");

        $expected_csv = "test-cause|Test Cause|Description for cause|1000|USD|||Go back!|email@email.com,email2@email.com|1,5,10|1\n"
            . "test-cause-2|Test Cause 2|Description for cause|1000|CAD|1|5|Go back!|email@email.com,email2@email.com||0\n";

        $this->assertEquals($expected_csv, $result);
    }

    public function testExportCausesAsXML() {
        $this->createCausesForTest();

        $expected_xml =
"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<causes>
\t<cause id=\"test-cause\">
\t\t<name>Test Cause</name>
\t\t<description>Description for cause</description>
\t\t<goal>1000</goal>
\t\t<currency>USD</currency>
\t\t<return_page></return_page>
\t\t<cancelled_page></cancelled_page>
\t\t<continue_link_text>Go back!</continue_link_text>
\t\t<notify_email>
\t\t\t<email>email@email.com</email>
\t\t\t<email>email2@email.com</email>
\t\t</notify_email>
\t\t<donation_options>
\t\t\t<option>1</option>
\t\t\t<option>5</option>
\t\t\t<option>10</option>
\t\t</donation_options>
\t\t<allow_freeform_donation_sums>1</allow_freeform_donation_sums>
\t</cause>
\t<cause id=\"test-cause-2\">
\t\t<name>Test Cause 2</name>
\t\t<description>Description for cause</description>
\t\t<goal>1000</goal>
\t\t<currency>CAD</currency>
\t\t<return_page>1</return_page>
\t\t<cancelled_page>5</cancelled_page>
\t\t<continue_link_text>Go back!</continue_link_text>
\t\t<notify_email>
\t\t\t<email>email@email.com</email>
\t\t\t<email>email2@email.com</email>
\t\t</notify_email>
\t\t<donation_options></donation_options>
\t\t<allow_freeform_donation_sums>0</allow_freeform_donation_sums>
\t</cause>
</causes>";

        $result = $this->object->exportCauses("XML");

        $this->assertEquals($expected_xml, $result);
    }

    function testExportGeneralSettings() {
        $this->createGeneralSettings();
        $settings = $this->object->exportSettings("|");

        // Settings are returned in a CSV format as key-value pairs,
        // sorted alphabetically by key
        $expected =
"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<general_settings>
\t<option name=\"ask_for_note\">1</option>
\t<option name=\"bg_on_paypal_page\">#FFFFFF</option>
\t<option name=\"cancel_return_page\">20</option>
\t<option name=\"continue_button_text\">Continue.</option>
\t<option name=\"currency\">JPY</option>
\t<option name=\"custom\">something here too?</option>
\t<option name=\"debug_mode\">1</option>
\t<option name=\"donation_sums\">1,5,10</option>
\t<option name=\"email_from\">some-email@mail.com</option>
\t<option name=\"email_from_name\">Test User</option>
\t<option name=\"email_template\">Hello, world!</option>
\t<option name=\"enable_logging\"></option>
\t<option name=\"header_bg_on_paypal_page\">#EEEEEE</option>
\t<option name=\"header_border_on_paypal_page\">#333300</option>
\t<option name=\"header_on_paypal_page\">http://someotherurl.com/image.png</option>
\t<option name=\"link_back\">1</option>
\t<option name=\"logo_on_paypal_page\">http://someurl.com/image.png</option>
\t<option name=\"note_field_label\">Leave a note!</option>
\t<option name=\"notify_email\">test-user@gmail.com</option>
\t<option name=\"paypal_email\">test-user@gmail.com</option>
\t<option name=\"paypal_sandbox_email\">sandbox-test-user@gmail.com</option>
\t<option name=\"receipt_subject\">Hi there...</option>
\t<option name=\"receipt_template\">Thanks for your donation!</option>
\t<option name=\"receipt_threshold\">50</option>
\t<option name=\"require_shipping\">2</option>
\t<option name=\"return_page\">10</option>
\t<option name=\"send_receipt\">1</option>
\t<option name=\"sort_causes_field\">name</option>
\t<option name=\"sort_causes_order\">DESC</option>
\t<option name=\"sort_donations_field\">time</option>
\t<option name=\"sort_donations_order\">ASC</option>
\t<option name=\"style\">something here?</option>
\t<option name=\"subtract_paypal_fees\"></option>
\t<option name=\"use_html_emails\">1</option>
</general_settings>";

        $this->assertEquals($expected, $settings);
    }

    function testExportStyles() {
        // Test that all custom styles are exported, not including the
        // default styles. CSV doesn't make sense, so we only allow exporting to XML.
        $this->createStylesForTest();

        $expected =
"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<widget_styles>
\t<widget_style id=\"test_1\" name=\"Test Style 1\">
\t\t<elements>
\t\t\t<element type=\"progress\">
\t\t\t\t<option name=\"direction\">vertical</option>
\t\t\t\t<option name=\"text-format\">&lt;span class=&quot;percentage&quot;&gt;%PERCENTAGE% %&lt;/span&gt; &lt;span class=&quot;of-label&quot;&gt;of&lt;/span&gt; &lt;span class=&quot;currency&quot;&gt;%CURRENCY%&lt;/span&gt;&lt;span class=&quot;goal&quot;&gt;%TARGET%&lt;/span&gt;</option>
\t\t\t</element>
\t\t\t<element type=\"title\">
\t\t\t</element>
\t\t\t<element type=\"donation-options\">
\t\t\t\t<option name=\"list-format\">buttons</option>
\t\t\t</element>
\t\t\t<element type=\"donation-list\">
\t\t\t</element>
\t\t\t<element type=\"text\">
\t\t\t\t<option name=\"text\">Hello, world!</option>
\t\t\t</element>
\t\t</elements>
\t\t<css_definitions>
\t\t\t<definition selector=\"\">text-align: left;</definition>
\t\t\t<definition selector=\"h3\">margin: 10px auto 10px auto; text-align: left; font-family: Arial;</definition>
\t\t\t<definition selector=\".description\">text-align: left; margin: 10px 0px 0px 0px;</definition>
\t\t\t<definition selector=\".donation-form\">overflow: auto;</definition>
\t\t</css_definitions>
\t</widget_style>
\t<widget_style id=\"superman_style\" name=\"Superman Style\">
\t\t<elements>
\t\t\t<element type=\"progress\">
\t\t\t\t<option name=\"direction\">horizontal</option>
\t\t\t\t<option name=\"text-format\">%PERCENTAGE% % of %CURRENCY% %TARGET%</option>
\t\t\t</element>
\t\t</elements>
\t\t<css_definitions>
\t\t\t<definition selector=\"\">text-align: left;</definition>
\t\t</css_definitions>
\t</widget_style>
</widget_styles>";

        $output = $this->object->exportStyles();

        $this->assertEquals($expected, $output);
    }

    function testExportDonationsAsCSV() {

// TODO: come up with a nice way to test this kind of db stuff. For now, I just had to
//       give up and finish the functionality quickly before getting to bed. ;)

        
/*        $expected = "ID,Item number,Transaction ID,Status,Date,Email,First name,Last name,Anonymous,Cause code,Amount,Fee,Note,Sandbox,Offline donation,Deleted\n"
            . "1,testi-1319618004,offline,Completed,2011-10-26 08:33:12,jxlaine@gmail.com,Jarkko,Laine,0,testi,50.00,0.00,,0,1,0";

        $result = $this->object->exportDonations("CSV");
        
        $this->assertEquals($expected, $result);
 */
    }
}
?>