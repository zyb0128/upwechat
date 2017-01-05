<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('couponmanage')?>">卡券列表</a></li>
	<?php  if($op != 'detail') { ?><li <?php  if($op == 'post' && !$couponid) { ?>class="active"<?php  } ?>><a href="javascript:;" data-toggle="modal" data-target="#cardtype-modal">添加卡券</a></li><?php  } ?>
	<?php  if($op == 'post' && $couponid) { ?><li class="active"><a href="<?php  echo $this->createWeburl('couponmanage', array('op' => 'post', 'id' => $couponid))?>">编辑卡券</a></li><?php  } ?>
	<?php  if($op == 'detail') { ?><li class="active"><a href="javascript:;">卡券详情</a></li><?php  } ?>
</ul>
<?php  if($op == 'display') { ?>
<div class="modal fade" id="cardtype-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">选择你要创建的卡券类型</h4>
			</div>
			<div class="modal-body clearfix form-horizontal">
				<div class="form-group marbot0">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
					<div class="col-sm-9 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="type" value="<?php echo COUPON_TYPE_DISCOUNT;?>"/> 折扣券
						</label>
						<div class="help-block">可为用户提供消费折扣</div>
					</div>
				</div>
				<div class="form-group marbot0">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
					<div class="col-sm-9 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="type" value="<?php echo COUPON_TYPE_CASH;?>"/> 代金券
						</label>
						<div class="help-block">可为用户提供抵扣现金服务。可设置成为“满*元，减*元”</div>
					</div>
				</div>
				<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
				<div class="form-group marbot0">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
					<div class="col-sm-9 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="type" value="<?php echo COUPON_TYPE_GIFT;?>"/> 礼品券
						</label>
						<div class="help-block">可为用户提供消费送赠品服务</div>
					</div>
				</div>
				<div class="form-group marbot0">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
					<div class="col-sm-9 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="type" value="<?php echo COUPON_TYPE_GROUPON;?>"/> 团购券
						</label>
						<div class="help-block">可为用户提供团购套餐服务</div>
					</div>
				</div>
				<div class="form-group marbot0">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
					<div class="col-sm-9 col-xs-12">
						<label class="radio-inline">
							<input type="radio" name="type" value="<?php echo COUPON_TYPE_GENERAL;?>"/> 优惠券
						</label>
						<div class="help-block">即“通用券”，建议当以上四种无法满足需求时采用</div>
					</div>
				</div>
				<?php  } ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="location.href='<?php  echo $this->createWeburl('couponmanage', array('op' => 'post'))?>&type=' + $('#cardtype-modal input[type=radio]:checked').val()">确定</button>
			</div>
		</div>
	</div>
