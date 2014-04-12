<?php

  require_once "MinecraftPing.php";

  class DBPing {

    const NAME_FIELD = 'server_name';
    const VERSION_FIELD  = 'version';
    const PLAYERS_FIELD = 'players';
    const MAX_PLAYERS_FIELD = 'maxplayers';
    const PING_FIELD = 'ping';
    const TIME_FIELD = 'time';

    const DEFAULT_VERSION = '1.7*';
    const DEFAULT_PORT = 25565;

    private $name;
    private $version;
    private $players;
    private $maxplayers;
    private $ping;

    private $time;
    
    # This function must be called in tandem with either loadFromDatabase, or createPing
    function __construct($start = -1) {
      $this->time = $start == -1 ? time() : $start;
    }

    function fromDatabase($row, $db) {
      $this->name = $row[self::NAME_FIELD];
      $this->version = $row[self::VERSION_FIELD];
      $this->players = $row[self::PLAYERS_FIELD];
      $this->maxplayers = $row[self::MAX_PLAYERS_FIELD];
      $this->ping = $row[self::PING_FIELD];      
      $this->time = intval($row[self::TIME_FIELD]) * 1000; # for Canvas.js      
    }

    function makePing($server) {
      $pinger = new MinecraftPing(8);
      $data = $pinger->getStatus($server->getIpAddress(), self::DEFAULT_VERSION, self::DEFAULT_PORT);

      $this->name = $server->getName();
      $this->hostname = $server->getIpAddress();
      $this->version = $data[self::VERSION_FIELD];
      $this->players = $data[self::PLAYERS_FIELD];
      $this->maxplayers = $data[self::MAX_PLAYERS_FIELD];
      $this->ping = $data[self::PING_FIELD];
    }

    function toDatabase($db) {
      $query = "INSERT INTO pings";
      $query .= "(`server_name`,`version`,`players`,`maxplayers`,`ping`,`time`)";
      $query .= "VALUES('".$this->name."','".$this->getVersion()."','".$this->getPlayers()."','".$this->getMaxPlayers();
      $query .= "','".$this->getPing()."','".$this->getTime()."')";

      return $db->query($query);
    }

    function getHostname() {
      return $this->hostname;
    }

    function getVersion() {
      return $this->version;
    }

    function getPlayers() {
      return $this->players;
    }

    function getMaxPlayers() {
      return $this->maxplayers;
    }

    function getPing() {
      return $this->ping;
    }

    function getTime() {
      return $this->time;
    }
  }
?>