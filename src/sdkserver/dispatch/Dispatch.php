<?php

namespace AstralPHP\sdkserver\dispatch;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use AstralPHP\common\Logger;

// Proto
use Gateserver;
use GlobalDispatchData;
use ServerData;

class Dispatch {
    public static function register(array &$routes, $config): void
    {
        $routes['/query_dispatch'] = function (ServerRequestInterface $request) use ($config) {
            Logger::log_dispatch("Received /query_dispatch request.");
            $queryParams = $request->getQueryParams();
            Logger::log_dispatch("Query params: " . json_encode($queryParams));            
            
            $rsp = new GlobalDispatchData();
            $rsp->setRetcode(0);
        
            $serverList = [];
            foreach ($config->game_servers as $serverConfig) {
                $server = new ServerData();
                $server->setName($serverConfig->name);
                $server->setTitle($serverConfig->title);
                $server->setEnvType($serverConfig->env_type);
                $server->setDispatchUrl($serverConfig->dispatch_url);
                $serverList[] = $server;
            }
            $rsp->setServerList($serverList);
            
            $serializedResponse = $rsp->serializeToString();
            $encodedResponse = base64_encode($serializedResponse);
        
            Logger::log_dispatch("Response prepared: " . $encodedResponse);
        
            return new Response(
                200,
                ["Content-Type" => "application/json"],
                $encodedResponse
            );
        };

        // query_gateway
        foreach ($config->game_servers as $regionName => $serverConfig) {
            $routes["/query_gateway/{$regionName}"] = function (ServerRequestInterface $request) use ($regionName, $serverConfig, $config) {
                Logger::log_dispatch('Received /query_gateway request.');
                $queryParams = $request->getQueryParams();
                Logger::log_dispatch("Query params: " . json_encode($queryParams));
                
                $version = $queryParams['version'] ?? null;
                Logger::log_dispatch("Version: $version");
                Logger::log_dispatch("Region: $regionName");
                
                if (!$version) {
                    return new Response(400, ["Content-Type" => "application/json"], json_encode(['error' => 'Missing version']));
                }

                $versionConfig = $config->versions[$version] ?? null;                
                Logger::log_dispatch("Version config: " . json_encode($versionConfig));
                
                if ($serverConfig) {
                    if ($versionConfig) {
                        // Prepare the Gateserver response
                        $rsp = new Gateserver();
                        $rsp->setIp($serverConfig->gateserver_ip);
                        $rsp->setPort($serverConfig->gateserver_port);
                        $rsp->setAssetBundleUrl($versionConfig->asset_bundle_url);
                        $rsp->setExResourceUrl($versionConfig->ex_resource_url);
                        $rsp->setLuaUrl($versionConfig->lua_url);
                        $rsp->setLuaVersion($versionConfig->lua_version);
                        $rsp->setIfixUrl($versionConfig->ifixUrl);
                        $rsp->setUseTcp(true);
                        $rsp->setUseDesignData(true);
                        $rsp->setUseNewNetworking(true);
                        $rsp->setEnableWatermark(true);
                        $rsp->setNetworkDiagnostic(true);
                        $rsp->setEnableAndroidMiddlePackage(true);
                        $rsp->setEnableVersionUpdate(true);
                        $rsp->setForbidRecharge(true);
                        $rsp->setCloseRedeemCode(true);
                        $rsp->setMODIBFHPMCP(true);
                        $rsp->setEnableSaveReplayFile(true);
                        $rsp->setIosExam(true);
                        $rsp->setEnableUploadBattleLog(true);
                        $rsp->setEventTrackingOpen(true);
                    } else {
                        $rsp = new Gateserver();
                        $rsp->setRetcode(9);
                        $rsp->setMsg("Forbidden version: $version");
                        Logger::log_dispatch("Forbidden version: $version");
                    }
                } else {
                    return new Response(404, ["Content-Type" => "application/json"], json_encode(['error' => 'Region not found']));
                }

                // Return the response, serialized and base64 encoded
                return new Response(
                    200,
                    ["Content-Type" => "application/json"],
                    base64_encode($rsp->serializeToString())
                );
            };
        }
    }
}