<?php
  # require_once './php/Application.php';

  abstract class APIBase {

    public static abstract function call();

    protected function app() {
      return new Application('./config/config.json');
    }
  }

?>