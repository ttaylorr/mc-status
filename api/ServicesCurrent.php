<?php
  require "APIBase.php";
  require "./php/Application.php";
  require "./php/DBService.php";

  class ServicesCurrent extends APIBase {
    public static function call() {
      header('Content-Type: application/json');

      $query = 'select `id`, `name`, `status`, max(`time`) as `time` from services group by `name`';
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

      return json_encode($data);
    }
  }

?>