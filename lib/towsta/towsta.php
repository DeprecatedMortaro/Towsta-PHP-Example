<?php

  class Towsta{

    public static $all = array();
    public static $attributes = array();

    public static function all(){
      $result = array();
      foreach(self::$all as $obj){
        if(get_class($obj) == get_called_class())
          $result[] = $obj;
      }
      return $result;
    }

    public static function attributes(){
      $underscore = underscore(get_called_class());
      $result = array();
      foreach(self::$attributes as $attribute => $value){
        $to = strlen($underscore);
        if(substr($attribute,0,($to)) == $underscore)
          $result[substr($attribute,++$to)] = $value;
      }
      return $result;
    }

    public static function kindOf($attribute){
      return static::$attributes[underscore(get_called_class()).'_'.$attribute];
    }

    public static function first(){
      $collection = static::all();
      if(sizeOf($collection) > 0){
        return $collection[0];
      }
      return null;
    }

    function __construct($attributes=array()){
      foreach($attributes as $attribute => $value)
        $this->set($attribute, $value);
      static::$all[] = $this;
    }

    function get($attribute){
      if(static::kindOf($attribute) == 'user')
        return $this->getUser($this->$attribute);
      if(static::kindOf($attribute) == 'vertical')
        return $this->getVertical($this->$attribute);
      if(static::kindOf($attribute) == 'multiple')
        return $this->getMultiple($this->$attribute);
      else
        return $this->$attribute;
    }

    function set($attribute, $value){
      if(static::kindOf($attribute) == 'image')
        $this->setImage($attribute, $value);
      elseif(static::kindOf($attribute) == 'gallery')
        $this->setGallery($attribute, $value);
      else
        $this->$attribute = $value;
    }

    function setImage($attribute, $value){
      $this->$attribute = json_decode(substr($value,1,-1))->link;
    }


    function setGallery($attribute, $value){
      $gallery = array();
      foreach(json_decode($value) as $image){
        $gallery[] = $image->link;
      }
      $this->$attribute = $gallery;
    }

    function getUser($value){
      foreach(User::all() as $user){
        if($user->get('id') == $value)
          return $user;
      }
      return null;
    }

    function getVertical($value){
      if(trim($value) == '')
        return null;
      foreach(Towsta::$all as $obj){
        if($obj->get('id') == $value)
          return $obj;
      }
      return null;
    }

    function getMultiple($value){
      $result = array();
      foreach(explode(' ', $value) as $objId){
        foreach(Towsta::$all as $obj){
          if($obj->get('id') == $objId)
            $result[] = $obj;
        }
      }
      return $result;
    }

  }

?>
