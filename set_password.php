<?php
require "load.php";
$ObjLayouts->heading();
$ObjMenus->main_menu();
$ObjLayouts->banner();
$Objforms->set_pass_form($ObjGlob);
$ObjContents->sidebar();
$ObjLayouts->footer();