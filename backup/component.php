<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if($arParams["AJAX-REQUEST"] == 'Y'){
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

}
$this->includeComponentTemplate();
