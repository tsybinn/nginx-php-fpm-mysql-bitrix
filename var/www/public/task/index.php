<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Task");

$APPLICATION->IncludeComponent(
    "pixel:task.client",
    "",
    []
);


?>