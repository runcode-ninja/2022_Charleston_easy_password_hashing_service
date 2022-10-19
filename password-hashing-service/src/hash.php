<?php
session_start();

class Hasher {

    public $password;
    public $algos = array(PASSWORD_BCRYPT, PASSWORD_ARGON2I, PASSWORD_ARGON2ID);
    public $hashes = array();

    public function run_hashes() {
        foreach ($this->algos as $algo) {
            $hash_type = $this->get_hash_type($algo);
            $hash = password_hash($this->password, $algo);
            //echo "$hash_type --> $hash<br>";
            $this->hashes[$hash_type] = $hash;
        }
    }

    public function get_hash_type($algo) {
        if ($algo == PASSWORD_BCRYPT) {
            return 'BCrypt';
        } else if ($algo == PASSWORD_ARGON2I) {
            return 'Argon2';
        } else if ($algo == PASSWORD_ARGON2ID) {
            return 'Argon2id';
        }
        return 'Unknown';
    }

};

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
            $hasher = new Hasher();
            $hasher->password = $password;
            $hasher->run_hashes();
            include($_SESSION['lang'] . ".php");
            $translator = new Translator();
            $translator->text = "Your password hash results";
            $translator->translate();
            $res = array(
                "error" => False, 
                "hashes" => $hasher->hashes, 
                "password" => $hasher->password,
                "message" => $translator->translation
            );
        } else if (isset($_POST['is_bulk'])) {
            
            $uploaddir = '/var/www/uploads/';
            $original_filename = basename($_FILES['file']['name']);
            $parts = explode('.', $original_filename);
            $ext = '.' . end($parts);
            $filename = sha1($original_filename) . $ext;
            $uploadfile = $uploaddir . $filename;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                include($_SESSION['lang'] . ".php");
                $hasher = new Hasher();
                $translator = new Translator();
                $translator->text = "Your password hash results";
                $translator->translate();
                $hash_results = array();
                $contents = file('/var/www/uploads/' . $filename);
                $cnt = 0;
                foreach($contents as $password) {
                    $password = trim($password);
                    $hasher->password = $password;
                    $hasher->run_hashes();
                    $hash_results[$password] = $hasher->hashes;
                    $cnt++;
                    if ($cnt == 5) {
                        break;
                    }
                }

                $res = array(
                    "error" => False, 
                    "hashes" => $hash_results,
                    "message" => $translator->translation
                );

            } else {
                $res = array("error" => True, "message" => "Failed to move file to upload dir");
            }


        } else {
            $res = array("error" => True, "message" => "No password was sent");
        }
    } else {
        $res = array("error" => True, "message" => "There was a problem");
    }
    echo json_encode($res);
} catch (Exception $e) {
    $res = array("error" => True, "message" => $e->getMessage());
    echo json_encode($res);
}