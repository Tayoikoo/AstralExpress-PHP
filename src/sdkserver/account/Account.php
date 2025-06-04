<?php

namespace AstralPHP\sdkserver\account;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use AstralPHP\common\Logger;

class Account
{
    public static function register(array &$routes): void
    {
        // risky_api_check
        $routes["/account/risky/api/check"] = function (
            ServerRequestInterface $request
        ) {
            Logger::log_dispatch("Received /account/risky/api/check request.");

            $response = [
                "retcode" => 0,
                "message" => "OK",
                "data" => [
                    "id" => "none",
                    "action" => "ACTION_NONE",
                    "geetest" => null,
                ]
            ];

            return new Response(
                200,
                ["Content-Type" => "application/json"],
                json_encode($response)
            );
        };

        // Account Register
        $routes["/account/register"] = function(
            ServerRequestInterface $request
        ) {
            Logger::log_dispatch("Received 'Register Now' request.");

            $html = file_get_contents(__DIR__ . '/html/register_page.html');
            return new HtmlResponse($html);
        };
        
        // Sdk login
        $routes["/sdk/login"] = function (
            ServerRequestInterface $request
        ) {
            Logger::log_dispatch("Received /sdk/login request.");
            $queryParams = $request->getQueryParams();
            Logger::log_dispatch("Query params: " . json_encode($queryParams));            
            return new Response(
                200,
                ["Content-Type" => "application/json"],
                json_encode([
                    "retcode" => 0,
                    "data" => [
                        "uid" => "69",
                        "token" => "securetokenfr",
                        "email" => "PHP-SR@php.org",
                    ],
                ])
            );            
        };
        
        $routes["/hkrpg_global/combo/granter/login/v2/login"] = function (
            ServerRequestInterface $request
        ) {
            Logger::log_dispatch("Received combo granter login request.");    
            return new Response(
                200,
                ["Content-Type" => "application/json"],
                json_encode([
                    "retcode"=> 0,
                    "message"=> "OK",
                    "data"=> [
                        "account_type"=> 1,
                        "combo_id"=> "69",
                        "combo_token"=> "9065ad8507d5a1991cb6fddacac5999b780bbd92",
                        "data"=> "{\"guest\":false}",
                        "heartbeat"=> False,
                        "open_id"=> "69"
                    ],            
                ])
            );         
        };

        // Acoount Login By Password
        $routes["/hkrpg_global/account/ma-passport/api/appLoginByPassword"] = function(
            ServerRequestInterface $request
        ) {
            Logger::log_dispatch("Received 'Account Login By Password' request.");

            $queryParams = $request->getQueryParams();
            Logger::log_dispatch("Query params: " . json_encode($queryParams));
            return new Response(
                200,
                ["Content-Type" => "application/json"],
                json_encode([
                    "retcode"=> 0,
                    "message"=> "OK",
                    "data"=> [
                        "bind_email_action_ticket" => "",
                        "reactivate_action_token" => "",
                        "ext_user_info" => [
                            "birth"=> "0",
                        ],
                        "token"=> [
                            "token"=> "securetokenfr",
                            "token_type"=> "1",
                        ],
                        "user_info"=> [
                            "account_name"=> "AstralPHP",
                            "area_code"=> "**",
                            "email"=> "PHP-SR@php.org",
                            "country"=> "ID",
                            "is_email_verify"=> "1",
                            "aid"=> "1111"
                        ],
                    ],           
                ])
            );             
        };        
    }
}
