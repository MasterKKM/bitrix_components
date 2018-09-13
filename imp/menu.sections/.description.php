<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IMP_SECTION_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("IMP_SECTION_TEMPLATE_DESCRIPTION"),
//	"ICON" => "/images/cat_list.gif",
//	"CACHE_PATH" => "Y",
//	"SORT" => 30,
    "PATH" => array(
        "ID" => "utility",
        "CHILD" => array(
            "ID" => "navigation",
            "NAME" => GetMessage("MAIN_NAVIGATION_SERVICE")
        )
    ),

);

?>
