<?php
class Encryption {
    var $skey   = "g&%(#gfddo87"; // you can change it
    
    public  function safe_b64encode($string) {
    
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
    
    public  function encode($value){ 
        #todo
        if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext)); 
    }
    
    public function decode($value){
        #todo
        if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

    // original file code starts here
     function encrypt($str)
    {
        //Key For security
        // $EncKey = "g&%(#gfddo87"; supports only 8 digits
        $EncKey = "g&%(#gfd";
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($str) % $block)) < $block) {
            $str .= str_repeat(chr($pad), $pad);
        }
        return base64_encode(mcrypt_encrypt(MCRYPT_DES, $EncKey, $str, MCRYPT_MODE_ECB));
    }


    function decrypt($str)
    {
        $EncKey = "g&%(#gfd";
        $str = mcrypt_decrypt(MCRYPT_DES, $EncKey, base64_decode($str), MCRYPT_MODE_ECB);
        # Strip padding out.
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match(
                '/' . chr($pad) . '{' . $pad . '}$/', $str
            )
        ) {
            return substr($str, 0, strlen($str) - $pad);
        }
        return $str;
    }

    function DecryptCCLastFirst4($pCCNumber) {

        $Hash=array();

        $Hash['First']    = substr($pCCNumber,0,4);

        $Hash['Last'] = substr($pCCNumber,strlen($pCCNumber)-4,4);

        return $Hash;
    }


    // original file code ends here.



}

?>