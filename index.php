<?php

require_once('./line_class.php');
session_start();
$channelAccessToken = '/tcZRyEboiJy5hvho5KIblRr+LQuX4TsmMPoae6FCRU7gUv0Hh1yKIJA3L31mMRQQUSGH3m1uN84VNF6360t9j0t/xMXBABO6jIdwvLucCv+Gl1MzsS3VxGZUBEv+wjH8E0KkXOJjtOt/zEiUJLSUAdB04t89/1O/w1cDnyilFU='; //Your Channel Access Token
$channelSecret = '4108f1189692251b18ffdd96e108d057';//Your Channel Secret

$client = new LINEBotTiny($channelAccessToken, $channelSecret);

$groupId 	= $client->parseEvents()[0]['source']['groupId'];
$userId 	= $client->parseEvents()[0]['source']['userId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$message 	= $client->parseEvents()[0]['message'];
$profil = $client->profil($userId);
$pesan_datang = $message['text'];

/*$bot = new \LINE\LINEBot(new CurlHTTPClient($channelAccessToken), [
    'channelSecret' => $channelSecret
]);

$res = $bot->getProfile('user-id');
if ($res->isSucceeded()) {
    $profile = $res->getJSONDecodedBody();
    $displayName = $profile['displayName'];
    $statusMessage = $profile['statusMessage'];
    $pictureUrl = $profile['pictureUrl'];
}
*/
if($message['type']=='sticker')
{	
	$balas = array(
							'UserID' => $profil->userId,	
                                                        'replyToken' => $replyToken,							
							'messages' => array(
								array(
										'type' => 'text',									
										'text' => 'Terima Kasih Stikernya.'										
									
									)
							)
						);
						
}
else
	
$pesan=str_replace(" ", "%20", $pesan_datang);
$usernm = str_replace(" ", "%20", $profil->displayName);
$url = 'http://karyakreatif.com/tebakkata/?pesan='.$pesan.'&gr='.$groupId.'&u='.$userId.'&un='.$usernm.'&a='.$profile.'&b='.$statusMessage.'&c='.$pictureUrl;
$json_data = file_get_contents($url);
$url=json_decode($json_data,1);

$diterima = $url['response'];
if($message['type']=='text')
{
if($url['result'] == 404)
	{
		$balas = array(
							'UserID' => $profil->userId,	
                                                        'replyToken' => $replyToken,													
							'messages' => array(
								array(
										'type' => 'text',					
										'text' => 'Mohon Gunakan Bahasa Indonesia Yang Benar :D.'
									)
							)
						);
				
	/*}
else
if($url['result'] != 100)
	{
		
		
		$balas = array(
							'UserID' => $profil->userId,
                                                        'replyToken' => $replyToken,														
							'messages' => array(
								array(
										'type' => 'text',					
										'text' => 'Maaf '.$profil->displayName.' Server Kami Sedang Sibuk Sekarang.'
									)
							)
						);*/
				
	}
	else{
		$balas = array(
							'UserID' => $profil->userId,
                                                        'replyToken' => $replyToken,														
							'messages' => array(
								array(
										'type' => 'text',					
										'text' => ''.$diterima.''
									)
							)
						);
						
	}
}
 
$result =  json_encode($balas);

file_put_contents('./reply.json',$result);


$client->replyMessage($balas);
