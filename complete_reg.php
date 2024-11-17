<?php
require "load.php";
$ObjGlob->checksignin();
$ObjLayouts->heading();
$ObjMenus->main_menu();
$ObjLayouts->banner();
$Objforms->complete_reg_form($ObjGlob, $conn);
$ObjContents->sidebar();
$ObjLayouts->footer();