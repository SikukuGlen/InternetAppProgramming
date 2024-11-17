<?php
require "load.php";
$ObjGlob->checksignin();
$ObjGlob->verify_profile();
$ObjLayouts->heading();
$ObjMenus->main_menu();
$ObjLayouts->banner();
$ObjContents->sidebar();
$ObjLayouts->footer();