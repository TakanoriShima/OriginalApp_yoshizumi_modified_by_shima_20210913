<?php
  //(C)
  require_once "filters/LoginFilter.php";
  require_once "models/User.php";
  require_once "models/Profile.php";
  require_once "models/Message.php";
  
  session_start();
 
  //セッションからログインユーザー情報を取得
   $login_user = $_SESSION["login_user"];
  
  //入力情報を取得
  $receive_user_id = $_POST["receive_user_id"];
  $message_content = $_POST["message_content"];

  
  //login_userからidを取得する
  $send_user_id = $login_user->id;


  // // //入力からメッセージインスタンスを新規作成
  $message = new Message($send_user_id,$receive_user_id,$message_content);
  var_dump($message);
  
  // // //入力項目に誤りがないかチェック
  $errors = $message->validate();
  
  $receive_user_id=$message->receive_user_id;
  
  // // 入力エラーが１つもなければ
  if(count($errors) === 0){
 
    // メッセージインスタンスを作成
    $flash_message = $message->save();
    $_SESSION["flash_message"] = $flash_message;
    
    header("Location:message_show.php?receive_user_id=".$message->receive_user_id);
    exit;
    
  //   //入力エラーが１つでもあれば
  }else{
    //セッションにエラーを保存
    $_SESSION["errors"] = $errors;
    header("Location:message_show.php?receive_user_id=".$message->receive_user_id);
    exit;

  }
  
