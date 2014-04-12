<?php
  require_once './php/Application.php';

  abstract class APIBase {
    /**
     * Makes a call to whatever database the API needs to handle
     * @return The un-encoded form of the data returned.
     */
    public static function call() {
      return array();
    }

    /**
     * Handles and then outputs the call from `call()`, encoding
     * in a JSON-compat format.
     * @return The JSON-encoded data from `call()`
     */
    public static function invoke($prettyPrint = false) {
      header('Content-Type: application/json');
      if ($prettyPrint) {
        die(json_encode(static::call(), JSON_PRETTY_PRINT));
      } else {
        die(json_encode(static::call()));
      }
    }

    protected function app() {
      return new Application('./config/config.json');
    }
  }

?>