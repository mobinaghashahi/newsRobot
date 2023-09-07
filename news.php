<?php


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$botToken = "1383022125:AAE2f1svCUBa_Z57gXxUcL6CTNPRXEuKY2I";
$webSite = "https://api.telegram.org/bot" . $botToken;

$update = file_get_contents("php://input");
$update = json_decode($update, TRUE);

$chatId = "-1001981902360";
$message = $update["message"]["text"];

$url="https://live.financialjuice.com/FJService.asmx/Startup?info=%22EAAAAC6qHO%2FeBUZ05qlPY5XW4m%2BBm9ZyT5mURrFOXEjWWuZGQijiPRltdmem4mruN5mZDZsNXX34purnD0CIkhSyRnxhzSnkqtYMNgtzhwp0tZab0LW9jlLfF5WVyYhP9o9PLAJLDjEKEOY3q9vDqi9n%2Fj8SPi%2F5PadK3CPjMdp72%2BNIzfUiou9Xb%2FmZJc9s5%2BR9ndm%2FCKNsnHE6muHlb6O531LjdL51EkUKS5k5fd3Kfx1qSybhG7dXVPJwR%2BaYIVWeW2ZDAVwZl%2FTY8PdXL0m36OdXjfFyfBBVRkWTQ3zqc8PL%22&TimeOffset=3.5&tabID=0&oldID=0&TickerID=0&FeedCompanyID=0&strSearch=&extraNID=0";



$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_HTTPHEADER, array(


    'Referer: https://www.financialjuice.com/',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.5414.120 Safari/537.36',
    'Content-Type: application/json',

));

// Executing curl
$response = curl_exec($curl);

echo "<br><br><br>";




// Decode the JSON file
$json_data = json_decode($response,true);
$json_data=$json_data["d"];
//var_dump($json_data);
$row = explode(',"', $json_data);
//var_dump($row);
$i=0;
$labelOn=0;
$message="";
$firstNews="";

foreach ($row as $value){
    if (preg_match('/Title"/',$value))
    {
        $value=str_replace('Title":"',"",$value);
        $value=str_replace('"',"",$value);
        $message=$value;
        //echo "<br>";
        //echo $value;
        $i++;
    }
    if(preg_match('/Labels":\[\]/',$value)){
        echo "vojod darad bedoneHashtag?!".isNews($message);
        if (!isNews($message))
        {
            echo "<br>send<br>";
            sendMessage($chatId,$message);
            insertNews($message);
        }
        echo $message;
        echo "<br>";
        $labelOn=0;
    }
    if(preg_match('/Labels":\["/',$value)&&preg_match('/"\]/',$value)){
        $label=str_replace('"]',"",$value);
        $label=str_replace('Labels":["',"",$label);
        $label=str_replace(' ',"_",$label);
        $message=$message."
#".$label;
        echo "vojod darad yek Hashtag?!".isNews($message);
        if (!isNews($message))
        {
            echo "<br>send<br>";
            sendMessage($chatId,$message);
            insertNews($message);
        }
        echo $message;
        echo "<br>";
        $labelOn=0;
    }
    else if(preg_match('/Labels":\["/',$value)){
        $label=str_replace('Labels":["',"",$value);
        $label=str_replace('"',"",$label);
        $label=str_replace(' ',"_",$label);
        $message=$message."
#".$label;
        $labelOn=1;
    }
    else if($labelOn==1&&!preg_match('/"]/',$value))
    {
        $label=str_replace('"',"",$value);
        $label=str_replace(' ',"_",$label);
        $message=$message." #".$label;
    }
    if($labelOn==1&&preg_match('/"]/',$value)){
        $label=str_replace('"]',"",$value);
        $label=str_replace(' ',"_",$label);
        $message=$message." #".$label;
        echo "vojod darad Chand Hashtag?!".isNews($message);
        if (!isNews($message))
        {
            echo "<br>send<br>";
            sendMessage($chatId,$message);
            insertNews($message);
        }
        echo $message;
        echo "<br>";
        $labelOn=0;
    }
    if($i==20){
        break;
    }
}





function isNews($title){
    $servername = "localhost";
    $username = "h181246_temp";
    $password = "00981920";
    $dbname = "h181246_temp";

    try
    {
        $con=new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //echo 'connected';
    }
    catch(PDOException $e)
    {
        echo '<br>'.$e->getMessage();
    }

//get the last record from the database
    $stmt = $con->query('SELECT title FROM news where title="'.$title.'" ');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(empty($rows[0]['title']))
        return 0;
    return 1;
}
function insertNews($news){
    $servername = "localhost";
    $username = "h181246_temp";
    $password = "00981920";
    $dbname = "h181246_temp";

    try
    {
        $con=new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //echo 'connected';
    }
    catch(PDOException $e)
    {
        echo '<br>'.$e->getMessage();
    }

    $stmt = $con->query('INSERT into news SET title = "'.$news.'";');

}

function sendMessage($chatId, $message)
{
    $url = $GLOBALS['webSite'] . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($message);
    file_get_contents($url);
}