<?php

  include("chatbot_functions.php");

  //start a session if not already running, looks if the conversation is older than 5 minutes --> if so it deletes it,
  function start_session(){
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    if(!isset($_SESSION['conversation'])||$_SESSION['last_time_used']<= time()-300)
    {
      $_SESSION['conversation'] = array();
      $_SESSION['last_time_used'] = time();
    }
    else{
      $_SESSION['last_time_used'] = time();
      for($i = 0; $i < count($_SESSION['conversation']); $i+=2)
      {
        sendPrevMessage($_SESSION['conversation'][$i],$_SESSION['conversation'][$i+1]);
      }
    }
  }


  function question_asked($question,$KursNummer,$KursErkannt){
    global $CFG;

    $bad_chars = array(
      "<",
      ">",
      "/",
      "\"",
      "?",
      "!",
      ",",
      ".",
      ";",
      "-",
      "|",
      "'",
      '"'
    );

    //get rid of unwanted spaces --> too much spaces or a space at the begining or end of the question
    while(strpos($question, '  ') !== false)
    {
      $question = str_replace("  "," ",$question);
    }
    if($question[0]== " ")
    {
      $question = substr($question,1,strlen($question)-1);
    }
    if($question[strlen($question)-1] == " ")
    {
      $question= substr($question,0,strlen($question)-1);
    }

    sendMessage("question",$question);

    $answer_array=[];

    $question = str_replace($bad_chars,"",$question);

      //die gestellte Frage wird in einzelne Wörter in einem Array gespeichert --> "Wo gibt es Essen" --> "Wo", "gibt", "es", "Essen"
      $words = explode(" ", $question);

      for($w = 0; $w < count($words); $w++)
      {
        $answers = get_answers_from_database($words[$w],$KursNummer);
        if($answers != "")
        {
          for($i = 0; $i<count($answers);$i++)
          {
            array_push($answer_array,$answers[$i]);
          }
        }
      }

      if (count($answer_array)==0)
      {
        //wenn er keine Antworten gefunden hat sagt der Chatbot "Das weiß ich leider nicht"
        if($CFG->Chatbot_dontknow != "")
        {
          sendMessage("answer", $CFG->Chatbot_dontknow);
        }
        else {
          sendMessage("answer",get_string("dontknow","chatbot"));
        }
      }
      else
      {
        if(count($answer_array)>=2)
        {
          if($CFG->Chatbot_multipleanswers != "")
          {
          //wenn der Chatbot mehrere Antworten gefunden hat sagt er dies auch sollte er nur eine haben schickt er diese Nachricht nicht
          sendMessage("answer", $CFG->Chatbot_multipleanswers);
          }
          else {
            sendMessage("answer", get_string("multipleanswers","chatbot"));
          }
        }
        for($i = 0; $i < count($answer_array);$i++)
        {
          //der Chatbot gibt alle Ergebnisse der Datenbanksuche aus
          sendMessage("answer", $answer_array[$i]);
        }
      }
}




function sendMessage($type, $input){
  //wenn eine neue Message erstellt wird, wird der Type der Message (answer oder question) und der input (also der Textinhalt mitgegeben)
  //es wird die Zeit abgerufen
  $time = date('H:i');
  $bad_chars = array(
    "<",
    ">",
    "/",
    "\"",
    "-",
    "|",
    "'",
    "{",
    "}"
  );
  if($type == "question")
  {
    $input = str_replace($bad_chars, "", $input);
  }
  //eine neue Message wird erstellt und mit dem jeweiligen type und input variablen befüllt
  $message = "
    <div class='row-".$type."'>
      <div class='".$type."'>
        <p>".$input."</p>
      </div>
    </div>";
    //die message wird ausgebeben.
  echo $message;
  array_push($_SESSION["conversation"],$type,$input);

}
function sendPrevMessage($type, $input){
  $message = "
    <div class='row-".$type."'>
      <div class='".$type."'>
        <p>".$input."</p>
      </div>
    </div>";
    //die message wird ausgebeben.
  echo $message;
}
function greet_user(){
  global $CFG;
  if($CFG->Chatbot_greeting == "")
  {
    return get_string("greeting","chatbot");
  }
  else{
    return $CFG->Chatbot_greeting;
  }

}
 ?>
