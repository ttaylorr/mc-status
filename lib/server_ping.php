<?php
class MinecraftPingException extends Exception
{
  //
}

class MinecraftPing
{
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
   * ---------
   *
   * This method can be used to get server-icon.png too.
   * Something like this:
   *
   * $Server = new MinecraftPing( 'localhost' );
   * $Info = $Server->Query();
   * echo '<img width="64" height="64" src="' . Str_Replace( "\n", "", $Info[ 'favicon' ] ) . '">';
   *
   */
  
  private $Socket;
  private $ServerAddress;
  private $ServerPort;
  private $Timeout;
  
  public function __construct( $Address, $Port = 25565, $Timeout = 2 )
  {
    $this->ServerAddress = $Address;
    $this->ServerPort = (int)$Port;
    $this->Timeout = (int)$Timeout;
    
    $this->Connect( );
  }
  
  public function __destruct( )
  {
    $this->Close( );
  }
  
  public function Close( )
  {
    if( $this->Socket !== null )
    {
      fclose( $this->Socket );
      
      $this->Socket = null;
    }
  }
  
  public function Connect( )
  {
    $connectTimeout = $this->Timeout;
    $this->Socket = @fsockopen( $this->ServerAddress, $this->ServerPort, $errno, $errstr, $connectTimeout );
    
    if( !$this->Socket )
    {
      throw new MinecraftPingException( "Failed to connect or create a socket: $errno ($errstr)" );
    }
    
    // Set Read/Write timeout
    stream_set_timeout( $this->Socket, $this->Timeout );
  }
  
  public function Query( )
  {
    $TimeStart = microtime(true); // for read timeout purposes
    
    // See http://wiki.vg/Protocol (Status Ping)
    $Data = "\x00"; // packet ID = 0 (varint)
    
    $Data .= "\x04"; // Protocol version (varint)
    $Data .= Pack( 'c', StrLen( $this->ServerAddress ) ) . $this->ServerAddress; // Server (varint len + UTF-8 addr)
    $Data .= Pack( 'n', $this->ServerPort ); // Server port (unsigned short)
    $Data .= "\x01"; // Next state: status (varint)
    
    $Data = Pack( 'c', StrLen( $Data ) ) . $Data; // prepend length of packet ID + data
    
    fwrite( $this->Socket, $Data ); // handshake
    fwrite( $this->Socket, "\x01\x00" ); // status ping
    
    $Length = $this->ReadVarInt( ); // full packet length
    
    if( $Length < 10 )
    {
      return FALSE;
    }
    
    fgetc( $this->Socket ); // packet type, in server ping it's 0
    
    $Length = $this->ReadVarInt( ); // string length
    
    $Data = "";
    do
    {
      if (microtime(true) - $TimeStart > $this->Timeout)
      {
        throw new MinecraftPingException( 'Server read timed out' );
      }
      
      $Remainder = $Length - StrLen( $Data );
      $block = fread( $this->Socket, $Remainder ); // and finally the json string
      // abort if there is no progress
      if (!$block)
      {
        throw new MinecraftPingException( 'Server returned too few data' );
      }
      
      $Data .= $block;
    } while( StrLen($Data) < $Length );
    
    if( $Data === FALSE )
    {
      throw new MinecraftPingException( 'Server didn\'t return any data' );
    }
    
    $Data = JSON_Decode( $Data, true );
    
    if( JSON_Last_Error( ) !== JSON_ERROR_NONE )
    {
      if( Function_Exists( 'json_last_error_msg' ) )
      {
        throw new MinecraftPingException( JSON_Last_Error_Msg( ) );
      }
      else
      {
        throw new MinecraftPingException( 'JSON parsing failed' );
      }
      
      return FALSE;
    }
    
    return array(
      "version" => $Data['version'],
      "players" => $Data['players']
    );
  }

  private function ReadVarInt( )
  {
    $i = 0;
    $j = 0;
    
    while( true )
    {
      $k = @fgetc( $this->Socket );
      
      if( $k === FALSE )
      {
        return 0;
      }
      
      $k = Ord( $k );
      
      $i |= ( $k & 0x7F ) << $j++ * 7;
      
      if( $j > 5 )
      {
        throw new MinecraftPingException( 'VarInt too big' );
      }
      
      if( ( $k & 0x80 ) != 128 )
      {
        break;
      }
    }
    
    return $i;
  }
}

$mc = new MinecraftPing($argv[1]);
echo json_encode($mc->Query());
