<?php

  class Synchronizer{

    private $params, $response, $hash;

    function __construct($params){
      $this->params = $params;
      if($this->synchronize()){
        $this->createVerticals();
        $this->populateVerticals();
      }
    }

    function synchronize(){
      return ($this->hasSecret() && $this->remoteString() && $this->validateSecret() && $this->validateResponse() && $this->parseJson());
    }

    function remoteString(){
      try{
        $encodedJson = urlencode(json_encode($this->params));
        $towstaSecret = TOWSTA_SECRET;
        $url = "http://manager.towsta.com/synchronizers/{$towstaSecret}/php/export.json?query={$encodedJson}";
        $this->response = file_get_contents($url);
        return true;
      }catch(Exception $e){
        return false;
      }
    }

    function hasSecret(){
      return ((defined('TOWSTA_SECRET')) && (trim(TOWSTA_SECRET) != ''));
    }

    function validateSecret(){
      return (trim($this->response) != '');
    }

    function validateResponse(){
      return (substr($this->response,0,1) == '{');
    }

    function parseJson(){
      try{
        $this->hash = json_decode($this->response);
        return true;
      }catch(Exception $e){
        return false;
      }
    }

    function createVerticals(){
      $usersStructure = new stdClass;
      $usersStructure->name = 'User';
      $usersStructure->slices = new stdClass;
      $usersStructure->slices->id = 'integer';
      $usersStructure->slices->nick = 'text';
      $usersStructure->slices->email = 'text';
      Vertical::create($usersStructure);
      foreach($this->hash->structures as $structure)
        Vertical::create($structure);
    }

    function populateVerticals(){
      Vertical::populate('User', $this->hash->users);
      $index = 0;
      foreach($this->hash->structures as $structure){
        Vertical::populate($structure->name, $this->hash->verticals[$index]->horizontals);
        $index++;
      }
    }

  }

  function sync_with_towsta($params){
    return new Synchronizer($params);
  }

?>
