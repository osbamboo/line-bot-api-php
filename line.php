<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'hU431d2ueASC44C44Xdm5ZkWN9teOYFPNupq+N9nYs1nc3yWguydBXsZ3t5xniV/GRzAdbOrXR6aPz4307SYNOqlZagNR5doosmyGEmWvfbJcpMwAsJgqkk97APFSo8bM7GYs4/b2jDV0bTdC8mzvAdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
	   	if($text == "ชื่อ"||$text == "ชื่ออะไร"||$text == "ชื่ออะไรครับ"||$text == "ชื่ออะไรคะ"){
			$reply_message = 'ชื่อของฉันคือ WASIN';
		}
	   
		if($text == "สถานการณ์โควิดวันนี้" || $text == "covid19" || $text == "covid-19" || $text == "Covid-19"){
     		$url = 'https://covid19.th-stat.com/api/open/today';
     		$ch = curl_init($url);
     		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
     		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     		curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
     		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
     		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
     		$result = curl_exec($ch);
     		curl_close($ch);   
     
     		$obj = json_decode($result);
     
     		$reply_message = $result;
     		$reply_message = 'ติดเชื้อสะสม '. $obj->{'Confirmed'}.' คน/r/n'.'รักษาหายแล้ว '. $obj->{'Recovered'}.' คน';
		
    		}
		//$reply_message = '('.$text.') ได้รับข้อความเรียบร้อย!!';   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