</div>
<div class="main" id="coupon_display" ng-controller="CouponDisplayCtrl">
	<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form action="./index.php" method="get" class="form-horizontal" role="form">
		<input type="hidden" name="c" value="site" />
		<input type="hidden" name="a" value="entry" />
		<input type="hidden" name="do" value="couponmanage" />
		<input type="hidden" name="m" value="we7_coupon" />
		<input type="hidden" name="type" value="<?php  echo $_GPC['type'];?>" />
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">卡券类型</label>
				<div class="col-sm-7 col-lg-9 col-xs-12 btn-group">
					<a href="<?php  echo filter_url('type:');?>" class="btn <?php  if($_GPC['type'] == '') { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">不限</a>
					<a href="<?php  echo filter_url('type:'.COUPON_TYPE_DISCOUNT);?>" class="btn <?php  if($_GPC['type'] == COUPON_TYPE_DISCOUNT) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">折扣券</a>
					<a href="<?php  echo filter_url('type:'.COUPON_TYPE_CASH);?>" class="btn <?php  if($_GPC['type'] == COUPON_TYPE_CASH) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">代金券</a>
					<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
					<a href="<?php  echo filter_url('type:'.COUPON_TYPE_GIFT);?>" class="btn <?php  if($_GPC['type'] == COUPON_TYPE_GIFT) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">礼品券</a>
					<a href="<?php  echo filter_url('type:'.COUPON_TYPE_GROUPON);?>" class="btn <?php  if($_GPC['type'] == COUPON_TYPE_GROUPON) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">团购券</a>
					<a href="<?php  echo filter_url('type:'.COUPON_TYPE_GENERAL);?>" class="btn <?php  if($_GPC['type'] == COUPON_TYPE_GENERAL) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">优惠券</a>
					<?php  } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">审核状态</label>
				<div class="col-sm-7 col-lg-9 col-xs-12">
					<div class="btn-group">
						<a href="<?php  echo filter_url('status:');?>" class="btn <?php  if($_GPC['status'] == '') { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">不限</a>
						<a href="<?php  echo filter_url('status:1');?>" class="btn <?php  if($_GPC['status'] == 1) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">审核中</a>
						<a href="<?php  echo filter_url('status:2');?>" class="btn <?php  if($_GPC['status'] == 2) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">未通过</a>
						<a href="<?php  echo filter_url('status:3');?>" class="btn <?php  if($_GPC['status'] == 3) { ?>btn-primary<?php  } else { ?>btn-default<?php  } ?>">已通过</a>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">可用会员组</label>
				<div class="col-sm-7 col-lg-9 col-xs-12">
					<select name="groupid" class="form-control">
						<?php  if(is_array($display_groups)) { foreach($display_groups as $row) { ?>
							<option <?php  if($_GPC['groupid'] == $row['id']) { ?>selected<?php  } ?> value="<?php  echo $row['id'];?>"><?php  echo $row['name'];?></option>
						<?php  } } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">可用门店</label>
				<div class="col-sm-7 col-lg-9 col-xs-12">
					<select name="storeid" class="form-control">
						<option value="0">不限</option>
						<?php  if(is_array($display_location_store)) { foreach($display_location_store as $row) { ?>
							<option <?php  if($_GPC['storeid'] == $row['id']) { ?>selected<?php  } ?> value="<?php  echo $row['id'];?>"><?php  echo $row['business_name'];?></option>
						<?php  } } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">可用模块</label>
				<div class="col-sm-7 col-lg-9 col-xs-12">
					<select name="moduleid" class="form-control">
						<option value="0">不限</option>
						<?php  if(is_array($display_modules)) { foreach($display_modules as $row) { ?>
							<option <?php  if($_GPC['moduleid'] == $row['name']) { ?>selected<?php  } ?> value="<?php  echo $row['name'];?>"><?php  echo $row['title'];?></option>
						<?php  } } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">券标题</label>
				<div class="col-sm-7 col-lg-9 col-xs-12">
					<input class="form-control" name="title" placeholder="券标题" type="text" value="<?php  echo $_GPC['title'];?>">
				</div>
				<div class="pull-right col-xs-12 col-sm-3 col-lg-2">
					<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="modal fade" id="quantity-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">修改库存</h4>
			</div>
			<div class="modal-body clearfix form-horizontal">
				<input type="text" class="form-control" name="quantity" />
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="util.ajaxshow('<?php  echo $this->createWeburl('couponmanage', array('op' => 'modifystock'))?>&id=&quantity=' + $('input[name=quantity]').val() + '&id='+window.couponid)">确定</button>
			</div>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<div class="table-responsive panel-body">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th width="80px">卡券类型</th>
					<th width="120px">卡券名称</th>
					<th width="150px">卡券有效期</th>
					<th width="70px">状态</th>
					<th width="100px">库存</th>
					<th width="50px">限领</th>
					<th width="80px">上架状态</th>
					<th style="width:450px; text-align:right;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($couponlist)) { foreach($couponlist as $k => $item) { ?>
				<tr>
					<td><?php  echo $item['type']['0'];?></td>
					<td><?php  echo $item['title'];?></td>
					<td>
						<?php  echo $item['date_info'];?>
					</td>
					<td>
						<?php  if($item['status'] == '1') { ?>
						<span class="label label-info">审核中</span>
						<?php  } else if($item['status'] == '2') { ?>
						<span class="label label-danger">未通过</span>
						<?php  } else if($item['status'] == '3') { ?>
						<span class="label label-success">已通过</span>
						<?php  } else if($item['status'] == '4') { ?>
						<span class="label label-default">卡券被商户删除</span>
						<?php  } else if($item['status'] == '5') { ?>
						<span class="label label-warning">已在公众平台投放</span>
						<?php  } ?>
					</td>
					<td><?php  echo $item['quantity'];?></td>
					<td><?php  echo $item['get_limit'];?></td>
					<td>
						<?php  if($item['is_display'] == 1) { ?>
							<span class="label label-success">上架中</span>
						<?php  } else { ?>
							<span class="label label-danger">已下架</span>
						<?php  } ?>
					</td>
					<td style="text-align:right;">
						<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
						<a href="javascript:;" onclick="util.ajaxshow('<?php  echo $this->createWeburl('couponmanage', array('op' => 'selfconsume', 'id' => $item['id']))?>')" class="btn <?php  if($item['is_selfconsume'] == 1) { ?>btn-danger<?php  } else { ?>btn-success<?php  } ?> btn-sm toggle-selfconsume" title="自助核销"><?php  if($item['is_selfconsume'] == 1) { ?>关闭自助核销<?php  } else { ?>开启自助核销<?php  } ?></a>
						<a href="#put-coupon" data-toggle="modal" data-cid=<?php  echo $item['id'];?> class="btn btn-default btn-sm js-publish">投放</a>
						<?php  } ?>
						<a href="javascript:;" class="btn btn-default btn-sm" onclick="window.couponid = '<?php  echo $item['id'];?>';$('input[name=quantity]').val('');" data-toggle="modal" data-target="#quantity-modal">修改库存</a>
						<a href="javascript:;" onclick="util.ajaxshow('<?php  echo $this->createWeburl('couponmanage', array('op' => 'toggle', 'id' => $item['id']))?>')" class="btn btn-default btn-sm toggle-display" title="上架/下架"><?php  if($item['is_display'] == 1) { ?>下架<?php  } else { ?>上架<?php  } ?></a>
						<a href="<?php  echo $this->createWeburl('couponmanage', array('op' => 'detail', 'id' => $item['id']))?>" class="btn btn-success btn-sm" title="查看详情">查看详情</a>
						<a href="<?php  echo $this->createWeburl('couponconsume', array('couponid' => $item['id'], 'status' => '0'))?>"  class="btn btn-default btn-sm" title="领取记录">领取记录</a>
						<a href="<?php  echo $this->createWeburl('couponmanage', array('op' => 'delete', 'id' => $item['id']))?>"  class="btn btn-default btn-sm" title="删除" onclick="if(!confirm('删除后将不可恢复，确定删除吗?')) return false;">删除</a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
	</div>
