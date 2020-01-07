<?php

namespace App\Helpers;

class Crypto
{
    const ABC = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    /**
     * @param $str
     * @param null $password
     * @return bool|string
     */
    public static function base64_encrypt($str,$password=null){
        if (is_null($password) || empty($password)) {
            $password = static::getDefaultPassword();
        }
        $r='';
        $md=$password?substr(md5($password),0,16):'';
        $str=base64_encode($md.$str);
        $abc=static::ABC;
        $a=str_split('+/='.$abc);
        $b=strrev('-_='.$abc);
        if($password){
            $b=static::mixing_passw($b,$password);
        }else{
            $r=rand(10,65);
            $b=mb_substr($b,$r).mb_substr($b,0,$r);
        }
        $s='';
        $b=str_split($b);
        $str=str_split($str);
        $lens=count($str);
        $lena=count($a);
        for($i=0;$i<$lens;$i++){
            for($j=0;$j<$lena;$j++){
                if($str[$i]==$a[$j]){
                    $s.=$b[$j];
                }
            };
        };
        return $s.$r;
    }

    public static function base64_decrypt($str,$password=null){
        if (is_null($password) || empty($password)) {
            $password = static::getDefaultPassword();
        }
        $abc=static::ABC;
        $a=str_split('+/='.$abc);
        $b=strrev('-_='.$abc);
        if($password){
            $b=static::mixing_passw($b,$password);
        }else{
            $r=mb_substr($str,-2);
            $str=mb_substr($str,0,-2);
            $b=mb_substr($b,$r).mb_substr($b,0,$r);
        }
        $s='';
        $b=str_split($b);
        $str=str_split($str);
        $lens=count($str);
        $lenb=count($b);
        for($i=0;$i<$lens;$i++){
            for($j=0;$j<$lenb;$j++){
                if($str[$i]==$b[$j]){
                    $s.=$a[$j];
                }
            };
        };
        $s=base64_decode($s);
        if($password&&substr($s,0,16)==substr(md5($password),0,16)){
            return substr($s,16);
        }else{
            return $s;
        }
    }

    /**
     * @param $b
     * @param $password
     * @return string
     */
    protected static function mixing_passw($b,$password)
    {
        $s = '';
        $c = $b;
        $b = str_split($b);
        $password = str_split(sha1($password));
        $lenp = count($password);
        $lenb = count($b);
        for ($i = 0; $i < $lenp; $i++) {
            for ($j = 0; $j < $lenb; $j++) {
                if ($password[$i] == $b[$j]) {
                    $c = str_replace($b[$j], '', $c);
                    if (!preg_match('/' . $b[$j] . '/', $s)) {
                        $s .= $b[$j];
                    }
                }
            };
        };
        return $c . '' . $s;
    }

    protected static function getDefaultPassword()
    {
        $password = config('app.key');
        $base64 = 'base64:';
        $base64L = strlen($base64);
        if (substr($password,0,$base64L)===$base64)
        {
            $password = substr($password,$base64L);
        }
        return $password;
    }
}