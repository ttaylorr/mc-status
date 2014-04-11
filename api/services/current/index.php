<?php
  require "../../../php/Application.php";
  require "../../../php/DBService.php";

  header('Content-Type: application/json');

  $app = new Application('../../../config/config.json');

  $query = 'select `id`, `name`, `status`, max(`time`) as `time` from services group by `name`';
  $result = $app->dbc()->query($query);

  $data = array();

  while ($row = $result->fetch_assoc()) {
    $service = new DBService($row);

    $data[$service->getName()] = array(
      'status' => $service->getStatus(),
      'bootstrapClass' => $service->asBootstrapClass(),
      'time' => $row['time']
    );
  }

  echo json_encode($data);
?>