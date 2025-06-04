<?php

namespace AstralPHP\game\net;

use React\Socket\ConnectionInterface;
use Google\Protobuf\Internal\Message;
use AstralPHP\common\Logger;
use AstralPHP\game\net\protocol\ProtocolDictonary;

class PlayerSession {
    private $connection;
    private $commandMap = [];
    private $handlers = [];

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;

        // Initialize ProtocolDictonary from JSON
        ProtocolDictonary::init();

        // Build command map from ProtocolDictonary
        $this->commandMap = [];

        // Build handler lookup from filesystem
        $handlerDirectories = glob(__DIR__ . '/handlers/*', GLOB_ONLYDIR);
        $handlerMap = [];
        
        foreach ($handlerDirectories as $dir) {
            $namespace = '\\AstralPHP\\game\\net\\handlers\\' . basename($dir) . '\\';
            foreach (glob("$dir/On*.php") as $filePath) {
                $className = basename($filePath, '.php');
                $handlerMap[$className] = $namespace . $className;
            }
        }
        
        foreach (ProtocolDictonary::getAll() as $cmdId => $cmdName) {
            $messageClass = "{$cmdName}";
            $shortName = 'On' . preg_replace('/(CsReq|ScRsp|Notify)$/', '', $cmdName);
            $handlerClass = $handlerMap[$shortName] ?? null;
        
            if (class_exists($messageClass) && $handlerClass && class_exists($handlerClass)) {
                $this->commandMap[(int)$cmdId] = [
                    'message' => $messageClass,
                    'handler' => $handlerClass,
                    'cmd_name' => $cmdName,
                ];
            }
        }        
    }

    private function handlePacket(NetPacket $packet)
    {
        if (isset($this->commandMap[$packet->cmd_type])) {
    
            // Decode the Protobuf message
            $messageClass = $this->commandMap[$packet->cmd_type]['message'];
            $handlerClass = $this->commandMap[$packet->cmd_type]['handler'];
    
            try {
                // Decode the message
                $message = new $messageClass();
                $message->mergeFromString($packet->body);
    
                $handler = new $handlerClass($this);
                $handler->handle($this->connection, $message);
                return $message;
            } catch (\Exception $e) {
                Logger::log_gameserver("Failed to process message for cmd_type {$packet->cmd_type}: " . $e->getMessage());
                return null;
            }
        } else {
            $rawName = ProtocolDictonary::getProtocolNameFromId($packet->cmd_type);
            $cleanName = preg_replace('/(CsReq|ScRsp)$/', '', $rawName);
            
            if (ProtocolDictonary::isDummyPacket($cleanName)) {
                $this->sendDummy($packet->cmd_type);
            } else {
                Logger::log_gameserver("Unknown and unhandled packet cmd_type {$packet->cmd_type}.");
            }
            
        }
    }         

    public function processRequest($data)
    {
        // Parse the data into a NetPacket object
        $packet = NetPacket::fromBytes($data);
        
        if ($packet === null) {
            Logger::log_gameserver("Failed to parse packet.");
            return;
        }

        // Process the packet using the handlePacket method
        $this->handlePacket($packet);
    }    

    public function processIncomingData($data)
    {
        // Temporary buffer to hold data
        static $buffer = '';

        // Append the new data to the buffer
        $buffer .= $data;

        while (true) {
            $tail_magic_pos = strpos($buffer, pack('N', NetPacket::TAIL_MAGIC));

            if ($tail_magic_pos !== false) {
                $packet_data = substr($buffer, 0, $tail_magic_pos + 4);
                
                $this->processRequest($packet_data);

                $buffer = substr($buffer, $tail_magic_pos + 4);
            } else {
                break;
            }
        }
    }

    /**
     * Sends a packet over the connection.
     *
     * @param ConnectionInterface $socket The connection to send the packet over.
     * @param Message $response The Protobuf response message.
     * @return void
     */
    public function sendPacket(ConnectionInterface $socket, Message $response): void
    {
        $cmdId = ProtocolDictonary::getCmdIdByClass(get_class($response));
        if ($cmdId === null) {
            Logger::log_gameserver("Failed to send packet: Unknown cmdId for " . get_class($response));
            return;
        }
    
        $packet = new NetPacket($cmdId, [], $response->serializeToString());
        $packet->cmd_type = $cmdId;
    
        $socket->write($packet->encodeNetPacket($packet));
        Logger::log_packet("Sending response with cmdId {$cmdId}.");
    }

    public function sendDummy(int $req_cmd_type): void
    {
        $reqName = ProtocolDictonary::getProtocolNameFromId($req_cmd_type);
    
        $rspName = preg_replace('/CsReq$/', 'ScRsp', $reqName);
        $rspClass = "\\{$rspName}";
        $rspCmdType = ProtocolDictonary::getProtocolIdFromName($rspName);
    
        if (!$rspCmdType || !class_exists($rspClass)) {
            Logger::log_gameserver("No ScRsp class found for dummy req {$reqName}.");
            return;
        }
    
        $body = '';
        $response = new $rspClass();
    
        if ($response instanceof Message) {
            $body = $response->serializeToString();
        }
    
        $packet = new NetPacket($rspCmdType, [], $body);
        $packetBytes = $packet->encodeNetPacket($packet);
    
        $this->connection->write($packetBytes);
        Logger::dummy("Sending dummy {$rspName} (cmd_type: {$rspCmdType})");
    }
    
    
    /**
     * Stops the session and closes the connection.
     */
    public function stop()
    {
        $this->connection->close();
    }    
}