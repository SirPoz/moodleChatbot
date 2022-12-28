<?php
include("functions.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <style>
      body{
        background-color:white;

      }
      #conversation_table{
        width:100%;

      }
      .textarea {
          position: fixed;
          bottom: 0px;
          left: 0;
          right: 0;
          width: 100%;
          height: 55px;
          z-index: 99;
          background-color: #000000;
          border: 1px solid rgba(0,0,0,0.2);
          outline: none;
          padding-left: 15px;
          padding-right: 15px;
          color: #000000;
          font-weight: 300;
          font-size: 1rem;
          line-height: 1.5;
          background: rgba(250,250,250,0.8);
      }

      .textarea:focus {
          background: white;
          box-shadow: 0 -6px 12px 0px rgba(235,235,235,0.95);
          transition: 0.4s;
      }

      .answer{
        max-width: 70%;
        min-width: 40%;
        margin-top:3px;
        background-color: rgb(140, 179, 35);
        color: white;
        align-self: left;
        border-radius:0px 5px 5px 5px;

        padding:3%;
        text-align:left;
        font-size:15px;
        font-family:sans-serif;
      }

      .question{
        max-width: 70%;
        min-width: 40%;
        float:right;
        margin-top:3px;
        background-color: rgb(0, 100, 155);
        color: white;
        align-self: left;
        border-radius:5px 0px 5px 5px;

        padding:3%;
        text-align:right;
        font-size:15px;
        font-family:sans-serif;

      }
      .row-answer{
        width:98%;
        margin:1px;
        margin-top:5px;
        overflow:auto;
        overflow-x: hidden;

      }
      .row-question{
        width:98%;
        margin:1px;
        margin-top:5px;
        overflow:auto;
        overflow-x: hidden;

      }
      #conversation{

        padding:5px;
        height:
      }
      #conversation_table tr{
        width: 100%;
      }
      #conversation_table td{
        width:30%;
      }
      #conversation .message{
        width:70%;
      }

      .spacer{
        height:55px;
        width:100%;
      }
      .time_stamp{
        line-height: 0.4px;
        font-size: 13px;
        color:#f2f2f2;
        position:relative;
        left:48%;
      }
    </style>
    <script>
      window.onload = function(){
        document.getElementById("spacer").scrollIntoView();
      };

    </script>
  </head>
  <body>
    <div id="conversation">
        <div class="row-answer">
          <div class="answer"><?php echo greet_user(); ?></div>
        </div>
        <?php


          start_session();
          if(isset($_POST["question"]))
          {
            if(!empty($_GET["course"]))
            {
              question_asked($_POST["question"],$_GET["course"],true);
            }
            else
            {
              question_asked($_POST["question"],"",false);
            }

          }
          ?>
      <div class="spacer" id="spacer"></div>
    </div>
    <div id="container">
      <form method="post">
        <input type="text" class="textarea" name="question" placeholder="Schreiben Sie eine Nachricht..."/>
      </form>
    </div>
  </body>
</html>
