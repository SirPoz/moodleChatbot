<?php
  require(__DIR__.'/../../../config.php');
  defined('MOODLE_INTERNAL') || die();

  function get_answers_from_database($question_word, $course){
    global $DB;
    $packages = get_packages_from_database($course);

    $pack_sql = "(";
    for($p = 0; $p<count($packages); $p++)
    {
      $pack_sql = $pack_sql." packageid = ".$packages[$p];
      if($p<count($packages)-1)
      {
        $pack_sql = $pack_sql." OR";
      }
    }
    $pack_sql = $pack_sql.")";
    $sql = "SELECT answer FROM {chatbot_packages} WHERE ".$pack_sql." AND (";

    for($i = 1; $i <= 4; $i++)
    {
      $sql = $sql."keyword".$i. " ='".$question_word."' ";
      if($i != 4)
      {
        $sql = $sql . "OR ";
      }
      if($i == 4)
      {
        $sql = $sql.")";
      }
    }
    
    $answers = $DB->get_fieldset_sql($sql);

    return $answers;
  }

  function get_packages_from_database($courseid){
    global $DB, $CFG;
    $sql = "SELECT DISTINCT packageid FROM {chatbot_courses} WHERE courseid = ".$courseid;

    $answers = $DB->get_fieldset_sql($sql);
    if(count($answers)<1)
    {
      $answers[0]= $CFG->Chatbot_main_package;
    }
    if(!in_array($CFG->Chatbot_main_package,$answers))
    {
      array_push($answers,$CFG->Chatbot_main_package);
    }
    return $answers;
  }
