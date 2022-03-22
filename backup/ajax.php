<?php
//if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Sale\Basket, \Bitrix\Sale\Order;

$result = [
	'OFFERS' => $_POST["OFFERS"]["OFFERS"]    
];
unset($_POST["OFFERS"]);
$result['PRODUCT_INFO'] = $_POST;


$basket = \Bitrix\Sale\Basket::create('s1');

$item = $basket->createItem('catalog', 352);
$item->setField('QUANTITY', 1);
$item->setField('CURRENCY', 'RUB');
$item->setField('PRODUCT_PROVIDER_CLASS', '\Bitrix\Catalog\Product\CatalogProvider');

$item = $basket->createItem('catalog', 353);
$item->setField('QUANTITY', 1);
$item->setField('CURRENCY', 'RUB');
$item->setField('PRODUCT_PROVIDER_CLASS', '\Bitrix\Catalog\Product\CatalogProvider');

$basket->refresh();

$order = \Bitrix\Sale\Order::create('s1', 1, 'RUB');
$order->setPersonTypeId(1);
$order->setBasket($basket);
$r = $order->save();
if (!$r->isSuccess())
{ 
	var_dump($r->getErrorMessages());
}
	
echo json_encode($result);
        






/*$result = [
    'OFFERS' => $_POST["OFFERS"]["OFFERS"]    
];
unset($_POST["OFFERS"]);
$result['PRODUCT_INFO'] = $_POST;
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

header('Content-Type: text/html; charset='.LANG_CHARSET);
$APPLICATION->ShowAjaxHead();
echo json_encode($result);

$APPLICATION->RestartBuffer();
$APPLICATION->IncludeComponent('custom:by1click', '.default', 
	Array(
		"OFFERS" => $result['OFFERS'],
        'PRODUCT_INFO' => $result['PRODUCT_INFO'],
        'AJAX_MODE' => 'Y',
        "AJAX-REQUEST" => 'Y'
));*/



//header("Content-type: application/json; charset=utf-8");



