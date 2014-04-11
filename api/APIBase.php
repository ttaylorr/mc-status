<?php
  require_once './php/Application.php';

  abstract class APIBase {

    /**
     * Makes a call to whatever database the API needs to handle
     * @return The un-encoded form of the data returned.
     */
    public static abstract function call();

    /**
     * Handles and then outputs the call from `call()`, encoding
     * in a JSON-compat format.
     * @return The JSON-encoded data from `call()`
     */
    public static function invoke() {
      header('Content-Type: application/json');
      die(json_encode(static::call()));
    }

    protected function app() {
      return new Application('./config/config.json');
    }
  }

?>