#!/usr/bin/php -q
<?php
  require 'Application.php';
  require 'DBService.php';

  define('MOJANG_STATUS_URL', 'https://status.mojang.com/check');

  define('STATUS_WEB', 'minecraft.net');
  define('STATUS_LOGIN', 'auth.mojang.com');
  define('STATUS_SESSION', 'sessionserver.mojang.com');
  define('STATUS_SKIN', 'textures.minecraft.net');

  # This script should execute every minute and will ping all servers
  if (php_sapi_name() != 'cli') {
    die("This script may only be invoked over the command line!");
  }

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, MOJANG_STATUS_URL);

  $_data = curl_exec($ch);
  curl_close($ch);

  $status = json_decode($_data, true);

  $app = new Application("../config/config.json", false);

  foreach ($status as $i => $arr) {
    $service_name = key($arr);
    $service_state = $arr[$service_name];

    echo('Updating service: \'' . $service_name . '\' with status \'' . $service_state . '\'' . PHP_EOL);

    $service = new DBService(array(
      'name' => $service_name,
      'status' => $service_state
    ));

    $service->toDatabase($app->dbc());
  }

  function statusFor($status, $service) {
    return $status[0][$service];
  }
?>
