<?php
require_once("MysqliDb.php");
class Lang
{
 private $Lang;
 private $Key;
 private $Val;
 private $db;
 private $CurrentLanguage;
 public $databaseName = 'db'; // Your database name
 public $username = 'name'; // Your database username
 public $password = 'pass'; // // Your database password
 public $host = 'localhost'; // Your database host, 'localhost' is default.


 function __construct($CurrentLanguage)
 {
     $this->CurrentLanguage = $CurrentLanguage;
     $this->initDB($this->host,$this->username,$this->password,$this->databaseName);
 }
 private function initDB($host,$username,$password,$databaseName)
 {
	$this->db = new MysqliDb ($host, $username, $password, $databaseName);
 }
 public function CreateLanguage($Title,$Lang,$Status=0)
 {
	 $data = Array (
	            "Title" => $Title,
               "Lang" => $Lang,
               "Status" => $Status
    );
    return $id = $this->db->insert ('languages', $data);
 }
 
 /* language Funcs */
 public function GetLanguages()
 {
	return $this->db->get('languages');
 }
 public function UpdateLanguage($Title,$Lang=null)
 {
      $this->db->where ('Lang', (isset($Lang))?$Lang:$this->CurrentLanguage);
	 $data = Array (
	            "Title" => $Title
    );
    return $this->db->update  ('languages', $data);
 }
  private function GetLangID($Lang=null)
 {
     $this->db->where ('Lang', (isset($Lang))?$Lang:$this->CurrentLanguage);
     return $this->db->getOne('languages')["ID"];
 }
 
  public function GetCurrentLanguage()
 {
     return $this->CurrentLanguage;
 }
 public function ActivateLanguage($Lang=null)
 {
	 $this->db->where ('Lang', (isset($Lang))?$Lang:$this->CurrentLanguage);
	 $data = Array (
	            "Status" => 1
    );
    return $this->db->update  ('languages', $data);
 }
 public function DeactivateLanguage($Lang=null)
 {
    $this->db->where ('Lang', (isset($Lang))?$Lang:$this->CurrentLanguage);
	 $data = Array (
	            "Status" => 0
    );
    return $this->db->update  ('languages', $data);
 }
 
 public function IsLanguageActive($Lang=null)
 {
    $this->db->where ('Lang', (isset($Lang))?$Lang:$this->CurrentLanguage);
    return (boolean) $this->db->getOne('languages')["Status"];
 }
 
 
 
 /* Key Val Funcs */
  public function CreateString($Key,$Val,$Lang=null)
 {
	 $data = Array (
	            "LangID" => $this->GetLangID((isset($Lang))?$Lang:$this->CurrentLanguage),
               "Str_Key" => $Key,
               "Str_Val" => $Val
    );
    
    return $id = $this->db->insert ('lang_values', $data);
 }
  public function GetString($key,$Lang=null)
 {
     $this->db->where ('Lang', $this->GetLangID((isset($Lang))?$Lang:$this->CurrentLanguage));
     $this->db->where ('Str_Key',$key );
	 return $this->db->getOne('lang_values')['Str_Val'];
 }
  public function UpdateString($key,$val,$Lang=null)
 {
	 $this->db->where ('Lang', $this->GetLangID((isset($Lang))?$Lang:$this->CurrentLanguage));
	 $this->db->where ('Str_Key',$key );
	 $data = Array (
	            "Str_Val" => $val
    );
	 return $this->db->update  ('lang_values', $data);
 }
 
 
 public function GetApplicationDefaultLanguage()
 {
     $this->db->where ('Default_Lang', 1);
     return $this->db->getOne('languages');
 }
 public function SetApplicationDefaultLanguage()
 {
	 $data = Array (
	            "Default_Lang" => 0
    );
    return $this->db->update  ('languages', $data);
    
     $this->db->where ('Lang', (isset($Lang))?$Lang:$this->CurrentLanguage);
	 $data = Array (
	            "Default_Lang" => 1
    );
    return $this->db->update('languages', $data);
 }
 public function GetUserDefaultLanguage()
 {
     if(isset($_COOKIE['Lang']))
     return $_COOKIE['Lang'];
     else if(isset($_SESSION['Lang']) && (session_status() == PHP_SESSION_NONE) )
     return $_SESSION['Lang'];
     else
     return null;
 }
 public function SetUserDefaultLanguage($Lang)
 {
     if($this->GetLangID($Lang))
     {
     setcookie('Lang', $Lang, strtotime("+1 year"), '/');
     $_SESSION['Lang'] = $Lang;
     }
 }
 public function UnsetUserDefaultLanguage()
 {
     setcookie('Lang', null, -1, '/');
     if (session_status() == PHP_SESSION_NONE) 
     $_SESSION['Lang'] = null;
 }
}

//$Language = new Lang('en');
//$Language->CreateLanguage('English','eng',1);
//var_dump($Language->IsLanguageActive());