</div>
<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
<a class="btn btn-success js-sync pull-left" onclick="util.ajaxshow('<?php  echo $this->createWeburl('couponmanage', array('op' => 'sync', 'type' => 1))?>')">更新全部卡券最新状态</a>&nbsp;&nbsp;
<a class="btn btn-success" ng-click="download();" style="display:none;">同步卡券信息</a>
<?php  } ?>
<div class="pull-right"><?php  echo $pager;?></div>
<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
<div class="modal fade" id="put-coupon">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-group">
					<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#put-qrcode" aria-controls="qrcode" role="tab" data-toggle="tab">二维码</a></li>
					<li role="presentation"><a href="#put-coupon-record" aria-controls="put-coupon-record" role="tab" data-toggle="tab">领取记录</a></li>
				</ul>
				</div>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active text-center" id="put-qrcode">
						<div class="alert alert-info text-left">
							该投放二维码有效期至：<span class="js-qrcode-expire"></span> <br />
							如果您要大量投放卡券，请使用卡券营销功能
						</div>
						<img src="" class="js-qrcode-src">
					</div>
					<div role="tabpanel" class="tab-pane" id="put-coupon-record">
						<div class="alert alert-info text-left">
							共有 <span class="js-qrcode-record-total"></span> 人领取
						</div>
						<table class="table table-hover">
							<thead class="table table-hover">
								<tr>
									<th style="width: 20%;"></th>
									<th>昵称</th>
									<th style="width: 30%;">领取时间</th>
								</tr>
							</thead>
							<tbody class="js-qrcode-record"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).on('click', '.js-publish', function() {
		cid = $(this).data('cid');
		$.post("<?php  echo $this->createWeburl('couponmanage', array('op' => 'publish'))?>", {'cid' : cid}, function(data) {
			var data = $.parseJSON(data);
			if (data.message.errno == '0') {
				var coupon = data.message.message.coupon;
				$('.js-qrcode-src').attr('src', coupon.url);
				$('.js-qrcode-expire').html(coupon.expire);
				$('.js-qrcode-record-total').html(data.message.message.total);
				var record = data.message.message.record;
				if (record) {
					$('.js-qrcode-record').html('');
					for (i in record) {
						$('.js-qrcode-record').append('<tr><td><img src="'+record[i].avatar+'" width="50" /></td><td><a href="#">'+record[i].nickname+'</a></td><td><a href="#">'+record[i].createtime+'</a></td></tr>');
					}
				}
			} else {
				$('#put-coupon').modal('hide');
				util.message(data.message.message);
			}
		});
	});
</script>
<?php  } ?>
<script>
	$(function(){
		require(['bootstrap.switch'], function() {
			$(":checkbox[name='flag']").bootstrapSwitch();
			$(':checkbox').on('switchChange.bootstrapSwitch', function(e, state){
				$this = $(this);
				var status = this.checked ? 1 : 2;
				var hint = status == 1 ? '切换为系统卡券将不显示微信卡券及微信门店' : '切换为微信卡券将不显示系统卡券及系统门店';
				if (confirm(hint) == false) {
						location.href = location.href;
					return false;
				}
				$.post("<?php  echo $this->createWeburl('couponmanage', array('op' => 'exchange_coupon_type'));?>", {'status' : status}, function(resp){
					resp = $.parseJSON(resp);
					if (resp.message.errno < 0) {
						util.message(resp.message.message, location.href, 'error');
					} else {
						util.message(resp.message.message, location.href, 'success');
					}
				});
			});
		});
	});
</script>
<?php  } ?>

