<?php
require('../../config.php');
require_once('lib.php');

$id = required_param('id', PARAM_INT);
list ($course, $cm) = get_course_and_cm_from_cmid($id, 'chatbot');
$chatbot = $DB->get_record('chatbot', array('id'=> $cm->instance), '*', MUST_EXIST);

 ?>
