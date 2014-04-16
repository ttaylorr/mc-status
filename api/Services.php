<?php
  require_once "APIBase.php";
  require_once "./php/DBService.php";

  class Services extends APIBase {
    public static function call() {
      $query = 'select * from services order by `time` desc limit 8';
      $result = parent::app()->dbc()->query($query);

      $data = array();

      while ($row = $result->fetch_assoc()) {
        $service = new DBService($row);

        $data[$service->getName()] = array(
          'status' => $service->getStatus(),
          'bootstrapClass' => $service->asBootstrapClass(),
          'time' => $row['time']
        );
      }

      return $data;
    }
  }

?>