<?php  if($op == 'post') { ?>
<div class="main">
	<form action="" method="post" id="coupon-form" class="form-horizontal" enctype="multipart/form-data">
		<div class="panel panel-default" id="step1">
			<div class="panel-heading">
				<?php  echo $coupon_title;?>
			</div>
			<div class="panel-body">
				<div class="pull-left" style="width:320px;background:#F4F5F9;margin-right:20px;border:1px solid #E7E7EB">
					<div class="card-title"><?php  echo $coupon_title;?></div>
					<div class="card_section area" style="position: relative">
						<div class="shop-panel" id="color-purview" style="background: #a9d92d">
							<div class="logo-area">
								<span class="logo pull-left"><img src="<?php  echo $setting['logourl'];?>" alt=""/></span>
								<div class="pull-left" style="height:38px;line-height:38px" id="brand_name">商户信息</div>
								<div class="clear"></div>
							</div>
							<div class="msg-area">
								<div class="tick-msg">
									<p><b id="title-purview"><?php  echo $coupon_title;?>标题</b></p>
									<p id="sub-title-purview"><?php  echo $coupon_title;?>副标题</p>
								</div>
								<p id="sub-time-purview" style="text-align: center">有效期:<?php  echo date('Y-m-d')?> ~ <?php  echo date('Y-m-d', time() + 30 * 86400)?></p>
							</div>
							<div class="deco"></div>
							<div class="cicon">
								<a href="javascript:;" data-id="form1"><i class="fa fa-pencil"></i></a>
							</div>
						</div>
					</div>
					<div class="card-dispose area">
						<div class="unset" id="destroy_title">
							<p>销券设置</p>
						</div>
						<div class="destroy_type_preview">
							<div class="barcode-area code_preview code_preview_3" style="display: block">
								<div class="barcode"></div>
								<p class="code_num">1513-2290-1878</p>
							</div>
							<div class="qrcode-area code_preview code_preview_2">
								<div class="qrcode"></div>
								<p class="code_num">1513-2290-1878</p>
							</div>
							<div class="sn-area code_preview code_preview_1">1513-2290-1878</div>
							<p style="text-align: center" id="notice-purview">请在付款时出示此券</p>
						</div>
						<div class="cicon">
							<a href="javascript:;" data-id="form2"><i class="fa fa-pencil"></i></a>
						</div>
					</div>
					<div class="shop-detail">
						<ul class="list">
							<li>
								<div class="li-panel area">
									<div class="li-content"><?php  echo $coupon_title;?>设置</div>
									<span class="ricon fa fa-angle-right"></span>
									<div class="cicon">
										<a href="javascript:;" data-id="form3"><i class="fa fa-pencil"></i></a>
									</div>
								</div>
							</li>
							<li>
								<div class="li-panel area">
									<div class="li-content">适用门店</div>
									<span class="ricon fa fa-angle-right"></span>
									<div class="cicon">
										<a href="javascript:;" data-id="form4"><i class="fa fa-pencil"></i></a>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="shop-detail">
						<ul class="list">
							<li>
								<div class="li-panel">
									<div class="li-content">立即使用</div>
									<span class="ricon">系统已实现</span>
								</div>
							</li>
							<li>
								<div class="li-panel area" style="border-bottom: none">
									<div class="li-content" id="promotion_url_name">个人中心</div>
									<div class="ricon"><span id="promotion_url_sub_title">点击进入 </span><i class="fa fa-angle-right"></i></div>
									<div class="cicon">
										<a href="javascript:;" data-id="form6"><i class="fa fa-pencil"></i></a>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="pull-left alert form form-area" style="width:700px;position:relative;display:block;" id="form1">
					<div class="arrow_in"></div>
					<h4 style="margin-top:20px;">券面信息</h4>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否上架</label>
						<div class="col-sm-9 col-xs-12">
							<label class="radio-inline">
								<input type="radio" value="1" name="is_display" checked/> 上架
							</label>
							<label class="radio-inline">
								<input type="radio" value="0" name="is_display"/> 下架
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 适用模块</label>
						<div class="col-sm-9 col-xs-12">
							<a href="javascript:;" class="btn btn-default js-modules-modal" data-toggle="modal">选择模块</a>
							<input type="hidden" name="module-select" value="" autocomplete="off" />
							<div class="js-modules-contain clearfix"></div>
							<div class="help-block">确定该券的适用模块，不选择则为全部可用</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 商户LOGO</label>
						<div class="col-sm-9 col-xs-12" id="logo_upload">
							<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
							<?php  echo tpl_form_field_wechat_image('logo_url', $setting['logourl'], '', array('acid' => $acid, 'mode' => 'file_upload'));?>
							<?php  } else { ?>
							<?php  echo tpl_form_field_image('logo_url', $setting['logourl'])?>
							<?php  } ?>
							<div class="help-block">商户LOGO大小不超过1M。像素为：300*300。仅支持JPG格式</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 商户名称</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" class="form-control" name="brand_name"/>
							<div class="help-block">商户名字,字数上限为12个汉字。 </div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 卡券颜色</label>
						<?php  echo tpl_coupon_colors('color', 'Color082');?>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> <?php  echo $coupon_title;?>标题</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" class="form-control" name="title"/>
							<div class="help-block">建议填写<?php  echo $coupon_title;?>“减免金额”及自定义内容，描述卡券提供的具体优惠。不超过9个字符</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"> <?php  echo $coupon_title;?>副标题(可选)</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" class="form-control" name="sub_title"/>
							<div class="help-block">不超过18个字符</div>
						</div>
					</div>
					<?php  if($type == COUPON_TYPE_DISCOUNT) { ?>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 折扣额度</label>
						<div class="col-sm-9 col-xs-12">
							<div class="input-group">
								<input type="text" class="form-control" name="discount" value="80"/>
								<span class="input-group-addon">折</span>
							</div>
							<div class="help-block">表示打折额度（百分比）。填30就是3折。填写整数</div>
						</div>
					</div>
					<?php  } else if($type == COUPON_TYPE_CASH) { ?>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 减免金额</label>
						<div class="col-sm-9 col-xs-12">
							<div class="input-group">
								<span class="input-group-addon">满</span>
								<input type="text" class="form-control" name="least_cost" value=""/>
								<span class="input-group-addon">减</span>
								<input type="text" class="form-control" name="reduce_cost"/>
								<span class="input-group-addon">元</span>
							</div>
						</div>
					</div>
					<?php  } else if($type == COUPON_TYPE_GIFT) { ?>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 礼品券详情</label>
						<div class="col-sm-9 col-xs-12">
							<textarea name="gift" id="gift" class="form-control" rows="7"></textarea>
						</div>
					</div>
					<?php  } else if($type == COUPON_TYPE_GROUPON) { ?>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 团购券详情</label>
						<div class="col-sm-9 col-xs-12">
							<textarea name="deal_detail" id="deal_detail" class="form-control" rows="7"></textarea>
						</div>
					</div>
					<?php  } else if($type == COUPON_TYPE_GENERAL) { ?>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 优惠券详情</label>
						<div class="col-sm-9 col-xs-12">
							<textarea name="default_detail" id="default_detail" class="form-control" rows="7"></textarea>
						</div>
					</div>
					<?php  } ?>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label" style="padding-top:15px"> 有效期</label>
						<div class="col-sm-9 col-xs-12">
							<div class="radio">
								<div class="col-sm-3" style="padding-top:6px"><label><input type="radio" value="1" name="time_type" checked/>固定日期 </label></div>
								<?php  echo tpl_form_field_daterange('time_limit', array('start' => date('Y-m-d'), 'end' => date('Y-m-d', time() + 30*86400)));?>
							</div>
							<div class="radio">
								<div class="col-sm-3" style="padding-top:6px"><label><input type="radio" value="2" name="time_type"/>领取后 </label></div>
								<div class="col-sm-3" style="margin-left:-15px">
									<select name="deadline" id="deadline" class="form-control">
										<?php 
										for($i=0; $i<=90; $i++) {
											if(!$i) $n = '当'; else $n = $i;
											echo '<option value="'.$i.'">'.$n.'天</option>';
										}
										?>
									</select>
								</div>
								<div class="col-sm-3" style="padding-top:6px">有效,有效期</div>
								<div class="col-sm-3" style="margin-left:-15px">
									<select name="limit" id="limit" class="form-control">
										<?php 
										for($i=1; $i<=90; $i++) {
											echo '<option value="'.$i.'">'.$i.'天</option>';
										}
										?>
									</select>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div class="pull-left alert form hidden form-area" style="width:700px;position:relative;" id="form2">
					<div class="arrow_in"></div>
					<h4 style="margin-top:20px;">领券设置</h4>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 库存</label>
						<div class="col-sm-9 col-xs-12">
							<div class="input-group">
								<input type="text" class="form-control" name="quantity" value="300"/>
								<span class="input-group-addon">份</span>
							</div>
							<div class="help-block">库存只能是大于0的数字</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 领券限制</label>
						<div class="col-sm-9 col-xs-12">
							<div class="input-group">
								<input type="text" class="form-control" name="get_limit" value="1"/>
								<span class="input-group-addon">份</span>
							</div>
							<div class="help-block">默认领券限制为1</div>
						</div>
					</div>
					<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
						<div class="col-sm-9 col-xs-12">
							<div class="checkbox">
								<label><input type="checkbox" name="can_share" value="1" checked /> 用户可以分享领券链接</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" name="can_give_friend" value="1" checked /> 用户领券后可转赠其他好友</label>
							</div>
						</div>
					</div>
					<h4 style="margin-top:20px;">销券设置</h4>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">销券方式</label>
						<div class="col-sm-9 col-xs-12">
							<div class="radio">
								<label><input type="radio"  class="" name="code_type" value="1"/> 仅卡券号</label>
								<div class="help-block">只显示卡券号，验证后可进行销券</div>
							</div>
							<div class="radio">
								<label><input type="radio"  class="" name="code_type" value="2"/> 二维码</label>
								<div class="help-block">包含卡券信息的二维码，扫描后可进行销券</div>
							</div>
							<div class="radio">
								<label><input type="radio"  class="" name="code_type" value="3" checked/> 条形码</label>
								<div class="help-block">包含卡券信息的条形码，扫描后可进行销券</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 操作提示</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" class="form-control" name="notice" id="notice" value="请在付款时出示此券"/>
							<div class="help-block">建议引导用户到店出示卡券，由店员完成核销操作。不超过16个字符</div>
						</div>
					</div>
					<?php  } ?>
				</div>
				<div class="pull-left alert form hidden form-area" style="width:700px;position:relative;" id="form3">
					<div class="arrow_in"></div>
					<h4 style="margin-top:20px;"><?php  echo $coupon_title;?>详情</h4>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 使用须知</label>
						<div class="col-sm-9 col-xs-12">
							<textarea name="description" id="description" class="form-control" rows="10"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 客服电话</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" class="form-control" name="service_phone"/>
							<div class="help-block">手机或固话</div>
						</div>
					</div>
				</div>
				<div class="pull-left alert form hidden form-area" style="width:700px;position:relative;" id="form4">
					<input type="hidden" name="location-select" value="" autocomplete="off"/>
					<input type="hidden" name="groups" value="" autocomplete="off"/>
					<div class="arrow_in"></div>
					<h4 style="margin-top:20px;">服务信息</h4>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 适用门店</label>
						<div class="col-sm-9 col-xs-12">
							<label class="radio"><a href="javascript:;" data-toggle="modal" class="js-location-store-modal">选择适用门店</a></label>
							<div class="js-location-contain"></div>
							<div class="help-block">默认为适用于全部门店</div>
						</div>
					</div>
					<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 可用会员组</label>
						<div class="col-sm-9 col-xs-12">
							<label class="radio"><a href="javascript:;" data-toggle="modal" class="js-groups-modal">选择可用会员组</a></label>
							<div class="js-groups-contain"></div>
							<div class="help-block">默认为全部用户组可用</div>
						</div>
					</div>
					<?php  } ?>
				</div>
				<div class="pull-left alert form hidden form-area" style="width:700px;position:relative;" id="form6">
					<div class="arrow_in"></div>
					<h4 style="margin-top:20px;">营销入口</h4>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 营销场景的自定义入口名称</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" class="form-control" name="promotion_url_name" value="个人中心"/>
							<div class="help-block">如：个人中心。</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 跳转链接</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" class="form-control" name="promotion_url" value="<?php  echo murl('mc/home', array(), true, true);?>"/>
							<div class="help-block">入口跳转链接地址。需要以"http://"或"https://"开头</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 营销入口右侧的提示语</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" class="form-control" name="promotion_url_sub_title" value="点击进入"/>
							<div class="help-block">营销入口右侧的提示语.不超过6个汉字</div>
						</div>
					</div>
				</div>
				</div>
		</div>
		<div class="form-group col-sm-12">
			<input name="submit" type="submit" value="提交" class="btn btn-primary col-lg-1">
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</form>
</div>
<div id="footer-location" class="hide">
	<span name="submit" id="submit" class="pull-right btn btn-primary">保存</span>
