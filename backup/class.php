<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Sale\Order,
    Bitrix\Sale\Basket;
use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use CBitrixComponent;

class ByOneClick extends CBitrixComponent implements Controllerable, Errorable{

    private $prodInfo; // параметры 
    private $basket;
    private $order;  //заказ 
    private $offers;    //Оффер
    private $orderInfo; //Информация о заказе
    private $result;    //результат
    
    //protected ErrorCollection $errorCollection;

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function getErrors(): array
    {
        return $this->errorCollection->toArray();
    }

    public function getErrorByCode($code): Error
    {
        return $this->errorCollection->getErrorByCode($code);
    }
    
    public function getOrderInfo(){
        $result =[];
        foreach($this->offers as $offer){
            if($offer["TREE"]["PROP_".$this->prodInfo["SKUID"]] == $this->prodInfo["VALUEID"]){
                $result['PRODUCT_ID'] = $offer['ID'];
                $result['NAME'] = $offer["NAME"];
                $result['CURRENCY'] = 'RUB';
                $result["PRICE"] = $offer["ITEM_PRICES"][0]["RATIO_PRICE"];
                $result['QUANTITY'] = $this->prodInfo['QUALITY'];
            }
        }
        
		$this->orderInfo = $result;
    }
    
    private function makeBasket($products){ 
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
    }

    private function createOrder(){
        $order = \Bitrix\Sale\Order::create('s1', 1, 'RUB');
		$order->setPersonTypeId(1);
		$order->setBasket($basket);
		$r = $order->save();
		if (!$r->isSuccess())
		{ 
			var_dump($r->getErrorMessages());
		}
    }

    public function makeOrdertest(){
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
   
     
     public function configureActions(): array
     {
         return [];
     }
 
     public function makeOrdertestAction(string $username = '', string $email = '', string $message = ''): array
     {
        $this->makeOrdertest();
     }

}
