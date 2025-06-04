<?php

namespace AstralPHP\game\net;

use React\Socket\ConnectionInterface;
use Google\Protobuf\Internal\Message;
use AstralPHP\common\Logger;

class NetPacket
{
    const HEAD_MAGIC = 0x9D74C714;
    const TAIL_MAGIC = 0xD7A152C8;

    public $cmd_type;
    public $head;
    public $body;

    public function __construct($cmd_type, $head, $body)
    {
        $this->cmd_type = $cmd_type;
        $this->head = $head;
        $this->body = $body;
    }

    /**
     * Convert the NetPacket into binary data to be sent over the network.
     *
     * @return string The binary packet.
     */
    public static function toBytes(NetPacket $packet): string
    {
        $out = '';
        $out .= pack('N', self::HEAD_MAGIC);  // Header magic
        $out .= pack('n', $packet->cmd_type);  // Command type (2 bytes)
        $out .= pack('n', strlen($packet->head));  // Head length (2 bytes)
        $out .= pack('N', strlen($packet->body));  // Body length (4 bytes)
        $out .= $packet->head;  // Head data
        $out .= $packet->body;  // Body data
        $out .= pack('N', self::TAIL_MAGIC);  // Tail magic
        return $out;
    }

    /**
     * Read and parse a NetPacket from the incoming data stream.
     *
     * @param string $data The incoming binary data.
     * @return NetPacket The parsed NetPacket object.
     */
    public static function fromBytes($data): ?NetPacket
    {
        $head_magic = unpack('N', substr($data, 0, 4))[1];
        if ($head_magic !== self::HEAD_MAGIC) {
            Logger::log_gameserver("Invalid HEAD_MAGIC");
            return null;
        }

        $cmd_type = unpack('n', substr($data, 4, 2))[1];

        $head_length = unpack('n', substr($data, 6, 2))[1];
        $body_length = unpack('N', substr($data, 8, 4))[1];

        $head = substr($data, 12, $head_length);
        $body = substr($data, 12 + $head_length, $body_length);

        $tail_magic = unpack('N', substr($data, 12 + $head_length + $body_length, 4))[1];
        if ($tail_magic !== self::TAIL_MAGIC) {
            Logger::log_gameserver("Invalid TAIL_MAGIC");
            return null;
        }
        return new NetPacket($cmd_type, $head, $body);
    }

    /**
     * Encodes the NetPacket to bytes.
     *
     * @param NetPacket $packet The NetPacket to encode.
     * @return string The encoded packet.
     */
    public function encodeNetPacket(NetPacket $packet)
    {
        // Construct the packet bytes with the header and body
        $out = '';
        $out .= pack('N', 0x9D74C714);  
        $out .= pack('n', $packet->cmd_type);  
        $out .= pack('n', count($packet->head));
        $out .= pack('N', strlen($packet->body));
        $out .= implode('', $packet->head);
        $out .= $packet->body;
        $out .= pack('N', 0xD7A152C8);  

        return $out;
    }    
}
