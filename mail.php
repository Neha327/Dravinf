<?php
  $email = $_POST['email'];
  $name = $_POST['name'];
  $message = $_POST['message'];
  $subject = $_POST['subject'];
  


if($_POST){

    $data = [
        'email'     => $email,
        'status'    => 'subscribed',
        'subject' =>   $subject,
        'message'  =>    $message,
        'fname'  =>    $name
    ];
   
    $res = syncMailchimp($data);
    
    if($res == 200){
        echo '<div class="alert alert-success" role="alert">Subscribed Successfull</div>';
    }else{
        echo '<div class="alert alert-danger" role="alert">Unable to Subscribe at the moment, try again later</div>';
    }

    echo $res;
}


function syncMailchimp($data)
{
    $apiKey = '798691308b5ffd9f742f5b46ecfe10f6-us10';
    $listId = '0a3e0d1b67';

    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

    $json = json_encode([
        'email_address' => $data['email'],
        'subject' => $data['subject'],
        'message' => $data['message'],
        'fname' => $data['fname'],
        'status' => $data['status']
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode;
}

?>