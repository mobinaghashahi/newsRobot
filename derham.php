<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("Asia/Tehran");
print_r(getdate());
$botToken = "6118892729:AAGnT48LBLv9drP0m8EYWZkczSiu9twbw48";
$webSite = "https://api.telegram.org/bot" . $botToken;

$update = file_get_contents("php://input");
$update = json_decode($update, TRUE);

$chatId = "-1001624594768";
$message = $update["message"]["text"];

if(getdate()['hours']>=0&&getdate()['hours']<=24)
{
    $url="https://api.nobitex.ir/v2/orderbook/USDTIRT";

    while (empty($json_data["lastTradePrice"])) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);


// Telling curl to store JSON
// data in a variable instead
// of dumping on screen
        curl_setopt($curl,
            CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Accept: application/json",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/116.0",

            "Accept-Language:en-US,en;q=0.5",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"

        ));

// Executing curl
        $response = curl_exec($curl);

// Decode the JSON file
        $json_data = json_decode($response, true);

// Display data
        if (!empty($json_data["lastTradePrice"]))
        {
            $emoji_flags["LY"] = "\u{1F1F1}\u{1F1FE}";
            $price=substr((int)($json_data["lastTradePrice"]/retrieveConstantNumber()),0,-2);
            //رند کردن رقم آخر
            $price.=0;

            $data=" نرخ فروش درهم دبی ".counrtyFlags("AE")." ".$price." ".counrtyFlags("AE");
            sendMessage($chatId,$data);
            saveToDataBase($price);
            
            /*if(lastPrice()!=$price){
                sendMessage($chatId,$data);
                saveToDataBase($price);
            }else if(lastHours()!=getdate()['hours']){
                sendMessage($chatId,$data);
                saveToDataBase($price);
            }*/
        }


        curl_close($curl);
    }






}

function sendMessage($chatId, $message)
{
    $url = $GLOBALS['webSite'] . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($message);
    file_get_contents($url);
}

