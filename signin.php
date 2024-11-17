<?php
require "load.php";
$ObjLayouts->heading();
$ObjMenus->main_menu();
$ObjLayouts->banner();
$Objforms->sign_in_form($ObjGlob);
$ObjContents->sidebar();
$ObjLayouts->footer();