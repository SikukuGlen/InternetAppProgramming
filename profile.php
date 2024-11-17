<?php
require "load.php";
$ObjGlob->checksignin();
$ObjLayouts->heading();
$ObjMenus->main_menu();
$ObjLayouts->banner();
$Objforms->profile_form($ObjGlob, $conn);
$ObjContents->sidebar();
$ObjLayouts->footer();