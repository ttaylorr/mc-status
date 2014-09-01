#!/usr/bin/php

<?php
class MinecraftPingException extends Exception {}

class MinecraftPing {
  /*
   * Queries Minecraft server
   * Returns array on success, false on failure.
   *
   * WARNING: This method was added in snapshot 13w41a (Minecraft 1.7)
   *
   * Written by xPaw
   *
   * Website: http://xpaw.me
   * GitHub: https://github.com/xPaw/PHP-Minecraft-Query
   *
   */
  
  private $socket;
  private $serverAddress;
  private $serverPort;
  private $timeout;
  
  public function __construct($hostname, $port = 25565, $timeout = 2) {
    $this->serverAddress = $hostname;
    $this->serverPort = (int) $port;
    $this->timeout = (int) $timeout;
    
    $this->connect( );
  }
  
  public function __destruct() {
    $this->close( );
  }

  public function close() {
    if ($this->socket !== null) {
      fclose( $this->socket );

      $this->socket = null;
    }
  }
  
  public function connect() {
    $connectTimeout = $this->timeout;
    $this->socket = @fsockopen( $this->serverAddress, $this->serverPort, $errno, $errstr, $connectTimeout );

    if (!$this->socket) throw new MinecraftPingException( "Failed to connect or create a socket: $errno ($errstr)" );

    // Set Read/Write timeout
    stream_set_timeout( $this->socket, $this->timeout );
  }
  
  public function query() {
    $TimeStart = microtime(true); // for read timeout purposes
    
    // See http://wiki.vg/Protocol (Status Ping)
    $Data = "\x00"; // packet ID = 0 (varint)
    
    $Data .= "\x04"; // Protocol version (varint)
    $Data .= Pack( 'c', StrLen( $this->serverAddress ) ) . $this->serverAddress; // Server (varint len + UTF-8 addr)
    $Data .= Pack( 'n', $this->serverPort ); // Server port (unsigned short)
    $Data .= "\x01"; // Next state: status (varint)
    
    $Data = Pack( 'c', StrLen( $Data ) ) . $Data; // prepend length of packet ID + data
    
    fwrite( $this->socket, $Data ); // handshake
    fwrite( $this->socket, "\x01\x00" ); // status ping
    
    $Length = $this->readVarInt( ); // full packet length
    
    if( $Length < 10 )
    {
      return FALSE;
    }
    
    fgetc( $this->socket ); // packet type, in server ping it's 0
    
    $Length = $this->readVarInt( ); // string length
    
    $Data = "";
    do {
      if (microtime(true) - $TimeStart > $this->timeout) {
        throw new MinecraftPingException( 'Server read timed out' );
      }
      
      $Remainder = $Length - StrLen( $Data );
      $block = fread( $this->socket, $Remainder ); // and finally the json string
      // abort if there is no progress
      if (!$block) {
        throw new MinecraftPingException( 'Server returned too few data' );
      }
      
      $Data .= $block;
    } while(StrLen($Data) < $Length);
    
    if($Data === FALSE) {
      throw new MinecraftPingException( 'Server didn\'t return any data' );
    }
    
    $Data = JSON_Decode( $Data, true );
    
    if( JSON_Last_Error( ) !== JSON_ERROR_NONE ) {
      if( Function_Exists( 'json_last_error_msg' ) ) {
        throw new MinecraftPingException( JSON_Last_Error_Msg( ) );
      } else {
        throw new MinecraftPingException( 'JSON parsing failed' );
      }
      
      return FALSE;
    }
    
    return array(
      "server" => array(
        "hostname" => $this->serverAddress,
        "port" => $this->serverPort,
        "version" => $Data["version"]
      ),
      "players" => $Data["players"]
    ); // $Data;
  }

  private function readVarInt( ) {
    $i = 0;
    $j = 0;
    
    while (true) {
      $k = @fgetc( $this->Socket );
      
      if( $k === FALSE ) {
        return 0;
      }
      
      $k = Ord( $k );
      
      $i |= ( $k & 0x7F ) << $j++ * 7;
      
      if( $j > 5 ) {
        throw new MinecraftPingException( 'VarInt too big' );
      }
      
      if(( $k & 0x80 ) != 128) {
        break;
      }
    }
    
    return $i;
  }
}

$server = new MinecraftPing($argv[1]);
$info = $server->query();

echo json_encode($info);
