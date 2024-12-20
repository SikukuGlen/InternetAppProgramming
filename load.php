<?php
session_start();
require "includes/constants.php";
require "includes/dbConnection.php";
require "lang/en.php";

// Class Auto Load
function ClassAutoload($ClassName){
   $directories = ["forms", "processes", "structure", "tables", "global", "store"];

   foreach($directories AS $dir){
        $FileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir .  DIRECTORY_SEPARATOR . $ClassName . '.php';
        
        if(file_exists($FileName) AND is_readable($FileName)){
         require $FileName;
        }
   }
}
spl_autoload_register('ClassAutoload');

$ObjGlob = new fncs();
$ObjSendMail = new SendMail();

// Creating instances of all classes
    $ObjLayouts = new layouts();
    $ObjMenus = new menus();
    $ObjContents = new contents();
    $Objforms = new forms();
    $conn = new dbConnection(DBTYPE, HOSTNAME, DBPORT, HOSTUSER, HOSTPASS, DBNAME);

// Create process instances

$ObjAuth = new auth();
$ObjAuth->signup($conn, $ObjGlob, $ObjSendMail, $lang, $conf);
$ObjAuth->verify_code($conn, $ObjGlob, $ObjSendMail, $lang, $conf);
$ObjAuth->set_passphrase($conn, $ObjGlob, $ObjSendMail, $lang, $conf);
$ObjAuth->signin($conn, $ObjGlob, $ObjSendMail, $lang, $conf);
 $ObjAuth->signout($conn, $ObjGlob, $ObjSendMail, $lang, $conf);
 $ObjAuth->save_details($conn, $ObjGlob, $ObjSendMail, $lang, $conf);