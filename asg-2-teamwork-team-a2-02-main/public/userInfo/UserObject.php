<?php

class UserObject
{
  public $fName;
  public $lName;
  public $city;
  public $country;
  public $id;
  public function __construct($fName, $lName, $city, $country, $id)
  {
    $this->fName = $fName;
    $this->lName = $lName;
    $this->city = $city;
    $this->country = $country;
    $this->id = $id;
  }
  function getFName()
  {
    return $this->fName;
  }
  function getLName()
  {
    return  $this->lName;
  }
  function getCity()
  {
    return  $this->city;
  }
  function getCountry()
  {
    return $this->country;
  }
  function getId()
  {
    return $this->id;
  }
}
