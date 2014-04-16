<?php
  class DBService {
    const NAME_FIELD = 'name';
    const STATUS_FIELD = 'status';

    private $name;
    private $status;

    function __construct($row) {
      $this->name = $row[self::NAME_FIELD];
      $this->status = $row[self::STATUS_FIELD];
    }

    public static function latest($name, $dbc) {
      $query = "SELECT * FROM services WHERE name = '$name'";
      $result = $dbc->query($query);
      while ($row = $result->fetch_assoc()) {
        return new DBService($row);
      }
      return null;
    }

    function getName() {
      return $this->name;
    }

    function getStatus() {
      return $this->status;
    }

    function asBootstrapClass() {
      switch($this->status) {
        case 'green':
          return 'alert-success';
        case 'yellow':
          return 'alert-warning';
        case 'red':
          return 'alert-danger';
        default:
          return null;
      }
    }

    function toDatabase($dbc) {
      $query = "INSERT INTO services (`name`, `status`, `time`) VALUES('".$this->name."', '".$this->status."', ".time().")";
      return $dbc->query($query);
    }
  }

?>
