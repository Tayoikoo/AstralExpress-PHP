<?php

namespace AstralPHP\sdkserver\mdk;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use AstralPHP\common\Logger;

class Mdk
{
    private const ACCOUNT_INFO = [
        "retcode"=> 0,
        "message"=> "OK",
        "data"=> [
            "account"=> [
                "area_code"=> "**",
                "email"=> "AstralPHP",
                "country"=> "virus",
                "is_email_verify"=> "1",
                "token"=> "securetokenfr",
                "uid"=> "69"
            ],
            "device_grant_required"=> false,
            "reactivate_required"=> false,
            "realperson_required"=> false,
            "safe_mobile_required"=> false,
        ],           
    ];    
    public static function register(array &$routes): void
    {
        // mdk_shield login
        $routes["/hkrpg_global/mdk/shield/api/login"] = function (
            ServerRequestInterface $request
        ) {
            Logger::log_dispatch("Received /shield/api/login");

            return new Response(
                200,
                ["Content-Type" => "application/json"],
                json_encode(self::ACCOUNT_INFO)
            );         
        };

        // mdk_shield verify
        $routes["/hkrpg_global/mdk/shield/api/verify"] = function (
            ServerRequestInterface $request
        ) {
            Logger::log_dispatch("Received api verify log request.");
            $queryParams = $request->getQueryParams();
            Logger::log_dispatch("Query params: " . json_encode($queryParams));                  
            return new Response(
                200,
                ["Content-Type" => "application/json"],
                json_encode(self::ACCOUNT_INFO)
            );         
        };      
        
        // login stuffs
        $routes["/mdk/shield/api/loginCaptcha"] = function (
            ServerRequestInterface $request
        ) {
            return new Response(
                200,
                ["Content-Type" => "application/json"],
                json_encode([
                    "retcode"=> 0,
                    "message"=> "OK",
                    "data"=> ["protocol"=> True, "qr_enabled"=> True, "log_level"=> "INFO"],                    
                ])
            );         
        };           
    }
}