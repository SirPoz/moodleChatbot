<?php
require_once('../../config.php');
require_once("lib.php");

$id = required_param('id', PARAM_INT);           // Course ID

// Ensure that the course specified is valid
$PAGE->set_url('/mod/chatbot/index.php', array('id'=>$id));

redirect("$CFG->wwwroot/course/view.php?id=$id");
