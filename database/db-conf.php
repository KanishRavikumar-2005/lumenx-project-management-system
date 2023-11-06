<?php

$iv = ":�%P�;�M"; //AES-256-CBC requires 16Bytes
//echo strlen($iv);
$key = "�b�B�8i�@0D�8i�@0�8i�@0DD�j:ՉUQ�=�8i�@0DQ�";
$Ekey = "�b�B�@0D�j:ՉUQ�=Q�JIsadj8i�@0D�j:ՉUQ�=Q�JIS";
$cipher = "aes-256-cbc";

function Encrypt($plaintext, $cipher, $key, $iv)
{
    return openssl_encrypt($plaintext, $cipher, $key, $options = 1, $iv);
}
function Decrypt($cyp, $cipher, $key, $iv)
{
    return openssl_decrypt($cyp, $cipher, $key, $options = 1, $iv);
}

/*
DEFAULT VALUE[Do not delete this comment, this will come useful]
$iv = ";�+�%gPK�";
$key = "�b�B�8i�@0D�j:ՉUQ�=Q�";
$Ekey = "�b�B�8i�@0D�j:ՉUQ�=Q�JIS";
$cipher = "aes-256-cbc";
*/
function reportError($error){
  echo "<script>console.error('$error')</script>";
}

function reportWarn($error){
  echo "<script>console.error('$error')</script>";
}

function redirect($loc){
  echo "<script>window.location.assign('$loc')</script>";
}
function reload(){
  echo "<script>window.location.assign(window.location.href)</script>";
}

function array_intersect_recursive($array1, $array2) {
    $result = [];
    foreach ($array2 as $key => $value) {
        if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
            $intersect = array_intersect_recursive($array1[$key], $value);
            if (!empty($intersect)) {
                $result[$key] = $intersect;
            }
        } elseif (isset($array1[$key]) && $array1[$key] === $value) {
            $result[$key] = $value;
        }
    }
    return $result;
}
//GET VALUES FROM A JSDB
class Jasper
{
    public function refresh()
    {
        echo "<script>window.location.assign(window.location.href)</script>";
    }

   public function create($jsdbname)
    {
        try {
            touch("$jsdbname.jdb");
            $jsdbf = fopen("$jsdbname.jdb", "w");
            $txt = Encrypt(
                "[]",
                $GLOBALS["cipher"],
                $GLOBALS["key"],
                $GLOBALS["iv"]
            );
            fwrite($jsdbf, $txt);
            fclose($jsdbf);
        } catch (Exception $e) {
            $errorMessage =
                "An error occurred while creating the database: " .
                $e->getMessage();
            reportError($errorMessage);
        }
    }

    public function get($file, $form = "")
    {
        try {
            $cipher_gt = $GLOBALS["cipher"];
            $key_gt = $GLOBALS["key"];
            $ekey_gt = $GLOBALS["Ekey"];
            $iv_gt = $GLOBALS["iv"];
            $get_data = file_get_contents($file . ".jdb");

            if ($get_data === false) {
                $errorMessage = "Failed to read the database file.";
                reportError($errorMessage);
                return null;
            }

            $scrypt = Decrypt($get_data, $cipher_gt, $key_gt, $iv_gt);
            $decrypt = Decrypt($scrypt, $cipher_gt, $ekey_gt, $iv_gt);
            $result = json_decode($decrypt, true);

            if (empty($result)) {
                $warningMessage = "The database is empty.";
                reportWarn($warningMessage);
                return [];
            } else {
                if ($form == "") {
                    return $result;
                } elseif ($form == "reverse") {
                    return array_reverse($result);
                }
            }
        } catch (Exception $e) {
            $errorMessage =
                "An error occurred while retrieving the data: " .
                $e->getMessage();
            reportError($errorMessage);
            return null;
        }
    }

     public function put($file, $content)
    {
        try {
            $cipher_gt = $GLOBALS["cipher"];
            $key_gt = $GLOBALS["key"];
            $ekey_gt = $GLOBALS["Ekey"];
            $iv_gt = $GLOBALS["iv"];
            $ycrypt = Encrypt($content, $cipher_gt, $ekey_gt, $iv_gt);
            $encrypt = Encrypt($ycrypt, $cipher_gt, $key_gt, $iv_gt);
            $result = file_put_contents($file . ".jdb", $encrypt);

            if ($result === false) {
                $errorMessage = "Failed to write data to the database.";
                reportError($errorMessage);
            }
        } catch (Exception $e) {
            $errorMessage =
                "An error occurred while writing data to the database: " .
                $e->getMessage();
            reportError($errorMessage);
        }
    }


