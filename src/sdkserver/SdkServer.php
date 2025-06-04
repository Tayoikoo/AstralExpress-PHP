<?php

namespace AstralPHP\sdkserver;

use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use AstralPHP\common\Logger;
use Throwable;
use Exception;

// Routes
use AstralPHP\sdkserver\account\Account;
use AstralPHP\sdkserver\dispatch\Dispatch;
use AstralPHP\sdkserver\mdk\Mdk;

class SdkServer {
    private array $routes = [];
    private Config $config;
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function init(): void
    {
        try {
            $this->config = Config::init_config();
        } catch (Exception $e) {
            $this->logger->error("Failed to initialize configuration: " . $e->getMessage());
            exit(1);
        }

        $this->registerRoutes();
        $this->startHttpServer();
    }

    private function registerRoutes(): void
    {
        $this->routes['/'] = function (ServerRequestInterface $request) {
            return new Response(
                200,
                ["Content-Type" => "application/json"],
                json_encode(['code' => 0, 'message' => 'OK'])
            );
        };

        // Routes
        Account::register($this->routes);
        Dispatch::register($this->routes, $this->config);
        Mdk::register($this->routes);
    }

    private function startHttpServer(): void
    {
        $httpServer = new HttpServer(function (ServerRequestInterface $request) {
            $path = $request->getUri()->getPath();
            $handler = $this->routes[$path] ?? null;

            if ($handler) {
                try {
                    return call_user_func($handler, $request);
                } catch (Throwable $e) {
                    $this->logger->error("Exception: " . $e->getMessage());
                    return new Response(500, [], "Internal Server Error");
                }
            }

            return new HtmlResponse("404 Not Found", 404);
        });

        $socket = new SocketServer("0.0.0.0:{$this->config->http_port}");
        $httpServer->listen($socket);

        Logger::log_dispatch("Dispatch Server running on port {$this->config->http_port}...\n");
    }    
}