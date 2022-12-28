<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->libdir . '/csvlib.class.php');
require_once("lang/en/chatbot.php");
class mod_chatbot_mod_form extends moodleform_mod {

  function definition() {
    global $PAGE, $DB, $CFG;

    $PAGE->force_settings_menu();
    $packages = $DB->get_fieldset_sql("SELECT DISTINCT packageid FROM {chatbot_packages}");
    $packagenames = [];

    $course = $PAGE->course;
    $courseid = $course->id;
    for($i = 0; $i < count($packages); $i++)
    {
      $packagenames[$i]=$DB->get_field_sql("SELECT DISTINCT packagename FROM {chatbot_packages} WHERE packageid=".$packages[$i]);
    }

    $activepackages = $DB->get_fieldset_sql("SELECT DISTINCT packageid FROM {chatbot_courses} WHERE courseid=".$courseid);
    echo "<script>console.log(".count($activepackages).");</script>";
    $mform = $this->_form;

    $mform->addElement('header', 'generalhdr', get_string('pluginname', 'chatbot'));
    $this->standard_intro_elements(get_string('pluginname', 'chatbot'));

    $mform->addElement('header', 'package_management', get_string('header_package_selector', 'chatbot'));
    //handling the display of all possible packages to chose from
    $mform->addElement('static', 'package_selector_text', get_string('description_package_selector','chatbot'));
    for($c = 0; $c < count($packages); $c++)
    {
      $mform->addElement('advcheckbox', 'package:'.$packages[$c], $packagenames[$c]);
      if($c==$CFG->Chatbot_main_package)
      {
        $mform->setDefault('package:'.$packages[$c],1);
        $mform->disabledIf('package:'.$packages[$c], 'package:'.$packages[$c], 'neq',1);
      }
      else if(in_array($packages[$c],$activepackages))
      {
        $mform->setDefault('package:'.$packages[$c],1);
      }

    }


    $mform->addElement('header','package_upload', get_string('header_package_upload', 'chatbot'));
    //handling a new package upload
    $mform->addElement('static', 'package_upload_text', get_string('template_csv','chatbot'));
    $mform->addElement('text', 'new_package_name', get_string('name_newpackage','chatbot'));
    $mform->setType('new_package_name',PARAM_TEXT);
    $mform->addElement('advcheckbox', 'chatbot_public', get_string('checkbox_public','chatbot'));

    $link_to_template = "<a href='".$CFG->Chatbot_LinkToBot."/moodle/mod/chatbot/bot/package_template.csv' download>".get_string('template_csv','chatbot')."</a>";
    $mform->addElement('static', 'download_link', $link_to_template);
    $mform->addElement('textarea', 'new_package', get_string('upload_newpackage','chatbot'), 'wrap="virtual" rows="20" cols="50"');




    $mform->addElement('hidden', 'showdescription', 1);
    $mform->setType('showdescription', PARAM_INT);

    $this->standard_coursemodule_elements();


//-------------------------------------------------------------------------------
// buttons
    $this->add_action_buttons(true, false, null);

  }





  function validation($data, $files){
    global $PAGE, $DB, $USER;

    $course = $PAGE->course;
    $courseid = $course->id;

    $packages = $DB->get_fieldset_sql("SELECT DISTINCT packageid FROM {chatbot_packages}");
    $DB->delete_records("chatbot_courses",["courseid"=>$courseid]);


      for($i = 0; $i < count($packages);$i++)
      {
         $new_import = new stdClass;
        if($data['package:'.$packages[$i]] == 1)
        {
          $new_import->courseid = $courseid;
          $new_import->packageid = $packages[$i];
          $DB->insert_record("chatbot_courses",$new_import);
        }
      }


      if(!empty($data["new_package_name"]) && !empty($data["new_package"]))
      {
            $author = $USER->id;
            $this::createNewPackage($data["new_package_name"], $data["new_package"], $data["chatbot_public"], $author);
      }






  }
  function createNewPackage($name, $package, $public, $author){
    global $DB;
    $packageid = $DB->get_field_sql("SELECT DISTINCT MAX(packageid) FROM {chatbot_packages}");
    $packageid++;
    $columns = explode("\n",$package);
    $new_import = new stdClass;
    $new_import->packageid = $packageid;
    $new_import->packagename = $name;
    $new_import->public = "";
    $new_import->authorid = $author;
    foreach($columns as $column)
    {
      $fields = explode(";",$column);
      $new_import->question = $fields[0];
      $new_import->keyword1 = $fields[1];
      $new_import->keyword2 = $fields[2];
      $new_import->keyword3 = $fields[3];
      $new_import->keyword4 = $fields[4];
      $new_import->answer   = $fields[5];
      $DB->insert_record("chatbot_packages",$new_import);
    }
  }
}
