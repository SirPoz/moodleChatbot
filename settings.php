<?php
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    global $DB;
    $navnames = $DB->get_fieldset_sql("SELECT DISTINCT packagename, MAX(packageid) FROM {chatbot_packages} GROUP BY packagename ORDER BY MAX(packageid) ASC, packagename");
    $navvalues = $DB->get_fieldset_sql("SELECT DISTINCT packageid FROM {chatbot_packages} ORDER BY packageid ASC, packageid");
    $options = array();
    for($i = 0; $i < count($navvalues); $i++)
    {
      $options[$navvalues[$i]] = $navnames[$i];
    }

    $settings->add(new admin_setting_configtext('Chatbot_LinkToBot',
        get_string("setting_adress", "chatbot"), "", "https://addressOfYourMoodle", PARAM_TEXT, 35));
    $settings->add(new admin_setting_configtext('Chatbot_iconSize',
        get_string("setting_size", "chatbot"), "", "80", PARAM_INT, 7));

    $settings->add(new admin_setting_configtext('Chatbot_greeting',
        get_string("setting_greet","chatbot"), "", get_string("greeting","chatbot"), PARAM_TEXT, 50));
    $settings->add(new admin_setting_configtext('Chatbot_dontknow',
        get_string("setting_dontknow","chatbot"), "", get_string("dontknow","chatbot"), PARAM_TEXT, 50));
    $settings->add(new admin_setting_configtext('Chatbot_multipleanswers',
        get_string("setting_multipleanswers","chatbot"), "", get_string("multipleanswers","chatbot"), PARAM_TEXT, 50));
    $settings->add(new admin_setting_configselect('Chatbot_main_package',
            get_string('main_package', 'chatbot'), '', PARAM_INT, $options));

}
?>
