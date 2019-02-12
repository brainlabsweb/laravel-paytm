<?php

namespace Brainlabsweb\Paytm\src\core;

class Core
{

    /**
     * @param $input
     * @param $ky
     * @return string
     */
    public function encrypt_e($input, $ky)
    {
        $key = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        return openssl_encrypt($input, "AES-128-CBC", $key, 0, $iv);
    }

    /**
     * @param $crypt
     * @param $ky
     * @return string
     */
    public function decrypt_e($crypt, $ky)
    {
        $key = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        return openssl_decrypt($crypt, "AES-128-CBC", $key, 0, $iv);
    }

    /**
     * @param $length
     * @return string
     */
    public function generateSalt_e($length)
    {
        $random = "";
        srand((double)microtime() * 1000000);

        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";

        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }

        return $random;
    }

    /**
     * @param $value
     * @return string
     */
    public function checkString_e($value)
    {
        if ($value == 'null') {
            $value = '';
        }
        return $value;
    }

    /**
     * @param $arrayList
     * @param $key
     * @param int $sort
     * @return string
     */
    public function getChecksumFromArray($arrayList, $key, $sort = 1)
    {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        return $this->encrypt_e($hashString, $key);
    }

    /**
     * @param $str
     * @param $key
     * @return string
     */
    public function getChecksumFromString($str, $key)
    {

        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        return $this->encrypt_e($hashString, $key);
    }

    /**
     * @param $arrayList
     * @param $key
     * @param $checksumvalue
     * @return bool
     */
    public function verifychecksum_e($arrayList, $key, $checksumvalue)
    {
        $arrayList = $this->removeCheckSumParam($arrayList);
        ksort($arrayList);
        $str = $this->getArray2StrForVerify($arrayList);
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);

        $finalString = $str . "|" . $salt;

        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;

        if ($website_hash == $paytm_hash) {
            $validFlag = true;
        } else {
            $validFlag = false;
        }
        return $validFlag;
    }

    /**
     * @param $str
     * @param $key
     * @param $checksumvalue
     * @return bool
     */
    public function verifychecksum_eFromStr($str, $key, $checksumvalue)
    {
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);

        $finalString = $str . "|" . $salt;

        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;

        if ($website_hash == $paytm_hash) {
            $validFlag = true;
        } else {
            $validFlag = false;
        }
        return $validFlag;
    }

    /**
     * @param $arrayList
     * @return string
     */
    public function getArray2Str($arrayList)
    {
        $findme = 'REFUND';
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pos = strpos($value, $findme);
            $pospipe = strpos($value, $findmepipe);
            if ($pos !== false || $pospipe !== false) {
                continue;
            }

            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    /**
     * @param $arrayList
     * @return string
     */
    public function getRefundArray2Str($arrayList)
    {
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pospipe = strpos($value, $findmepipe);
            if ($pospipe !== false) {
                continue;
            }

            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    /**
     * @param $arrayList
     * @return string
     */
    public function getArray2StrForVerify($arrayList)
    {
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }

    /**
     * @param $paramList
     * @param $key
     * @return string
     */
    public function redirect2PG($paramList, $key)
    {
        $hashString = $this->getchecksumFromArray($paramList);
        return $this->encrypt_e($hashString, $key);
    }

    /**
     * @param $arrayList
     * @return mixed
     */
    public function removeCheckSumParam($arrayList)
    {
        if (isset($arrayList["CHECKSUMHASH"])) {
            unset($arrayList["CHECKSUMHASH"]);
        }
        return $arrayList;
    }

    /**
     * @param $url
     * @param $requestParamList
     * @return mixed
     */
    public function getTxnStatus($url, $requestParamList)
    {
        return $this->callAPI($url, $requestParamList);
    }

    /**
     * @param $url
     * @param $requestParamList
     * @return mixed
     */
    public function getTxnStatusNew($url, $requestParamList)
    {
        return $this->callNewAPI($url, $requestParamList);
    }

    /**
     * @param $requestParamList
     * @return mixed
     */
    public function initiateTxnRefund($requestParamList)
    {
        $CHECKSUM = $this->getRefundChecksumFromArray($requestParamList, PAYTM_MERCHANT_KEY, 0);
        $requestParamList["CHECKSUM"] = $CHECKSUM;
        return $this->callAPI(PAYTM_REFUND_URL, $requestParamList);
    }

    /**
     * @param $apiURL
     * @param $requestParamList
     * @return mixed
     */
    public function callAPI($apiURL, $requestParamList)
    {
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        return json_decode($jsonResponse, true);
    }

    /**
     * @param $apiURL
     * @param $requestParamList
     * @return mixed
     */
    public function callNewAPI($apiURL, $requestParamList)
    {
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        return json_decode($jsonResponse, true);
    }

    /**
     * @param $arrayList
     * @param $key
     * @param int $sort
     * @return string
     */
    public function getRefundChecksumFromArray($arrayList, $key, $sort = 1)
    {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getRefundArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        return $this->encrypt_e($hashString, $key);
    }


    /**
     * @param $refundApiURL
     * @param $requestParamList
     * @return mixed
     */
    public function callRefundAPI($refundApiURL, $requestParamList)
    {
        $JsonData = json_encode($requestParamList);
        $postData = 'JsonData=' . urlencode($JsonData);
        $ch = curl_init($refundApiURL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $jsonResponse = curl_exec($ch);

        return json_decode($jsonResponse, true);
    }

}
