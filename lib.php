<?php
defined('MOODLE_INTERNAL') || die;

function get_chatbot_name(){
  $name = get_string('pluginname','chatbot');
  return $name;
}
function get_chatbot_intro(){
  global $PAGE, $CFG;

  $link = $CFG->Chatbot_LinkToBot;

  $iconsize = $CFG->Chatbot_iconSize;


  $greeting = $CFG->Chatbot_greeting;
  $dontknow = $CFG->Chatbot_dontknow;
  $course = $PAGE->course;
  $courseid = $course->id;


  $intro = "";
  if($link == "")
  {
    $link = " ";
    $intro=$intro."<p>".get_string("warning_link","chatbot")."</p>";
  }
  if($greeting == "")
  {
    $intro = $intro."<p>".get_string("warning_greeting","chatbot")."</p>";

  }
  if($dontknow == "")
  {
    $intro = $intro."<p>".get_string("warning_dontknow","chatbot")."</p>";

  }
  if($iconsize <= 10)
  {
    $iconsize = 80;
    $intro = $intro."<p>".get_string("warning_size","chatbot")."</p>";
  }



  $intro = $intro.'<script>
    window.onload = function() {

        document.getElementById("chatbot_initiate").style.display = "inherit";
        document.getElementById("chatbot_window").style.display = "none";
    };

    function toggleChatbot() {
        var btn = document.getElementById("chatbot_initiate");
        var chatbot = document.getElementById("chatbot_window");



        if (chatbot.style.display == "none") {

            chatbot.style.display = "inherit";
            btn.style.display = "none";
        } else if (btn.style.display == "none") {

            chatbot.style.display = "none";
            btn.style.display = "inherit";
        }
    }
    </script>

    <div id="chatbot_initiate" onclick="toggleChatbot()" style="z-index:99; height: '.$iconsize.'px;width: '.$iconsize.'px; position: fixed;bottom: 30px;right: 40px;display: inherit; background-image:url(\''.$link.'/moodle/mod/chatbot/bot/icon.svg\');background-size:contain;background-repeat: no-repeat;"></div>
    <div id="chatbot_window" style="z-index:99; height: 500px;width: 400px;position: fixed;bottom: 30px;right: 40px;border: 1px solid grey;border-radius: 5px;display: none;">
    <div id="chatbot_menu_bar" style="width: 100%;height: 50px;position: absolute;top: 0;background-image: linear-gradient(90deg, rgb(140, 179, 35), rgb(0, 100, 155));">
        <div id="chatbot_menu_bar_close" onclick="toggleChatbot()" style="height: 50px;width: 50px;background-color: rgba(255,255,255,0);font-size: 40px;font-family: sans-serif;color: white;align-content: center;text-align: center;position: absolute;right: 0;transition: 0.3s;cursor: pointer;-ms-user-select: None;-moz-user-select: None;-webkit-user-select: None;user-select: None;">X</div>
    </div>
    <div id="chatbot_iframe_container" style="width: 100%;height: 450px;position: absolute;bottom: 0px;">
    <iframe src="'.$link.'/moodle/mod/chatbot/bot/bot.php?course='.$courseid.'" id="chatbot_iframe" frameborder="0" style="width: 100%;height: 100%;">
      Der Chatbot kann nicht erreicht werden.

    </iframe>
    </div>
</div>';

  return $intro;
}
function chatbot_add_instance($chatbot){
  global $DB, $PAGE, $CFG;


  $course = $PAGE->course;

  $courseid = $course->id;

  $chatbot->intro = get_chatbot_intro();

  


  $chatbot->name = get_chatbot_name();
  $chatbot->timemodified = time();
  $chatbot->course = $courseid;

  if($DB->record_exists("chatbot", array("course"=>$courseid)))
  {
    $DB->delete_records("chatbot", array("course"=>$courseid));
  }

  $id = $DB->insert_record("chatbot", $chatbot);


  return $id;
}
function chatbot_update_instance($chatbot){
  global $DB, $PAGE, $CFG;

  $course = $PAGE->course;

  $courseid = $course->id;

    $chatbot->intro = get_chatbot_intro();




    $chatbot->name = get_chatbot_name();
    $chatbot->timemodified = time();
    $chatbot->id = $DB->get_field("chatbot","id", array("course"=>$courseid));
    $id = $chatbot->id;



    return $DB->update_record("chatbot", $chatbot);
}

function chatbot_delete_instance($id){
    global $DB, $PAGE;

    $course = $PAGE->course;
    $courseid = $course->id;

    if (! $chatbot = $DB->get_record("chatbot", array("course"=>$courseid))) {
        return false;
    }
    $result = true;

    if (! $DB->delete_records("chatbot", array("course"=>$courseid))) {
        $result = false;
    }

    return $result;
}
