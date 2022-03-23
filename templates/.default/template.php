<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?CUtil::InitJSCore( array('ajax' , 'popup' ));?>
<?if($arParams['MODE'] == 'DETAIL'){?>
<div>
    <a onclick="getPopUp();" class='onebuttonby-btn' href = "javascript:void(0);">Купить в 1 клик</a>
</div>
<?}
if($arParams['MODE'] == 'ORDER'){
?>
<div>
    <a onclick="getPopUpOrder();" class='onebuttonby-btn' href = "javascript:void(0);">Купить в 1 клик</a>
</div>
<?	
}
	//dump($arParams);
?>



<script>
	//var myparams = new phpParams(<?//=json_encode($jsParams)?>);
	var params = <?=CUtil::PhpToJSObject($arParams)?>;
	/*BX.ready(function () {
	var myparams = new phpParams(<?//=CUtil::PhpToJSObject($jsParams)?>);
	});*/
</script>