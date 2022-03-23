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
	if($arParams['MODE'] == 'DETAIL'){
		$jsParams = $arParams;
	}
	if($arParams['MODE'] == 'ORDER'){
		$jsParams['MODE'] = $arParams['MODE'];
	}
?>



<script>
	var params = <?=CUtil::PhpToJSObject($jsParams)?>;
</script>