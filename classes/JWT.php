<?php

class JWT {
    public function generate(array $header, array $payload, string $secret, int $validity = 86400) : string
    {
        if($validity >0){
            $now = new DateTime();
            $expiration = $now->getTimestamp() + $validity;
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $expiration;
        }
         // encodage en base64
         $base64Header = base64_encode(json_encode($header));
         $base64payload = base64_encode(json_encode($payload));
         // nettoie les données 
         $base64Header = str_replace(['+','/','='],['-','_',''],$base64Header);
         $base64payload = str_replace(['+','/','='],['-','_',''],$base64payload);
         // Generation de la signature 
         $secret = base64_encode(SECRETKEY);
         $signature = hash_hmac('sha256', $base64Header . '.'. $base64payload, 
         $secret,true);
         // cnettoyage
         $signature = str_replace(['+','/','='],['-','_',''],base64_encode($signature));
         $jwt = $base64Header . '.' . $base64payload . '.' . $signature;
         return $jwt;
    }
    public function check (string $token, string $secret) : bool
    {
        
        $header = $this->getHeader($token);
        $payload = $this->getHeader($token);
        // on genere un token de vérification
        $veriftoken = $this->generate($header,$payload,$secret,0);
        return $token === $veriftoken;
    }
    
    public function getHeader(string $token){
        
        $array = explode('.',$token);
        $header = json_decode(base64_decode($array[0],true));
        return $header;
    }
    public function getpayload(string $token){
        $array = explode('.',$token);
        $payload = json_decode(base64_decode($array[1],true));
        return $payload;
    }


}