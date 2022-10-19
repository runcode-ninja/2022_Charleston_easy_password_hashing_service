<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['lang'])) {
        $lang = $_POST['lang'];
        $_SESSION['lang'] = $lang;
        $res = array("error" => False, "message" => "Langauge Updated", "new_lang" => $lang);
    } else {
        $res = array("error" => True, "message" => "No Language Selected");
    }
} else {
    $res = array("error" => True, "message" => "Problem updating your Language");
}
echo json_encode($res);