</div>
<!--选择门店弹出窗-->
<div class="modal fade" id="location-store-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">选择适用门店</h4>
			</div>
			<div class="modal-body clearfix form-horizontal">
				<table class="table tb" style="min-width:568px;">
					<thead>
					<tr>
						<th style="width: 35px;" class="text-center">选择</th>
						<th style="width: 100px;">门店名称</th>
						<th style="width: 100px;">分店名称</th>
						<th style="width: 100px;">地址</th>
					</tr>
					</thead>
					<tbody>
					<?php  if(!empty($location_store)) { ?>
						<?php  if(is_array($location_store)) { foreach($location_store as $row) { ?>
							<tr id="loca-<?php  echo $row['id'];?>">
								<td class="text-center"><input type="checkbox" id="chk_loca_<?php  echo $row['id'];?>" name="locationids[]" data-name="<?php  echo $row['business_name'];?>" data-id="<?php  echo $row['id'];?>" value="<?php  echo $row['id'];?>"></td>
								<td><label for="chk_loca_<?php  echo $row['id'];?>" style="font-weight:normal;" class="name"><?php  echo $row['business_name'];?></label></td>
								<td><label style="font-weight:normal;" class="name"><?php  echo $row['branch_name'];?></label></td>
								<td><label class="label label-info address"><?php  echo $row['address'];?></label></td>
							</tr>
						<?php  } } ?>
					<?php  } else { ?>
						<tr>
							<td align="center" colspan="3">没有有效的门店。你可以 <a href="<?php  echo $this->createWeburl('storelist', array('op' => 'post'));?>" target="_blank">添加门店</a> 或 <a href="<?php  echo $this->createWeburl('storelist', array('op' => 'import'));?>" target="_blank">更新门店</a></td>
						</tr>
					<?php  } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary js-select-store" data-dismiss="modal">确定</button>
			</div>
		</div>
	</div>
