<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

define("NO_KEEP_STATISTIC", "Y");

define("NO_AGENT_STATISTIC","Y");

define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Sale\Order;
use Bitrix\Sale\Basket;
use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Context;
use \Bitrix\Sale\Fuser;
use Bitrix\Main\UserTable;
use CBitrixComponent;

use \Bitrix\Main\Application;



use \Bitrix\Main\Loader;

Loader::includeModule("sale");
Loader::includeModule("catalog");

class ByOneClick extends CBitrixComponent implements Controllerable {

    

    private $basketInfo; // параметры 
    private $basket;
    private $order;  //заказ 
    private $offers;    //Оффер
    private $orderInfo; //Информация о заказе
    private $result;    //результат
    private $params;
    
    

    public function onPrepareComponentParams($arParams)
    {
        $this->params = $arParams;
        return $arParams;
    }

    public function executeComponent()
    {

        $this->includeComponentTemplate();
    }

    public function makeReques(){
        return "function";
    }

    public function configureActions(): array
    {
        return [
            'makeOrder' => [ // Ajax-метод
                'prefilters' => [],
            ],
        ];
    }
 
    public function makeOrderAction($productdata = '', $params = ''): array
    {
        if($params['MODE'] == 'DETAIL'){
            $this->createBasket(); //создаём корзину
            $this->getItem($productdata, $params['OFFERS']); //получаем продукт(для детального товара)
            $this->setItems();//добавляем его в заказ
        }
        if($params['MODE'] == 'ORDER'){
            $this->getBasketUser();
        }
        global $USER;
        $id = $USER->GetID(); //если пользователь авторизован, то заказ будет на его акк
        if(!$id){
            $id = $this->registerUserByPhone($productdata['PHONE']); //если нет, регистрируем и авторизуем(если нет в базе)
        }
        $this->createOrder($id); //создаем заказ 
        $this->setOrderProperty($productdata['PHONE']); // заполняем пропсы
        $this->setOrder(); //сохраняем заказ
        
        
        $result['function'] = $params;
        //$result['ID'] = $USER->GetID();
        return [
            "result" => $result,
        ];
        
    }

    /*only DETAIL Product Page*/
    private function getItem($params, $offerList){
        foreach($offerList as $offer){
            if($offer['TREE']["PROP_{$params['SKUID']}"] == $params['VALUEID']){
                $this->basketInfo[0]['ID'] = $offer['ID'];
            }
        }
        $this->basketInfo[0]['QUANTITY'] = $params['QUALITY'];
    }
    /*only ORDER  Page*/
    private function getBasketUser(){
        $this->basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
    }
    private function createOrder($userID){
        $this->order = Order::create(SITE_ID, $userID, 'RUB');
    }

    private function createBasket(){
        $this->basket = Basket::create(SITE_ID);
    }


    private function setItems(){
        foreach($this->basketInfo as $itemInfo){
            $item = $this->basket->createItem('catalog', $itemInfo['ID']);
            $item->setField('QUANTITY', $itemInfo['QUANTITY']);
            $item->setField('CURRENCY', 'RUB');
            $item->setField('PRODUCT_PROVIDER_CLASS', '\Bitrix\Catalog\Product\CatalogProvider');
        }
		
        $this->basket->refresh();

    }

    private function setOrderProperty($phone){
        $propertyCollection = $this->order->getPropertyCollection();
        $propertyCodeToId = array();

        foreach($propertyCollection as $propertyValue)
            $propertyCodeToId[$propertyValue->getField('CODE')] = $propertyValue->getField('ORDER_PROPS_ID');

        $propertyValue=$propertyCollection->getItemByOrderPropertyId($propertyCodeToId['CONTACT_PHONE']);
        $propertyValue->setValue($phone);

        $propertyValue=$propertyCollection->getItemByOrderPropertyId($propertyCodeToId['BY1CLICK']);
        $propertyValue->setValue('YES');
    }


    private function setOrder(){        
        
		$this->order->setPersonTypeId(1);
		$this->order->setBasket($this->basket);
		$r = $this->order->save();
		if (!$r->isSuccess())
		{ 
			var_dump($r->getErrorMessages());
        }
        return $r;
    }
        
    private function registerUserByPhone($phone)
    {
        if($this->checkUserByPhone($phone) == false){ // если пользователь не зарегистрирован
            $password = rand(0, 9).rand(14, 99).rand().rand().rand().rand().rand().rand().rand().rand();
            $user = new CUser;
            $fields = Array(
            "NAME" => "",
            "LAST_NAME" => "",
            "EMAIL" => "autoreg-".date('ymdhis')."@"."by1click.bx",
            "LOGIN" => trim($phone, '+'), // логин - номер телефона без +
            "LID" => "ru",
            "ACTIVE" => "Y",
            "GROUP_ID" => array(3),
            "PASSWORD" => $password,
            "CONFIRM_PASSWORD" => $password,
            "PERSONAL_PHONE" => $phone,
            );
            $ID = $user->Add($fields);
            if($ID == false){
                return "error";
            }
        
            if (intval($ID) > 0){
            // echo "string";
            $user->Authorize($ID);//авторизуем
            return $ID;
            }
        
        } else { // если пользователь зарегистрирован
            return $this->checkUserByPhone($phone);
        }
        }
        /**
        * id пользователя по номеру телефона
        * возвращает false если не существует
        * возвращает id если существует
        */
    private function checkUserByPhone($phone)
    {
        $user = UserTable::getRow(array(
            'filter' => array(
                '=LOGIN' => trim($phone, '+'),
            ),
            'select' => array('ID')
        ));

        if($user['ID'])
        {
            return $user['ID']; // пользователь существует
        }
        else
        {
            return false; // пользователь не существует
        }
    }
   

    
}
