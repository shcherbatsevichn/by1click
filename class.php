<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Sale\Order;
use Bitrix\Sale\Basket;
use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use CBitrixComponent;

class ByOneClick extends CBitrixComponent implements Controllerable {

    

    private $prodInfo; // параметры 
    private $basket;
    private $order;  //заказ 
    private $offers;    //Оффер
    private $orderInfo; //Информация о заказе
    private $result;    //результат
    
    

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
		//$this->makeOrdertest();
        $this->includeComponentTemplate();
    }

    public function makeOrdertest(){
        $this->basket = Basket::create('s1');

		$item = $this->basket->createItem('catalog', 352);
		$item->setField('QUANTITY', 1);
		$item->setField('CURRENCY', 'RUB');
		$item->setField('PRODUCT_PROVIDER_CLASS', '\Bitrix\Catalog\Product\CatalogProvider');

		$this->basket->refresh();
        
        $this->order = Order::create('s1', 1, 'RUB');
		$this->order->setPersonTypeId(1);
		$this->order->setBasket($this->basket);
		//$r = $this->order->save();
		//if (!$r->isSuccess())
		//{ 
		//	var_dump($r->getErrorMessages());
    //}
        
    }
   

    public function makeReques(){
        return "function";
    }

    public function configureActions(): array
    {
        return [];
       /* return [
            'makeOrder' => [ // Ajax-метод
                'prefilters' => [],
            ],
        ];*/
    }
 
    public function makeOrderAction(string $username = '', string $email = '', string $message = ''): array
    {
        $this->makeOrdertest();
        $r = $this->order->save();
        return [
            "result" => $r->getErrorMessages(),
        ];
        
    }

}