</div>
<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
<!--选择可用用户组弹出窗-->
<div class="modal fade" id="groups-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">选择可用会员组</h4>
			</div>
			<div class="modal-body clearfix form-horizontal">
				<table class="table tb" style="min-width:568px;">
					<thead>
					<tr>
						<th style="width: 50px;" class="text-center">选择</th>
						<th>用户组</th>
					</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center"><input type="checkbox" id="chk_group_0" name="groupids[]" value="0" data-name="默认分组"></td>
							<td><label for="chk_group_0" style="font-weight:normal;" class="name">默认分组</label></td>
						</tr>
					<?php  if(!empty($groups)) { ?>
						<?php  if(is_array($groups)) { foreach($groups as $group) { ?>
							<tr>
								<td class="text-center"><input type="checkbox" id="chk_group_<?php  echo $group['id'];?>" name="groupids[]" data-name="<?php  echo $group['name'];?>" value="<?php  echo $group['id'];?>"></td>
								<td><label for="chk_group_<?php  echo $group['id'];?>" style="font-weight:normal;" class="name"><?php  echo $group['name'];?></label></td>
							</tr>
						<?php  } } ?>
					<?php  } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary js-select-groups" data-dismiss="modal">确定</button>
			</div>
		</div>
	</div>
</div>
<?php  } ?>
<!--选择可用模块弹出窗-->
<div class="modal fade" id="modules-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">选择可用模块</h4>
			</div>
			<div class="modal-body clearfix form-horizontal">
				<table class="table tb" style="min-width:568px;">
					<thead>
					<tr>
						<th style="width: 35px;" class="text-center">选择</th>
						<th style="width: 100px;">模块名称</th>
						<th style="width: 100px;">标识</th>
					</tr>
					</thead>
					<tbody class="module-list">
					<?php  if(is_array($post_module)) { foreach($post_module as $row) { ?>
					<?php  if(!in_array($row['type'], array('system'))) { ?>
					<tr>
						<td class="text-center"><input type="checkbox" id="chk_module_<?php  echo $row['name'];?>" name="modules[]" data-name="<?php  echo $row['title'];?>" value="<?php  echo $row['name'];?>"></td>
						<td><label for="chk_module_<?php  echo $row['name'];?>" style="font-weight:normal;" class="title"><?php  echo $row['title'];?></label></td>
						<td><label class="label label-info"><?php  echo $row['name'];?></label></td>
					</tr>
					<?php  } ?>
					<?php  } } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary js-select-modules" data-dismiss="modal">确定</button>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).on('click', '.js-location-store-modal', function() {
		$('#location-store-modal').modal('show');
	});
	$(document).on('click', '.js-modules-modal', function() {
		$('#modules-modal').modal('show');
	});
	$(document).on('click', '.js-groups-modal', function() {
		$('#groups-modal').modal('show');
	});
	$('.list li,.card-dispose,.shop-panel').hover(function(){
		$(this).find('.cicon').show();
		$(this).addClass('hover');
	}, function(){
		$(this).find('.cicon').hide();
		$(this).removeClass('hover');
	});
	$('#form1').mouseover(function(){
		var bg_color = $(':input[name="color-value"]').val();
		if(!bg_color) {
			bg_color = '#a9d92d';
		}
		$('#color-purview').css('background-color', bg_color);
		if($(':input[name="title"]').val()) {
			$('#title-purview').html($(':input[name="title"]').val());
		}
		if($(':input[name="sub_title"]').val()) {
			$('#sub-title-purview').html($(':input[name="sub_title"]').val());
		}
		if($(':input[name="brand_name"]').val()) {
			$('#brand_name').html($(':input[name="brand_name"]').val());
		}
		$('.logo-area img').attr('src', $('#logo_upload img').attr('src'));
		var time_type = $(':radio[name="time_type"]:checked').val();
		var time = '';
		if(time_type == 1) {
			var startime = $("input[name='time_limit[start]']").val();
			var endtime = $("input[name='time_limit[end]']").val();
			time = '有效期:'+startime+'~'+endtime;
		} else if(time_type == 2) {
			var deadline = parseInt($('#deadline').val());
			var limit = parseInt($('#limit').val());
			var now = new Date();
			now.setHours(0, 0, 0);
			var unixtime =Date.parse(now)/1000;
			unixtime += (deadline*86400);
			var startime = new Date(parseInt(unixtime) * 1000).toLocaleString().substr(0,9).replace(/\//g, "-");
			unixtime += (limit*86400);
			var endtime = new Date(parseInt(unixtime) * 1000).toLocaleString().substr(0,9).replace(/\//g, "-");
			time = '有效期:'+startime+'~'+endtime;
		}
		$('#sub-time-purview').html(time);
	});

	$('.area').click(function() {
		$(this).find('.cicon a').children().trigger('click');
	});
	$('.cicon a').click(function(){
		$('.form').addClass('hidden');
		var top = $(this).offset().top;
		$('#'+$(this).attr('data-id')).css('margin-top',(top-300))
		$('#'+$(this).attr('data-id')).removeClass('hidden');
		return false;
	})

	$('#form2').mouseover(function(){
		var code_type = $('#form2 :radio[name="code_type"]:checked').val() || 0;
		if(code_type) {
			$('#destroy_title').hide();
			$('.code_preview').hide();
			$('.code_preview_' + code_type).show();
		}
		$('#notice-purview').html($('#form2 :text[name="notice"]').val());
	});

	$('#coupon-form').submit(function(){
		if(!$.trim($(':input[name="brand_name"]').val())) {
			util.message('请填写商户名称', '','error');
			return false;
		}

		if(!$.trim($(':input[name="color-value"]').val())) {
			util.message('请选择卡券颜色', '','error');
			return false;
		}

		<?php  if($type == COUPON_TYPE_DISCOUNT) { ?>
		if(!$.trim($(':text[name="discount"]').val())) {
			util.message("折扣额度不合法",'','error');
			return false;
		}
		<?php  } else if($type == COUPON_TYPE_CASH) { ?>
		if(!$.trim($(':text[name="reduce_cost"]').val())) {
			util.message("减免金额不合法",'','error');
			return false;
		}
		if(!$.trim($(':text[name="least_cost"]').val())) {
			util.message("抵扣金额不合法",'','error');
			return false;
		}
		<?php  } ?>
		if(!$.trim($(':input[name="title"]').val())) {
			util.message('<?php  echo $coupon_title;?>标题不能为空', '','error');
			return false;
		}

		var time_type = $(':radio[name="time_type"]:checked').val();
		if(!time_type) {
			util.message('请完善<?php  echo $coupon_title;?>有效期', '','error');
			return false;
		}

		var quantity = parseInt($.trim($(':text[name="quantity"]').val()));
		if(isNaN(quantity)) {
			util.message("库存只能是大于0的数字",'','error');
			return false;
		}
		<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
		var code_type = $(':radio[name="code_type"]:checked').val();
		if(!code_type) {
			util.message('请选择销券方式','','error');
			return false;
		}
		if(!$.trim($(':text[name="notice"]').val())) {
			util.message('请完善操作提示', '','error');
			return false;
		}
		<?php  } ?>
		if(!$.trim($('textarea[name="description"]').val())) {
			util.message('请完善使用须知', '','error');
			return false;
		}
		
		$('#submit').attr('disabled', true);
		return true;
	});

	//选择门店
	$('.js-select-store').click(function(){
		var chks = $('input[name="locationids[]"]:checked');
		var locations = [];
		$('.js-location-contain').html('')
		if(chks.length>0){
			$.each(chks, function(){
				$('.js-location-contain').append('<span class="label label-success">'+$(this).data('name')+'</span>&nbsp;');
				locations.push($(this).val());
			});
			$('input[name=location-select]').val(locations.join('-'));
		} else {
			$('input[name="location-select"]').val('');
		}
	});
	//选择可用用户组
	$('.js-select-groups').click(function(){
		var chks = $('input[name="groupids[]"]:checked');
		var groupids = [];
		$('.js-groups-contain').html('');
		if (chks) {
			$.each(chks, function(){
				$('.js-groups-contain').append('<span class="label label-success">'+$(this).data('name')+'</span>&nbsp;');
				groupids.push($(this).val());
			});
			$('input[name=groups]').val(groupids.join('-'));
		} else {
			$('input[name=groups]').val('');
		}
	});
	//选择模块
	$('.js-select-modules').click(function(){
		var chks = $('input[name="modules[]"]:checked');
		var moduleids = [];
		$('.js-modules-contain').html('');
		if (chks) {
			$.each(chks, function(){
				$('.js-modules-contain').append('<span class="label label-success pull-left" style="margin:5px ;">'+$(this).data('name')+'</span>&nbsp;');
				moduleids.push($(this).val());
			});
			$('input[name=module-select]').val(moduleids.join('@'));
		} else {
			$('input[name=module-select]').val('');
		}
	});
	//初始化checkbox状态
	$('input[name$="[]"]').prop('checked', false);
</script>
<?php  } ?>

