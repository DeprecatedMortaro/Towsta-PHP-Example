<?php

  class Vertical{

    public static $all = array();

    public static function create($structure){
      $file = underscore($structure->name);
      $folder = defined('MODULE_FOLDER') ? MODULE_FOLDER : "../modules";
      if(!file_exists($folder))
        mkdir($folder, 0777);
      $file = "{$folder}/{$file}.php";
      if(!file_exists($file)){
        $content = "<?php\n\n\tclass {$structure->name} extends Towsta{\n\n\t}\n\n?>";
        $moduleFile = fopen($file, 'w');
        fwrite($moduleFile, $content);
        fclose($moduleFile);
      }
      include($file);
      foreach(get_object_vars($structure->slices) as $attribute => $value){
        $classAttribute = underscore($structure->name).'_'.$attribute;
        eval($structure->name.'::$attributes[$classAttribute] = $value;');
      }
      eval($structure->name.'::$all = array();');
    }

    public static function populate($className, $horizontals){
      foreach($horizontals as $horizontal)
        eval('$obj = new '.$className.'(get_object_vars($horizontal));');
    }

  }

?>