function saveToDataBase($price){
    $servername = "localhost";
    $username = "adlyst_mobin";
    $password = "Mobin.mobin7060";
    $dbname = "h181246_temp";

    try
    {
        $con=new PDO("mysql:host=$servername;dbname=adlyst_robot",$username,$password);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //echo 'connected';
    }
    catch(PDOException $e)
    {
        echo '<br>'.$e->getMessage();
    }

//get the last record from the database
    $stmt = $con->query("INSERT INTO derham (price, hours, minutes)
VALUES (".$price.", ".getdate()['hours'].",".getdate()['minutes'].");");
    $stmt->execute();
}

function lastPrice(){
    $servername = "localhost";
    $username = "adlyst_mobin";
    $password = "Mobin.mobin7060";
    $dbname = "h181246_temp";

    try
    {
        $con=new PDO("mysql:host=$servername;dbname=adlyst_robot",$username,$password);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //echo 'connected';
    }
    catch(PDOException $e)
    {
        echo '<br>'.$e->getMessage();
    }

//get the last record from the database
    $stmt = $con->query("SELECT price FROM derham ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows[0]['price'];

}

function lastHours(){
    $servername = "localhost";
    $username = "adlyst_mobin";
    $password = "Mobin.mobin7060";
    $dbname = "h181246_temp";

    try
    {
        $con=new PDO("mysql:host=$servername;dbname=adlyst_robot",$username,$password);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //echo 'connected';
    }
    catch(PDOException $e)
    {
        echo '<br>'.$e->getMessage();
    }

//get the last record from the database
    $stmt = $con->query("SELECT hours FROM derham ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows[0]['hours'];

}
function retrieveConstantNumber(){
    $servername = "localhost";
    $username = "adlyst_mobin";
    $password = "Mobin.mobin7060";
    $dbname = "h181246_temp";

    try
    {
        $con=new PDO("mysql:host=$servername;dbname=adlyst_robot",$username,$password);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //echo 'connected';
    }
    catch(PDOException $e)
    {
        echo '<br>'.$e->getMessage();
    }

//get the last record from the database
    $stmt = $con->query("SELECT const FROM const ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows[0]['const'];

}

function counrtyFlags($name){
    $emoji_flags = array();

$emoji_flags["AD"] = "\u{1F1E6}\u{1F1E9}";$emoji_flags["AE"] = "\u{1F1E6}\u{1F1EA}";$emoji_flags["AF"] = "\u{1F1E6}\u{1F1EB}";$emoji_flags["AG"] = "\u{1F1E6}\u{1F1EC}";$emoji_flags["AI"] = "\u{1F1E6}\u{1F1EE}";$emoji_flags["AL"] = "\u{1F1E6}\u{1F1F1}";$emoji_flags["AM"] = "\u{1F1E6}\u{1F1F2}";$emoji_flags["AO"] = "\u{1F1E6}\u{1F1F4}";$emoji_flags["AQ"] = "\u{1F1E6}\u{1F1F6}";$emoji_flags["AR"] = "\u{1F1E6}\u{1F1F7}";$emoji_flags["AS"] = "\u{1F1E6}\u{1F1F8}";$emoji_flags["AT"] = "\u{1F1E6}\u{1F1F9}";$emoji_flags["AU"] = "\u{1F1E6}\u{1F1FA}";$emoji_flags["AW"] = "\u{1F1E6}\u{1F1FC}";$emoji_flags["AX"] = "\u{1F1E6}\u{1F1FD}";$emoji_flags["AZ"] = "\u{1F1E6}\u{1F1FF}";$emoji_flags["BA"] = "\u{1F1E7}\u{1F1E6}";$emoji_flags["BB"] = "\u{1F1E7}\u{1F1E7}";$emoji_flags["BD"] = "\u{1F1E7}\u{1F1E9}";$emoji_flags["BE"] = "\u{1F1E7}\u{1F1EA}";$emoji_flags["BF"] = "\u{1F1E7}\u{1F1EB}";$emoji_flags["BG"] = "\u{1F1E7}\u{1F1EC}";$emoji_flags["BH"] = "\u{1F1E7}\u{1F1ED}";$emoji_flags["BI"] = "\u{1F1E7}\u{1F1EE}";$emoji_flags["BJ"] = "\u{1F1E7}\u{1F1EF}";$emoji_flags["BL"] = "\u{1F1E7}\u{1F1F1}";$emoji_flags["BM"] = "\u{1F1E7}\u{1F1F2}";$emoji_flags["BN"] = "\u{1F1E7}\u{1F1F3}";$emoji_flags["BO"] = "\u{1F1E7}\u{1F1F4}";$emoji_flags["BQ"] = "\u{1F1E7}\u{1F1F6}";$emoji_flags["BR"] = "\u{1F1E7}\u{1F1F7}";$emoji_flags["BS"] = "\u{1F1E7}\u{1F1F8}";$emoji_flags["BT"] = "\u{1F1E7}\u{1F1F9}";$emoji_flags["BV"] = "\u{1F1E7}\u{1F1FB}";$emoji_flags["BW"] = "\u{1F1E7}\u{1F1FC}";$emoji_flags["BY"] = "\u{1F1E7}\u{1F1FE}";$emoji_flags["BZ"] = "\u{1F1E7}\u{1F1FF}";$emoji_flags["CA"] = "\u{1F1E8}\u{1F1E6}";$emoji_flags["CC"] = "\u{1F1E8}\u{1F1E8}";$emoji_flags["CD"] = "\u{1F1E8}\u{1F1E9}";$emoji_flags["CF"] = "\u{1F1E8}\u{1F1EB}";$emoji_flags["CG"] = "\u{1F1E8}\u{1F1EC}";$emoji_flags["CH"] = "\u{1F1E8}\u{1F1ED}";$emoji_flags["CI"] = "\u{1F1E8}\u{1F1EE}";$emoji_flags["CK"] = "\u{1F1E8}\u{1F1F0}";$emoji_flags["CL"] = "\u{1F1E8}\u{1F1F1}";$emoji_flags["CM"] = "\u{1F1E8}\u{1F1F2}";$emoji_flags["CN"] = "\u{1F1E8}\u{1F1F3}";$emoji_flags["CO"] = "\u{1F1E8}\u{1F1F4}";$emoji_flags["CR"] = "\u{1F1E8}\u{1F1F7}";$emoji_flags["CU"] = "\u{1F1E8}\u{1F1FA}";$emoji_flags["CV"] = "\u{1F1E8}\u{1F1FB}";$emoji_flags["CW"] = "\u{1F1E8}\u{1F1FC}";$emoji_flags["CX"] = "\u{1F1E8}\u{1F1FD}";$emoji_flags["CY"] = "\u{1F1E8}\u{1F1FE}";$emoji_flags["CZ"] = "\u{1F1E8}\u{1F1FF}";$emoji_flags["DE"] = "\u{1F1E9}\u{1F1EA}";$emoji_flags["DG"] = "\u{1F1E9}\u{1F1EC}";$emoji_flags["DJ"] = "\u{1F1E9}\u{1F1EF}";$emoji_flags["DK"] = "\u{1F1E9}\u{1F1F0}";$emoji_flags["DM"] = "\u{1F1E9}\u{1F1F2}";$emoji_flags["DO"] = "\u{1F1E9}\u{1F1F4}";$emoji_flags["DZ"] = "\u{1F1E9}\u{1F1FF}";$emoji_flags["EC"] = "\u{1F1EA}\u{1F1E8}";$emoji_flags["EE"] = "\u{1F1EA}\u{1F1EA}";$emoji_flags["EG"] = "\u{1F1EA}\u{1F1EC}";$emoji_flags["EH"] = "\u{1F1EA}\u{1F1ED}";$emoji_flags["ER"] = "\u{1F1EA}\u{1F1F7}";$emoji_flags["ES"] = "\u{1F1EA}\u{1F1F8}";$emoji_flags["ET"] = "\u{1F1EA}\u{1F1F9}";$emoji_flags["FI"] = "\u{1F1EB}\u{1F1EE}";$emoji_flags["FJ"] = "\u{1F1EB}\u{1F1EF}";$emoji_flags["FK"] = "\u{1F1EB}\u{1F1F0}";$emoji_flags["FM"] = "\u{1F1EB}\u{1F1F2}";$emoji_flags["FO"] = "\u{1F1EB}\u{1F1F4}";$emoji_flags["FR"] = "\u{1F1EB}\u{1F1F7}";$emoji_flags["GA"] = "\u{1F1EC}\u{1F1E6}";$emoji_flags["GB"] = "\u{1F1EC}\u{1F1E7}";$emoji_flags["GD"] = "\u{1F1EC}\u{1F1E9}";$emoji_flags["GE"] = "\u{1F1EC}\u{1F1EA}";$emoji_flags["GF"] = "\u{1F1EC}\u{1F1EB}";$emoji_flags["GG"] = "\u{1F1EC}\u{1F1EC}";$emoji_flags["GH"] = "\u{1F1EC}\u{1F1ED}";$emoji_flags["GI"] = "\u{1F1EC}\u{1F1EE}";$emoji_flags["GL"] = "\u{1F1EC}\u{1F1F1}";$emoji_flags["GM"] = "\u{1F1EC}\u{1F1F2}";$emoji_flags["GN"] = "\u{1F1EC}\u{1F1F3}";$emoji_flags["GP"] = "\u{1F1EC}\u{1F1F5}";$emoji_flags["GQ"] = "\u{1F1EC}\u{1F1F6}";$emoji_flags["GR"] = "\u{1F1EC}\u{1F1F7}";$emoji_flags["GS"] = "\u{1F1EC}\u{1F1F8}";$emoji_flags["GT"] = "\u{1F1EC}\u{1F1F9}";$emoji_flags["GU"] = "\u{1F1EC}\u{1F1FA}";$emoji_flags["GW"] = "\u{1F1EC}\u{1F1FC}";$emoji_flags["GY"] = "\u{1F1EC}\u{1F1FE}";$emoji_flags["HK"] = "\u{1F1ED}\u{1F1F0}";$emoji_flags["HM"] = "\u{1F1ED}\u{1F1F2}";$emoji_flags["HN"] = "\u{1F1ED}\u{1F1F3}";$emoji_flags["HR"] = "\u{1F1ED}\u{1F1F7}";$emoji_flags["HT"] = "\u{1F1ED}\u{1F1F9}";$emoji_flags["HU"] = "\u{1F1ED}\u{1F1FA}";$emoji_flags["ID"] = "\u{1F1EE}\u{1F1E9}";$emoji_flags["IE"] = "\u{1F1EE}\u{1F1EA}";$emoji_flags["IL"] = "\u{1F1EE}\u{1F1F1}";$emoji_flags["IM"] = "\u{1F1EE}\u{1F1F2}";$emoji_flags["IN"] = "\u{1F1EE}\u{1F1F3}";$emoji_flags["IO"] = "\u{1F1EE}\u{1F1F4}";$emoji_flags["IQ"] = "\u{1F1EE}\u{1F1F6}";$emoji_flags["IR"] = "\u{1F1EE}\u{1F1F7}";$emoji_flags["IS"] = "\u{1F1EE}\u{1F1F8}";$emoji_flags["IT"] = "\u{1F1EE}\u{1F1F9}";$emoji_flags["JE"] = "\u{1F1EF}\u{1F1EA}";$emoji_flags["JM"] = "\u{1F1EF}\u{1F1F2}";$emoji_flags["JO"] = "\u{1F1EF}\u{1F1F4}";$emoji_flags["JP"] = "\u{1F1EF}\u{1F1F5}";$emoji_flags["KE"] = "\u{1F1F0}\u{1F1EA}";$emoji_flags["KG"] = "\u{1F1F0}\u{1F1EC}";$emoji_flags["KH"] = "\u{1F1F0}\u{1F1ED}";$emoji_flags["KI"] = "\u{1F1F0}\u{1F1EE}";$emoji_flags["KM"] = "\u{1F1F0}\u{1F1F2}";$emoji_flags["KN"] = "\u{1F1F0}\u{1F1F3}";$emoji_flags["KP"] = "\u{1F1F0}\u{1F1F5}";$emoji_flags["KR"] = "\u{1F1F0}\u{1F1F7}";$emoji_flags["KW"] = "\u{1F1F0}\u{1F1FC}";$emoji_flags["KY"] = "\u{1F1F0}\u{1F1FE}";$emoji_flags["KZ"] = "\u{1F1F0}\u{1F1FF}";$emoji_flags["LA"] = "\u{1F1F1}\u{1F1E6}";$emoji_flags["LB"] = "\u{1F1F1}\u{1F1E7}";$emoji_flags["LC"] = "\u{1F1F1}\u{1F1E8}";$emoji_flags["LI"] = "\u{1F1F1}\u{1F1EE}";$emoji_flags["LK"] = "\u{1F1F1}\u{1F1F0}";$emoji_flags["LR"] = "\u{1F1F1}\u{1F1F7}";$emoji_flags["LS"] = "\u{1F1F1}\u{1F1F8}";$emoji_flags["LT"] = "\u{1F1F1}\u{1F1F9}";$emoji_flags["LU"] = "\u{1F1F1}\u{1F1FA}";$emoji_flags["LV"] = "\u{1F1F1}\u{1F1FB}";$emoji_flags["LY"] = "\u{1F1F1}\u{1F1FE}";$emoji_flags["MA"] = "\u{1F1F2}\u{1F1E6}";$emoji_flags["MC"] = "\u{1F1F2}\u{1F1E8}";$emoji_flags["MD"] = "\u{1F1F2}\u{1F1E9}";$emoji_flags["ME"] = "\u{1F1F2}\u{1F1EA}";$emoji_flags["MF"] = "\u{1F1F2}\u{1F1EB}";$emoji_flags["MG"] = "\u{1F1F2}\u{1F1EC}";$emoji_flags["MH"] = "\u{1F1F2}\u{1F1ED}";$emoji_flags["MK"] = "\u{1F1F2}\u{1F1F0}";$emoji_flags["ML"] = "\u{1F1F2}\u{1F1F1}";$emoji_flags["MM"] = "\u{1F1F2}\u{1F1F2}";$emoji_flags["MN"] = "\u{1F1F2}\u{1F1F3}";$emoji_flags["MO"] = "\u{1F1F2}\u{1F1F4}";$emoji_flags["MP"] = "\u{1F1F2}\u{1F1F5}";$emoji_flags["MQ"] = "\u{1F1F2}\u{1F1F6}";$emoji_flags["MR"] = "\u{1F1F2}\u{1F1F7}";$emoji_flags["MS"] = "\u{1F1F2}\u{1F1F8}";$emoji_flags["MT"] = "\u{1F1F2}\u{1F1F9}";$emoji_flags["MU"] = "\u{1F1F2}\u{1F1FA}";$emoji_flags["MV"] = "\u{1F1F2}\u{1F1FB}";$emoji_flags["MW"] = "\u{1F1F2}\u{1F1FC}";$emoji_flags["MX"] = "\u{1F1F2}\u{1F1FD}";$emoji_flags["MY"] = "\u{1F1F2}\u{1F1FE}";$emoji_flags["MZ"] = "\u{1F1F2}\u{1F1FF}";$emoji_flags["NA"] = "\u{1F1F3}\u{1F1E6}";$emoji_flags["NC"] = "\u{1F1F3}\u{1F1E8}";$emoji_flags["NE"] = "\u{1F1F3}\u{1F1EA}";$emoji_flags["NF"] = "\u{1F1F3}\u{1F1EB}";$emoji_flags["NG"] = "\u{1F1F3}\u{1F1EC}";$emoji_flags["NI"] = "\u{1F1F3}\u{1F1EE}";$emoji_flags["NL"] = "\u{1F1F3}\u{1F1F1}";$emoji_flags["NO"] = "\u{1F1F3}\u{1F1F4}";$emoji_flags["NP"] = "\u{1F1F3}\u{1F1F5}";$emoji_flags["NR"] = "\u{1F1F3}\u{1F1F7}";$emoji_flags["NU"] = "\u{1F1F3}\u{1F1FA}";$emoji_flags["NZ"] = "\u{1F1F3}\u{1F1FF}";$emoji_flags["OM"] = "\u{1F1F4}\u{1F1F2}";$emoji_flags["PA"] = "\u{1F1F5}\u{1F1E6}";$emoji_flags["PE"] = "\u{1F1F5}\u{1F1EA}";$emoji_flags["PF"] = "\u{1F1F5}\u{1F1EB}";$emoji_flags["PG"] = "\u{1F1F5}\u{1F1EC}";$emoji_flags["PH"] = "\u{1F1F5}\u{1F1ED}";$emoji_flags["PK"] = "\u{1F1F5}\u{1F1F0}";$emoji_flags["PL"] = "\u{1F1F5}\u{1F1F1}";$emoji_flags["PM"] = "\u{1F1F5}\u{1F1F2}";$emoji_flags["PN"] = "\u{1F1F5}\u{1F1F3}";$emoji_flags["PR"] = "\u{1F1F5}\u{1F1F7}";$emoji_flags["PS"] = "\u{1F1F5}\u{1F1F8}";$emoji_flags["PT"] = "\u{1F1F5}\u{1F1F9}";$emoji_flags["PW"] = "\u{1F1F5}\u{1F1FC}";$emoji_flags["PY"] = "\u{1F1F5}\u{1F1FE}";$emoji_flags["QA"] = "\u{1F1F6}\u{1F1E6}";$emoji_flags["RE"] = "\u{1F1F7}\u{1F1EA}";$emoji_flags["RO"] = "\u{1F1F7}\u{1F1F4}";$emoji_flags["RS"] = "\u{1F1F7}\u{1F1F8}";$emoji_flags["RU"] = "\u{1F1F7}\u{1F1FA}";$emoji_flags["RW"] = "\u{1F1F7}\u{1F1FC}";$emoji_flags["SA"] = "\u{1F1F8}\u{1F1E6}";$emoji_flags["SB"] = "\u{1F1F8}\u{1F1E7}";$emoji_flags["SC"] = "\u{1F1F8}\u{1F1E8}";$emoji_flags["SD"] = "\u{1F1F8}\u{1F1E9}";$emoji_flags["SE"] = "\u{1F1F8}\u{1F1EA}";$emoji_flags["SG"] = "\u{1F1F8}\u{1F1EC}";$emoji_flags["SH"] = "\u{1F1F8}\u{1F1ED}";$emoji_flags["SI"] = "\u{1F1F8}\u{1F1EE}";$emoji_flags["SJ"] = "\u{1F1F8}\u{1F1EF}";$emoji_flags["SK"] = "\u{1F1F8}\u{1F1F0}";$emoji_flags["SL"] = "\u{1F1F8}\u{1F1F1}";$emoji_flags["SM"] = "\u{1F1F8}\u{1F1F2}";$emoji_flags["SN"] = "\u{1F1F8}\u{1F1F3}";$emoji_flags["SO"] = "\u{1F1F8}\u{1F1F4}";$emoji_flags["SR"] = "\u{1F1F8}\u{1F1F7}";$emoji_flags["SS"] = "\u{1F1F8}\u{1F1F8}";$emoji_flags["ST"] = "\u{1F1F8}\u{1F1F9}";$emoji_flags["SV"] = "\u{1F1F8}\u{1F1FB}";$emoji_flags["SX"] = "\u{1F1F8}\u{1F1FD}";$emoji_flags["SY"] = "\u{1F1F8}\u{1F1FE}";$emoji_flags["SZ"] = "\u{1F1F8}\u{1F1FF}";$emoji_flags["TC"] = "\u{1F1F9}\u{1F1E8}";$emoji_flags["TD"] = "\u{1F1F9}\u{1F1E9}";$emoji_flags["TF"] = "\u{1F1F9}\u{1F1EB}";$emoji_flags["TG"] = "\u{1F1F9}\u{1F1EC}";$emoji_flags["TH"] = "\u{1F1F9}\u{1F1ED}";$emoji_flags["TJ"] = "\u{1F1F9}\u{1F1EF}";$emoji_flags["TK"] = "\u{1F1F9}\u{1F1F0}";$emoji_flags["TL"] = "\u{1F1F9}\u{1F1F1}";$emoji_flags["TM"] = "\u{1F1F9}\u{1F1F2}";$emoji_flags["TN"] = "\u{1F1F9}\u{1F1F3}";$emoji_flags["TO"] = "\u{1F1F9}\u{1F1F4}";$emoji_flags["TR"] = "\u{1F1F9}\u{1F1F7}";$emoji_flags["TT"] = "\u{1F1F9}\u{1F1F9}";$emoji_flags["TV"] = "\u{1F1F9}\u{1F1FB}";$emoji_flags["TW"] = "\u{1F1F9}\u{1F1FC}";$emoji_flags["TZ"] = "\u{1F1F9}\u{1F1FF}";$emoji_flags["UA"] = "\u{1F1FA}\u{1F1E6}";$emoji_flags["UG"] = "\u{1F1FA}\u{1F1EC}";$emoji_flags["UM"] = "\u{1F1FA}\u{1F1F2}";$emoji_flags["US"] = "\u{1F1FA}\u{1F1F8}";$emoji_flags["UY"] = "\u{1F1FA}\u{1F1FE}";$emoji_flags["UZ"] = "\u{1F1FA}\u{1F1FF}";$emoji_flags["VA"] = "\u{1F1FB}\u{1F1E6}";$emoji_flags["VC"] = "\u{1F1FB}\u{1F1E8}";$emoji_flags["VE"] = "\u{1F1FB}\u{1F1EA}";$emoji_flags["VG"] = "\u{1F1FB}\u{1F1EC}";$emoji_flags["VI"] = "\u{1F1FB}\u{1F1EE}";$emoji_flags["VN"] = "\u{1F1FB}\u{1F1F3}";$emoji_flags["VU"] = "\u{1F1FB}\u{1F1FA}";$emoji_flags["WF"] = "\u{1F1FC}\u{1F1EB}";$emoji_flags["WS"] = "\u{1F1FC}\u{1F1F8}";$emoji_flags["XK"] = "\u{1F1FD}\u{1F1F0}";$emoji_flags["YE"] = "\u{1F1FE}\u{1F1EA}";$emoji_flags["YT"] = "\u{1F1FE}\u{1F1F9}";$emoji_flags["ZA"] = "\u{1F1FF}\u{1F1E6}";$emoji_flags["ZM"] = "\u{1F1FF}\u{1F1F2}";$emoji_flags["ZW"] = "\u{1F1FF}\u{1F1FC}";
return $emoji_flags[$name];
}