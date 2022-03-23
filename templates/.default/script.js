window.doAction = function(selectNode) { //выполняем действия по сбору информации, формированию и отправке
    var attributes = {};
    if (window.params['MODE'] == 'DETAIL') {
        attributes = getSkuAttribute(selectNode);
        attributes['QUALITY'] = getQuality();
    }
    attributes['PHONE'] = getPhoneNumber();
    BX.ajax.runComponentAction("custom:by1click", "makeOrder", {
        mode: "class",
        data: {
            "productdata": attributes,
            "params": window.params,
        }
    }).then(function(response) {
        window.getPopUpResult(response.data['result']);
    });

}

window.checkSelect = function() { // проверяем, выбран ли размер
    var selectNode = getSelectNode();
    if (!selectNode) {
        alert('Выберите размер!');
    }
    return selectNode;
}

getSelectNode = function() { // получить выбранный нод 
    var skuNodelist, skuSelectNode;
    skuNodelist = document.querySelectorAll('li.product-item-scu-item-text-container-big');
    skuNodelist.forEach(element => {;
        if (element.classList.contains('selected')) {
            skuSelectNode = element;
        }
    });
    return skuSelectNode;
}

checkPhoneNumber = function() {
    var inputPhone = document.querySelector('#pop-up-input-phone');
    if (!inputPhone.value) {
        alert('Введите Ваш номер телефона!');
        return false;
    }
    return true;
}

getPhoneNumber = function() {
    var inputPhone = document.querySelector('#pop-up-input-phone');
    return inputPhone.value
}

getSkuAttribute = function(node) { // получить параметры sku для поиска в offers
    var attributText = node.getAttribute('data-treevalue'),
        attributList, resultList;
    attributList = attributText.split('_');
    resultList = { "SKUID": attributList[0], "VALUEID": attributList[1] };
    return resultList;
}

getQuality = function() { //получить выбранное количество 
    var inputQuality = document.querySelector('input[name="Card[number]"]');
    return inputQuality.value;
}

window.getPopUp = function() { // вызвать popup 
    var selectNode = checkSelect();
    if (selectNode) {
        BX.ready(function() {
            var oPopup = new BX.PopupWindow(
                "my_answer",
                null, {
                    content: '<div class="popup-good popup__content"><div><label for="phone" class="form__label">Введите Ваш номер телефона:</lable></div><div><input id="pop-up-input-phone" name="phone" class="input" type="text" requred></div></div>', //начинка 
                    closeIcon: { right: "20px", top: "10px" },
                    zIndex: 0,
                    offsetLeft: 0,
                    offsetTop: 0,
                    draggable: { restrict: false },
                    buttons: [
                        new BX.PopupWindowButton({
                            text: "Оформить заказ",
                            className: "btn popup-good__btn by-1-click-popup-btn",
                            events: {
                                click: function() {
                                    if (checkPhoneNumber()) {
                                        window.doAction(selectNode);
                                        this.popupWindow.close();
                                    }

                                }
                            }
                        }),
                    ]
                });
            oPopup.setContent(BX('hideBlock'));
            oPopup.show();
        });
    }

}

window.getPopUpOrder = function() { // вызвать popup 
    var selectNode = '';
    if (window.params["BASKET_ITEMS"] != '') {
        BX.ready(function() {
            var oPopup = new BX.PopupWindow(
                "my_answer",
                null, {
                    content: '<div class="popup-good popup__content"><div><label for="phone" class="form__label">Введите Ваш номер телефона:</lable></div><div><input id="pop-up-input-phone" name="phone" class="input" type="text" requred></div></div>', //начинка 
                    closeIcon: { right: "20px", top: "10px" },
                    zIndex: 0,
                    offsetLeft: 0,
                    offsetTop: 0,
                    draggable: { restrict: false },
                    buttons: [
                        new BX.PopupWindowButton({
                            text: "Оформить заказ",
                            className: "btn popup-good__btn by-1-click-popup-btn",
                            events: {
                                click: function() {
                                    if (checkPhoneNumber()) {
                                        window.doAction(selectNode);
                                        this.popupWindow.close();
                                    }

                                }
                            }
                        }),
                    ]
                });
            oPopup.setContent(BX('hideBlock'));
            oPopup.show();
        });
    } else {
        alert('Ваша корзина пуста! Добавьте товары в корзину!');
    }

}

window.getPopUpResult = function(message) { // вызвать popup 
    BX.ready(function() {
        var rPopup = new BX.PopupWindow(
            "result",
            null, {
                content: '<div class="popup-good popup__content"><label class="form__label">' + message + '</lable></div>', //начинка 
                closeIcon: { right: "20px", top: "10px" },
                zIndex: 0,
                offsetLeft: 0,
                offsetTop: 0,
                draggable: { restrict: false },
                buttons: [
                    new BX.PopupWindowButton({
                        text: "Закрыть",
                        className: "btn popup-good__btn by-1-click-popup-btn",
                        events: {
                            click: function() {
                                if (checkPhoneNumber()) {
                                    this.popupWindow.close();
                                    if (window.params['MODE'] == 'ORDER') {
                                        location.reload();
                                    }
                                }

                            }
                        }
                    }),
                ]
            });
        rPopup.setContent(BX('hideBlock'));
        rPopup.show();
    });

}