    public function idgen($length = 10, $delim = "")
    {
        $characters =
            "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $randomString .=
                $characters[rand(0, $charactersLength - 1)] . $delim;
        }
        return $randomString;
    }

  public function uuid($length = 10, $delim = "")
  {
      $characters =
          "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $charactersLength = strlen($characters);
      $randomString = "";
      for ($i = 0; $i < $length; $i++) {
          $randomString .=
              $characters[rand(0, $charactersLength - 1)] . $delim;
      }
      return $randomString;
  }

    public function safe($value)
    {
        $value = htmlentities($value);
        return $value;
    }

   public function remove_row($file, $code)
    {
        $givnData = $code;
        $recvDD = $this->get($file);
        foreach ($recvDD as $key => $dd) {
            $result = [];
            if (array_intersect_recursive($dd, $givnData) === $givnData) {
                $result = $dd;
            }
            if (!empty($result)) {
                unset($recvDD[$key]);
                $finecd = json_encode(array_values($recvDD));
                $this->put($file, $finecd);
            }
        }
    }

    public function update_row($file, $code, $ucode)
    {
        $givnData = $code;
        $updData = $ucode;
        $dataR = $this->get($file);
        foreach ($dataR as $key => $dd) {
            $result = [];
            if (array_intersect_recursive($dd, $givnData) === $givnData) {
                $result = $dd;
            }
            if (!empty($result)) {
                $arrt_upd = $dataR[$key];
                foreach ($updData as $kk => $vv) {
                    $arrt_upd[$kk] = $vv;
                }
                $dataR[$key] = $arrt_upd;

                $finecd = json_encode(array_values($dataR));
                $this->put($file, $finecd);
            }
        }
    }

    public function add_row($file, $code)
    {
        $givnData = $code;
        $dataV = $this->get($file);
        $dataV[] = $givnData;
        $final = json_encode($dataV);
        $this->put($file, $final);
    }

  public function addJsonDirect($file, ...$code)
{
    $dataV = $this->get($file);

    if (count($code) === 1 && is_array($code[0])) {
        // If a single array is passed, convert it to an array of arrays
        $code = $code[0];
    }

    $dataV = array_merge($dataV, $code);
    $final = json_encode($dataV);
    $this->put($file, $final);
} 


    public function get_row($file, $code, $form = "")
    {
        $givnData = $code;
        $recvDD = $this->get($file);
        $mmed = [];
        foreach ($recvDD as $key => $dd) {
            if (array_intersect_recursive($dd, $givnData) === $givnData) {
                $mmed[] = $dd;
            }
        }
        if (empty($mmed)) {
            reportWarn("No matching rows found.");
            return [];
        } else {
            if ($form == "") {
                return $mmed;
            } elseif ($form == "reverse") {
                return array_reverse($mmed);
            }
        }
    }




 public function getKeys($file)
{
    $result = $this->get($file);

    if ($result === null) {
        return [];
    } else {
        $keys = [];

        // Function to recursively remove duplicates and include nested keys
        $removeDuplicatesRecursive = function ($array, $parentKey = '') use (&$removeDuplicatesRecursive, &$keys) {
            foreach ($array as $key => $value) {
                $currentKey = ($parentKey !== '') ? $parentKey . '[' . $key . ']' : $key;

                if (is_array($value)) {
                    $removeDuplicatesRecursive($value, $currentKey);
                } else {
                    $keys[] = $currentKey;
                }
            }
        };

        // Remove duplicates and include nested keys
        $removeDuplicatesRecursive($result);

        // Return only the keys from the last array
        $lastArrayKeys = array_values(array_unique(array_slice($keys, -count($result))));

      $nka = $this->displayInputArray($lastArrayKeys);
        return $nka;
    }
}

private function displayInputArray($inputArray) {
    $result = [];

    foreach ($inputArray as $item) {
        preg_match('/^\d+\[(.*?)\]$/', $item, $matches);

        if (isset($matches[1])) {
            $keys = explode('][', $matches[1]);

            $nestedArray = &$result;

            foreach ($keys as $key) {
                if (!isset($nestedArray[$key])) {
                    $nestedArray[$key] = [];
                }

                $nestedArray = &$nestedArray[$key];
            }
        }
    }

    return $result;
}



    public function decall($file, $tofl)
    {
        $cipher_gt = $GLOBALS["cipher"];
        $key_gt = $GLOBALS["key"];
        $iv_gt = $GLOBALS["iv"];
        $get_data = file_get_contents($file . ".jdb");

        if ($get_data === false) {
            $errorMessage = "Failed to read the database file.";
            reportError($errorMessage);
            return null;
        }

        $decrypt = Decrypt($get_data, $cipher_gt, $key_gt, $iv_gt);
        $result = file_put_contents($tofl . ".json", $decrypt);

        if ($result === false) {
            $errorMessage =
                "Failed to write decrypted data to the destination file.";
            reportError($errorMessage);
        }
    }

    public function encall($file, $tofl)
    {
        $cipher_gt = $GLOBALS["cipher"];
        $key_gt = $GLOBALS["key"];
        $iv_gt = $GLOBALS["iv"];
        $content = file_get_contents($file . ".json");

        if ($content === false) {
            $errorMessage = "Failed to read the source file.";
            reportError($errorMessage);
            return null;
        }

        $encrypt = Encrypt($content, $cipher_gt, $key_gt, $iv_gt);
        $result = file_put_contents($tofl . ".jdb", $encrypt);

        if ($result === false) {
            $errorMessage =
                "Failed to write encrypted data to the destination file.";
            reportError($errorMessage);
        }
    }
}

function onClick($buttonName, $callback, $optionalAction = null, $callbackParams = null, $customObject = null) {
    if (isset($_POST[$buttonName])) {
        if (is_array($callbackParams)) {
            call_user_func_array($callback, $callbackParams); 
        } else {
            $callback($callbackParams); 
        }
    } elseif ($optionalAction !== null) {
        $optionalAction(); 
    }
}
?>
