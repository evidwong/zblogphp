<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
    $zbp->ShowError(6);
    die();
}
if (!$zbp->CheckPlugin('linkmanage')) {
    $zbp->ShowError(48);
    die();
}
global $sysMenu;

$blogtitle = '菜单链接编辑';

$Menus = linkmanage_getMenus();
$menuID = GetVars('id');
if (isset($menuID) && $menuID == '') {
    unset($menuID);
}

$links = linkmanage_getLink($menuID);

if ($links == null) {
	$links_json = "{}";
} else {
	$links_json = json_encode($links);
}
$tempid = linkmanage_getTempid();
require $blogpath.'zb_system/admin/admin_header.php';
?>
<script type="text/javascript" src="jquery.mjs.nestedSortable.js"></script>
<script type="text/javascript" src="js.js"></script>
<script type="text/javascript">
	var links_json = <?php echo $links_json; ?>;
	var tempid = <?php echo $tempid; ?>;
</script>
<link href="style.css" rel="stylesheet" type="text/css" />
<?php
require $blogpath.'zb_system/admin/admin_top.php';
?>
<div id="divMain">
	<div class="divHeader"><?php echo $blogtitle; ?></div>
	<div class="SubMenu"><?php linkmanage_SubMenu(1);?></div>
	<div id="divMain2">