<?php  if($op == 'detail') { ?>
<div class="main">
	<form action="" method="post" id="form-location" class="form-horizontal" enctype="multipart/form-data">
		<div class="panel panel-default" id="step1">
			<div class="panel-heading">
				<?php  echo $coupon_title;?>
			</div>
			<div class="panel-body">
				<div class="pull-left" style="width:320px;background:#F4F5F9;margin-right:20px;border:1px solid #E7E7EB">
					<div class="card-title"><?php  echo $coupon_title;?></div>
					<div class="card_section" style="position: relative">
						<div class="shop-panel" id="color-purview" style="background:<?php  echo $colors[$coupon['color']];?>">
							<div class="logo-area">
								<span class="logo pull-left"><img src="<?php  echo $coupon['logo_url'];?>" alt=""/></span>
								<div class="pull-left" style="height:38px;line-height:38px"><?php  echo $coupon['brand_name'];?></div>
								<div class="clear"></div>
							</div>
							<div class="msg-area">
								<div class="tick-msg">
									<p><b id="title-purview"><?php  echo $coupon['title'];?></b></p>
									<p id="sub-title-purview"><?php  echo $coupon['sub_title'];?></p>
								</div>
								<p id="sub-time-purview" style="text-align: center">
									<?php  echo $coupon['extra_date_info'];?>
								</p>
							</div>
							<div class="deco"></div>
							<div class="cicon">
								<a href="javascript:;" data-id="form1"><i class="fa fa-pencil"></i></a>
							</div>
						</div>
					</div>
					<div class="card-dispose">
						<div class="destroy_type_preview">
							<?php  if($coupon['code_type'] == 3) { ?>
							<div class="barcode-area code_preview_3">
								<div class="barcode"></div>
								<p class="code_num">1513-2290-1878</p>
							</div>
							<?php  } else if($coupon['code_type'] == 2) { ?>
							<div class="qrcode-area code_preview_2">
								<div class="qrcode"></div>
								<p class="code_num">1513-2290-1878</p>
							</div>
							<?php  } else { ?>
							<div class="sn-area code_preview_1">1513-2290-1878</div>
							<?php  } ?>
							<p style="text-align: center" id="notice-purview"><?php  echo $coupon['notice'];?></p>
						</div>
						<div class="cicon">
							<a href="javascript:;" data-id="form2"><i class="fa fa-pencil"></i></a>
						</div>
					</div>
					<div class="shop-detail">
						<ul class="list">
							<li>
								<div class="li-panel">
									<div class="li-content"><?php  echo $coupon_title;?>设置</div>
									<span class="ricon fa fa-angle-right"></span>
									<div class="cicon">
										<a href="javascript:;" data-id="form3"><i class="fa fa-pencil"></i></a>
									</div>
								</div>
							</li>
							<li>
								<div class="li-panel">
									<div class="li-content">适用门店</div>
									<span class="ricon"><?php  echo $coupon['location_count'];?>家</span>
									<div class="cicon">
										<a href="javascript:;" data-id="form4"><i class="fa fa-pencil"></i></a>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="shop-detail">
						<ul class="list">
							<li>
								<div class="li-panel">
									<div class="li-content">立即使用</div>
									<span class="ricon">系统已实现</span>
								</div>
							</li>
							<?php  if(!empty($coupon['promotion_url_name'])) { ?>
							<li>
								<div class="li-panel" style="border-bottom: none">
									<div class="li-content"><?php  echo $coupon['promotion_url_name'];?></div>
									<div class="ricon"><span><?php  echo $coupon['promotion_url_sub_title'];?></span><i class="fa fa-angle-right"></i></div>
									<div class="cicon">
										<a href="javascript:;" data-id="form6"><i class="fa fa-pencil"></i></a>
									</div>
								</div>
							</li>
							<?php  } ?>
						</ul>
					</div>
				</div>
				<div class="pull-left alert form" style="width:700px;position:relative;display:block;" id="form1">
					<h4>券面信息</h4>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 卡券类型</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static"><?php  echo $coupon_title;?></p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 卡券id</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static"><?php  echo $coupon['card_id'];?></p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 适用模块</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								<?php  if(!empty($coupon['modules'])) { ?>
									<?php  if(is_array($coupon['modules'])) { foreach($coupon['modules'] as $module) { ?>
										<?php  echo $module['title'];?>&nbsp;
									<?php  } } ?>
								<?php  } else { ?>
									所有模块适用
								<?php  } ?>
							</p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 卡券标题</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static"><?php  echo $coupon['title'];?></p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 卡券副标题</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static"><?php  if(!empty($coupon['sub_title'])) { ?><?php  echo $coupon['sub_title'];?><?php  } else { ?>无<?php  } ?></p>
						</div>
					</div>
					<?php  if($coupon['type'] == COUPON_TYPE_DISCOUNT) { ?>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 卡券额度</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static"><?php  echo $coupon['extra_instruction'];?></p>
						</div>
					</div>
					<?php  } else if($coupon['type'] == COUPON_TYPE_CASH) { ?>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 减免金额</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static"><?php  echo $coupon['detail']['reduce_cost'];?>元</p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 抵扣条件</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">满<?php  echo $coupon['detail']['least_cost'];?>元可用</p>
						</div>
					</div>
					<?php  } ?>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 有效期</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
							<?php  echo $coupon['extra_date_info'];?>
							</p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 商户名称</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								<?php  echo $coupon['brand_name'];?>
							</p>
						</div>
					</div>
					<h4>投放信息</h4>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 库存</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								<?php  echo $coupon['quantity'];?>
							</p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 领取限制</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								每个用户限领<?php  echo $coupon['get_limit'];?>张
							</p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 销券条码</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								<?php  if($coupon['code_type'] == 1) { ?>
								卡号
								<?php  } else if($coupon['code_type'] == 2) { ?>
								二维码
								<?php  } else { ?>
								条形码
								<?php  } ?>
							</p>
						</div>
					</div>
					<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 操作提示</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								<?php  echo $coupon['notice'];?>
							</p>
						</div>
					</div>
					
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 分享设置</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								<?php  if($coupon['can_share']) { ?>用户可以分享领券链接<?php  } else { ?>用户不能分享领券链接<?php  } ?>
							</p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 转赠设置</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								<?php  if($coupon['can_give_friend']) { ?>用户领券后可转赠其他好友<?php  } else { ?>用户领券后不能转赠其他好友<?php  } ?>
							</p>
						</div>
					</div>
					<?php  } ?>
					<h4><?php  echo $coupon_title;?>详情</h4>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 使用须知</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								<?php  echo $coupon['description'];?>
							</p>
						</div>
					</div>
					<div class="form-group marbot0">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 客服电话</label>
						<div class="col-sm-9 col-xs-12">
							<p class="form-control-static">
								<?php  echo $coupon['service_phone'];?>
							</p>
						</div>
					</div>
					<h4>适用门店</h4>
					<?php  if(!empty($coupon['location_id_list'])) { ?>
						<?php  if(is_array($coupon['location_id_list'])) { foreach($coupon['location_id_list'] as $location) { ?>
							<?php  echo $location['business_name'];?>
						<?php  } } ?>
					<?php  } else { ?>
					适用于全部门店
					<?php  } ?>
				</div>
			</div>
		</div>
		<div class="form-group col-sm-12">
			<span class="btn btn-primary col-lg-1" onclick="javascript:history.go(-1)">返回</span>
		</div>
	</form>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>