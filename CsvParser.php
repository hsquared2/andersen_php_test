<?php

include('User.php');

Class CsvParser {
  private array $users = [];
  private array $data = [];
  private array $dataOut = [];
  private string $file;

  public function __construct(string $file) {
    $this->file = $file;
    $this->data = $this->readCsvFile($file);
  }

  // Reads the csv file and outputs the data into $this->data field
  private function readCsvFile(string $file) : array
  {
    $data = [];

    if($handle = fopen($file, 'r')) {
      $headers = fgetcsv($handle);

      while ($row = fgetcsv($handle)) {
        $data[] = array_combine($headers, $row);
      }

      fclose($handle);
    }

    return $data;
  }

  // Main logic, goes through every row and makes calculations based on operations, writes the output to the same file at the end
  public function run() : void
  {
    $newData = array_filter($this->data, function($row) {
      return $row['Type of Transfer'] == 'OUT';
    });

    foreach($newData as $row) {
      $userID = $row['User ID'];

      if(!array_key_exists($userID, $this->users)) {
        $this->users[$userID] = new User($row);
      }

      $this->users[$userID]->setDate($row['Date of Operation']);
      $commission = $this->users[$userID]->calculateCommission($row['Amount']);

      $this->dataOut[] = [
        "Transfer ID ".$row['ID'],
        'commission: '.$commission ." ".$row['Currency'],
      ];
    }

    $this->writeFile($this->dataOut);
  }

  // Helper function that writes the inputed data into file
  private function writeFile(array $data)  {
    $handle = fopen($this->file, 'w');

    foreach($data as $row) {
      fputcsv($handle, $row);
    }

    fclose($handle);
  }

}