<?php if (isset($menuID)) {	?>
	<div id="nav-menus-frame">
		<div id="menu-settings">
			<div class="clear"></div>
			<div class="menu-settings-header">
				<p>常用链接</p>
			</div>
			<div id="accordion" class="accordion-container">

				  <div class="group">
				    <h3>特殊</h3>
				    <div class="accordion-section-content">
					    <div class="input-control select">
							<select multiple="1" size="6" id="other">
								<?php linkmanage_get_syslink('other');?>
							</select>
						</div>
						<p class="button-controls">
								<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="add_link(this,'other','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
				    </div>
				  </div>
				  <div class="group">
				    <h3>文章</h3>
				    <div class="accordion-section-content">
					    <div class="input-control select">
							<select multiple="1" size="6" id="post">
								<?php linkmanage_get_syslink('post'); ?>
							</select>
						</div>
						<p class="button-controls">
								<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="add_link(this,'post','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
				    </div>
				  </div>

				 <div class="group">
				    <h3>页面</h3>
				    <div class="accordion-section-content">
				    	<div class="input-control select">
							<select multiple="1" size="6" id="page">
								<?php linkmanage_get_syslink('page');?>
							</select>
						</div>
						<p class="button-controls">
							<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="add_link(this,'page','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
					</div>
				  </div>

				  <div class="group">
				    <h3>分类</h3>
				    <div class="accordion-section-content">
					    <div class="input-control select">
							<select multiple="1" size="6" id="category">
								<?php linkmanage_get_syslink('category');?>
							</select>
						</div>
						<p class="button-controls">
								<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="add_link(this,'category','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
				    </div>
				  </div>

				  <div class="group">
				    <h3>标签</h3>
				    <div class="accordion-section-content">
					    <div class="input-control select">
							<select multiple="1" size="6" id="tags">
								<?php linkmanage_get_syslink('tags');?>
							</select>
						</div>
						<p class="button-controls">
								<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="add_link(this,'tags','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
				    </div>
				  </div>

				  <div class="group">
				    <h3>作者</h3>
				    <div class="accordion-section-content">
					    <div class="input-control select">
							<select multiple="1" size="6" id="author">
								<?php linkmanage_get_syslink('author');?>
							</select>
						</div>
						<p class="button-controls">
								<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="add_link(this,'author','<?php echo $menuID;?>')"><span class="ui-button-text">添加</span></button>
						</p>
				    </div>
				  </div>
			</div>
		</div>

		<div id="menu-management">
			<div id="menu-manage">
				<div class="menu-edit">
					<div id="nav-menu-header">
						<form id="menu-config" method="POST">
						<input name="id" type="hidden" value="<?php echo $menuID;?>">
						<div class="major-publishing-actions">
							<label class="menu-name-label howto open-label" for="menu-name">
								<span>导航菜单名称(<?php echo $Menus['data'][$menuID]['id'];?>)：</span>
								<input name="menuname" id="menu-name" type="text" class="menu-name regular-text menu-item-textbox" title="在此输入名称" value="<?php echo $Menus['data'][$menuID]['name'];?>">
							</label>
							<div class="publishing-action">
								<button  name="reset_menu" id="reset_menu_header"  class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="reset_item();return false;">重置链接</button>

								<button  name="save_menu" id="save_menu_header"  class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="save_menusetting();return false;">保存更改</button>
							</div><!-- END .publishing-action -->
						</div><!-- END .major-publishing-actions -->
						</form>
					</div>

					<div id="menu-edit-body">
						<div id="menu-edit-body-content">
							<ol class="nav-menu ui-sortable">
							<?php
							$html = '';
						    //$Menus = linkmanageGetMenu();
						    $link_sort = linkmanage_getLink_sort($menuID);
						    //echo var_dump($link_sort);
						    //die();
						    if (!is_null($link_sort)){
							    foreach ($link_sort as $key => $value) {
							        $link = $links['ID'.$key];
							        $html_tmp = '
										<li sid="menuItem_'.$link['id'].'">
											<div class="menu-item-bar">
												<div class="menu-item-handle ui-sortable-handle">
													<span class="item-title"><span class="menu-item-title">'.$link['title'].'</span>
													<span class="item-controls">
														<span class="item-type">'.$link['type'].'</span>
														<span class="item-edit ui-icon ui-icon-triangle-1-n"></span>
													</span>
												</div>
											</div>
											<div class="menu-item-settings" style="display: none;">
												<p class="link-p">
													<label class="link-edit" for="menu-item['.$link['id'].'][menu-item-url]">
														<span>URL</span>
														<input name="menu-item['.$link['id'].'][menu-item-url]" type="text" class="code menu-item-textbox custom-menu-item-url" value="'.$link['url'].'">
													</label>
												</p>
												<p class="link-p">
													<label class="link-edit" for="menu-item['.$link['id'].'][menu-item-title]">
														<span>描述</span>
														<input name="menu-item['.$link['id'].'][menu-item-title]" type="text" class="regular-text menu-item-textbox input-with-default-title custom-menu-item-title" value="'.$link['title'].'">
													</label>
												</p>
												<p class="link-p">
													<label class="link-edit" for="custom-menu-item-name">
														<span>新窗口打开 </span>
														<input type="text" name="menu-item['.$link['id'].'][menu-item-newtable]" class="checkbox" value="'.$link['newtable'].'" style="display: none;">
													</label>
												</p>
												<p class="button-controls">
													<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text"  onclick="del_link('.$link['id'].',\''.$menuID.'\');return false;">删除链接</span></button>
													<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false"><span class="ui-button-text" onclick="save_link('.$link['id'].');return false;">保存链接</span></button>
												</p>
											</div>
											<span id="'.$link['id'].'"></span>
										</li>';
							        if ($value == 'null') {
							            $html .= $html_tmp;
							        } else {
							            $html = str_replace('<span id="'.$value.'"></span>', '<ol>'.$html_tmp.'</ol><span id="'.$value.'"></span>', $html);
							        }
							    }
							    $html=preg_replace("/<(span id\=.*?)>(.*?)<(\/span.*?)>/si","",$html); //过滤span标签
								$html=preg_replace("/<\/ol><ol>/si","",$html); //过滤ol标签
						   		echo $html;
						    }
							?>
							</ol>
						</div>
					</div>

					<div id="menu-edit-footer">
						<div class="addlink-action">
							<button class="ui-button-primary ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="add_link(this,'custom','<?php echo $menuID;?>');return false;"><span class="ui-button-text">添加自定义链接</span></button>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php

}
?>

<div id="message" title="消息通知" style="display:none;"><p>操作成功</p></div>
	</div>
  </div>
</div>
<?php
require $blogpath.'zb_system/admin/admin_footer.php';
RunTime();
?>