<?php

Class User {
  private float $totalWeeklyAmount;
  private string $weekStartDate;
  private string $date;
  private string $customerType;
  private float $commissionRate;

  public function __construct(array $data)
  {
    $this->totalWeeklyAmount = 0;
    $this->weekStartDate = $data['Date of Operation'];
    $this->customerType = $data['Type of Customer'];
    $this->commissionRate = $this->customerType == 'natural' ? 0.005 : 0.02;
  }

  // Sets the active date of the operation
  public function setDate(string $date): void
  {
    $this->date = $date;

    if($this->checkDate()) {
      $this->weekStartDate = $date;
      $this->totalWeeklyAmount = 0;
    }
  }

  // Checks if a week was passed since the initial operation for specific user
  private function checkDate(): bool
  {
    $currentDateTimestamp = strtotime($this->date);
    $weekStartTimestamp = strtotime($this->weekStartDate);

    return (($currentDateTimestamp - $weekStartTimestamp) / 86400) > 7;
  }

  // Calculates commission for each operation for speicific user
  public function calculateCommission(float $amount): float
  {
    $oldTotal = $this->totalWeeklyAmount;
    $this->totalWeeklyAmount += $amount;

    if(!$this->checkDate() && $this->totalWeeklyAmount > 1000) {
      $difference = $oldTotal > 1000 ? $oldTotal : 1000;
      return ($this->totalWeeklyAmount - $difference) * $this->commissionRate;
    }

    return 0;
  }
}