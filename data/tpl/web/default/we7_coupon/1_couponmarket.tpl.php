<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('couponmarket', array('op' => 'display'));?>">卡券活动列表</a></li>
	<li <?php  if($op == 'post' && !$id) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWeburl('couponmarket', array('op' => 'post'));?>">添加活动</a></li>
	<?php  if($op == 'post' && $id) { ?><li class="active"><a href="<?php  echo $this->createWeburl('couponmarket', array('op' => 'post', 'id' => $id))?>">查看活动</a></li><?php  } ?>
</ul>
<?php  if($op == 'display') { ?>
<div class="main">
	<div class="panel panel-info">
		<div class="panel-heading">筛选</div>
		<div class="panel-body">
			<form action="./index.php" method="get" class="form-horizontal" role="form">
				<input type="hidden" name="c" value="site" />
				<input type="hidden" name="a" value="entry" />
				<input type="hidden" name="do" value="couponmarket" />
				<input type="hidden" name="m" value="we7_coupon">
				<div class="form-group">
					<label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">活动名称</label>
					<div class="col-sm-7 col-lg-8 col-xs-12">
						<input class="form-control" name="title" type="text" value="<?php  echo $_GPC['title'];?>">
					</div>
					<div class="pull-right col-lg-2">
						<input type="submit" name="submit" class="btn btn-default" value="搜索">
						<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php  if(empty($list)) { ?>
	<div class="alert alert-info">
		您当前没有活动
	</div>
	<?php  } else { ?>
	<div class="panel panel-default">
		<div class="table-responsive panel-body">
			<table class="table table-hover">
				<thead class="navbar-inner">
				<tr>
					<th style="width:60px;">缩略图</th>
					<th style="width:100px;">活动名</th>
					<th style="width:100px;">发放用户</th>
					<th style="width:300px;">操作</th>
				</tr>
				</thead>
				<tbody>
				<?php  if(is_array($list)) { foreach($list as $item) { ?>
				<tr>
					<td><img src="<?php  echo tomedia($item['thumb'])?>" width="40"></td>
					<td><?php  echo $item['title'];?></td>
					<td>
						<?php  if(in_array('new_member', $item['members'])) { ?>
						新会员
						<?php  } else if(in_array('old_member', $item['members'])) { ?>
						老会员
						<?php  } else if(in_array('activity_member', $item['members'])) { ?>
						活跃会员
						<?php  } else if(in_array('quiet_member', $item['members'])) { ?>
						沉寂会员
						<?php  } else if(in_array('group_member', $item['members'])) { ?>
						<?php  echo $item['members']['group_name'];?>组会员
						<?php  } else if(in_array('cash_time', $item['members'])) { ?>
						<?php  echo $item['members']['cash_time']['start'];?>-<?php  echo $item['members']['cash_time']['end'];?><br/>期间消费用户
						<?php  } else if(in_array('openids', $item['members'])) { ?>
						指定会员
						<?php  } ?>
					</td>
					<td>
						<a href="<?php  echo $this->createWeburl('couponmarket', array('op' => 'post', 'id' => $item['id']))?>">查看详情</a>
						<a href="<?php  echo $this->createWeburl('couponmarket', array('op' => 'delete', 'id' => $item['id']))?>" onclick="return confirm('确定删除活动吗？');return false;">删除</a>
					</td>
				</tr>
				<?php  } } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php  echo $pager;?>
</div>
<?php  } ?>
<?php  } else if($op == 'post') { ?>
<div class="clearfix">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" style="display: block">
		<div class="panel panel-default" id="step1">
			<div class="panel-heading">
				<?php  if($id) { ?>活动详情<?php  } else { ?>添加活动<?php  } ?>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 活动名称</label>
					<div class="col-sm-8 col-xs-12">
						<?php  if(!$id) { ?>
						<input type="text" class="form-control" name="title" value=""/>
						<?php  } else { ?>
						<label class="radio-inline"><?php  echo $item['title'];?></label>
						<?php  } ?>
					</div>
				</div>
				<?php  if(COUPON_TYPE == SYSTEM_COUPON) { ?>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"> 活动状态</label>
					<div class="col-sm-8 col-xs-12">
						<?php  if(!$id) { ?>
						<label class="radio-inline"><input type="radio" name="status" <?php  if(empty($id) || $item['status'] == 1) { ?>checked<?php  } ?> value="1">开启</label>
						<label class="radio-inline" style="margin-left: 20px;"><input type="radio" name="status" value="0" <?php  if($item['status'] == 0 && !empty($id)) { ?>checked<?php  } ?>>关闭</label>
						<?php  } else { ?>
						<label class="radio-inline"><?php  if($item['status'] == 1) { ?>开启<?php  } else { ?>关闭<?php  } ?></label>
						<?php  } ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 活动缩略图</label>
					<div class="col-sm-8 col-xs-12">
						<?php  if(!$id) { ?>
						<?php  echo tpl_form_field_image('thumb', $item['thumb'])?>
						<?php  } else { ?>
						<img src="<?php  echo tomedia($item['thumb'])?>"/>
						<?php  } ?>
					</div>
				</div>
				<?php  } ?>
				<div class="form-group" <?php  if($id) { ?>style="display: none;"<?php  } ?>>
				<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger">*</span> 选择卡券</label>
				<div class="col-sm-8 col-xs-12">
					<?php  if(!$id) { ?>
					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" id="add_coupon">添加卡券</button>
					<?php  } ?>
				</div>
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">选择卡券</h4>
							</div>
							<div class="modal-body">
								<table class="table">
									<tr>
										<th style="width: 50px">选择</th>
										<th style="width: 120px">卡券名称</th>
										<th style="width: 180px">类型</th>
										<th>折扣</th>
									</tr>
									<?php  if(is_array($coupons)) { foreach($coupons as $coupon) { ?>
									<tr>
										<td><input type="checkbox" name="coupons[]" id="coupon" class="coupon check" value="<?php  echo $coupon['id'];?>" <?php  if(is_array($item['coupons']) && in_array($coupon['id'], array_keys($item['coupons']))) { ?>checked<?php  } ?> data-title="<?php  echo $coupon['title'];?>" data-type="<?php  echo $coupon['type'];?>"></td>
										<td><?php  echo $coupon['title'];?></td>
										<td><?php  if($coupon['type'] == 1) { ?>折扣券
											<?php  } else if($coupon['type'] == 2) { ?>代金券
											<?php  } else if($coupon['type'] == 3) { ?>礼品券
											<?php  } else if($coupon['type'] == 4) { ?>团购券
											<?php  } else if($coupon['type'] == 5) { ?>优惠券
											<?php  } ?>
										</td>
										<td><?php  if($coupon['type'] == 1) { ?>打<?php  echo $coupon['extra']['discount']*0.1?>折
											<?php  } else if($coupon['type'] == 2) { ?>满<?php  echo $coupon['extra']['least_cost']*0.01?>元 减 <?php  echo $coupon['extra']['reduce_cost']*0.01?>元
											<?php  } else if($coupon['type'] == 3) { ?><?php  echo $coupon['extra']['deal_detail'];?>
											<?php  } else if($coupon['type'] == 4) { ?><?php  echo $coupon['extra']['gift'];?>
											<?php  } else if($coupon['type'] == 5) { ?><?php  echo $coupon['extra']['default_detail'];?>
											<?php  } ?>
										</td>
									</tr>
									<?php  } } ?>
								</table>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" id="save" data-dismiss="modal">保存</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group" id="coupon_table">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span><?php  if($id) { ?>卡券列表<?php  } ?></label>
				<div class="col-sm-8 col-xs-12">
					<table class="table" style="width: 380px">
						<?php  if(!empty($item['coupons'])) { ?>
						<tr>
							<th style="width: 50px">名称</th>
							<th style="width: 50px">类型</th>
						</tr>
						<?php  } ?>
						<tbody id="coupon_list">
						<?php  if(is_array($item['coupons'])) { foreach($item['coupons'] as $coup) { ?>
						<tr>
							<td style="width: 50px"><?php  echo $coup['title'];?></td>
							<td style="width: 50px"><?php  if($coup['type'] == 1) { ?>折扣券<?php  } else { ?>代金券<?php  } ?></td>
						</tr>
						<?php  } } ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php  if(!$id) { ?>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span></label>
				<div class="col-sm-8 col-xs-12">
					发放用户
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span>预定义</label>
				<div class="col-sm-8 col-xs-12">
					<div class="panel panel-default tab-content">
						<div class="panel-heading">
							<ul class="nav nav-pills">
								<li class="give_member" data-member="new_member"><a href="javascript:;" style="color: gray;">发放给新用户</a></li>
								<li class="give_member" data-member="old_member"><a href="javascript:;" style="color: gray;">发放给老用户</a></li>
								<li class="give_member" data-member="activity_member"><a href="javascript:;" style="color: gray;">发放给活跃用户</a></li>
								<li class="give_member" data-member="quiet_member"><a href="javascript:;" style="color: gray;">发放沉寂跃用户</a></li>
							</ul>
						</div>
						<div class="panel-body">
							<span class="help-block help" id="new_member">&nbsp;&nbsp;&nbsp;新用户:  &nbsp;&nbsp;成为会员不超过<?php  echo $propertys['newmember'];?>个月，并且只消费过一次或没消费的用户。<br/></span>
							<span class="help-block help" id="old_member">&nbsp;&nbsp;&nbsp;老用户:  &nbsp;&nbsp;成为会员<?php  echo $propertys['oldmember'];?>个月以上的用户。<br/></span>
							<span class="help-block help" id="activity_member">活跃用户: &nbsp;&nbsp;<?php  echo $propertys['activitymember'];?>个月内消费超过2次的用户。<br/></span>
							<span class="help-block help" id="quiet_member">沉寂用户: &nbsp;&nbsp;<?php  echo $propertys['quietmember'];?>个月内没有消费的用户。<br/></span>
							<span class="help-block help" id="num">用户人数 ：<span class="" id="member_num"></span></span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span>自定义</label>
				<div class="col-sm-8 col-xs-12">
					<div class="panel panel-default tab-content">
						<div class="panel-heading">
							<ul class="nav nav-pills">
								<li class="give_member" id="group" data-member="group_member"><a href="javascript:;" style="color: gray;">根据会员组</a></li>
								<li class="give_member" id="cash_time" data-member="cash_time"><a href="javascript:;" style="color: gray;">根据消费时间</a></li>
								<li class="give_member" id="openids" data-member="openids" data-toggle="modal" data-target="#myodal"><a href="javascript:;" style="color: gray;">发放给指定粉丝</a></li>
							</ul>
						</div>
						<div class="panel-body" id="type">
							<select name="groupid" class="form-control" id="group_list" <?php  if(!$item['members']['groupid']) { ?>style="display: none;"<?php  } ?>>
							<?php  if(is_array($groups)) { foreach($groups as $group) { ?>
							<option value="<?php  echo $group['groupid'];?>"><?php  if(COUPON_TYPE == SYSTEM_COUPON) { ?><?php  echo $group['title'];?><?php  } else { ?><?php  echo $group['name'];?><?php  } ?></option>
							<?php  } } ?>
							</select>
							<div id="date" <?php  if(!$item['members']['cash_time']['start']) { ?>style="display: none;"<?php  } ?>><?php  echo tpl_form_field_daterange('daterange', array('start' => date('Y-m-d', strtotime('-1 month', time())), 'end' => date('Y-m-d', time())))?></div>
						<div id="custom_help" class="help-block help" style="display: none">用户人数: <span id="custom_person"></span></div>
					</div>
				</div>
				<input type="hidden" name="members[]" value="" id="members">
			</div>
			<?php  } else { ?>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 发放用户</label>
				<div class="col-sm-8 col-xs-12">
					<?php  if(in_array('quiet_member', $item['members'])) { ?>
					<label class="radio-inline label-success"><span class="label label-success">发放给沉寂用户</span></label>
					<?php  } else if(in_array('old_member', $item['members'])) { ?>
					<label class="radio-inline"><span class="label label-success">发放给老用户</span></label>
					<?php  } else if(in_array('new_member', $item['members'])) { ?>
					<label class="radio-inline"><span class="label label-success">发放给新用户</span></label>
					<?php  } else if(in_array('activity_member', $item['members'])) { ?>
					<label class="radio-inline"><span class="label label-success">发放给活跃用户</span></label>
					<?php  } else if(in_array('group_member', $item['members'])) { ?>
					<label class="radio-inline"><span class="label label-success">发放给 “<?php  echo $groups[$item['members']['groupid']]['title'];?>” 用户组的用户</span></label>
					<?php  } else if(in_array('cash_time', $item['members'])) { ?>
					<label class="radio-inline"><span class="label label-success">发放给在<?php  echo $item['members']['cash_time']['start'];?> - <?php  echo $item['members']['cash_time']['end'];?>期间消费的用户</span></label>
					<?php  } else if(in_array('openids', $item['members'])) { ?>
					<label class="radio-inline"><span class="label label-success">指定的粉丝</span></label>
					<?php  } ?>
				</div>
			</div>
			<?php  } ?>
		</div>
		<?php  if(COUPON_TYPE == SYSTEM_COUPON) { ?>
		<div class="form-group">
			<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span class="text-danger"></span> 活动描述</label>
			<div class="col-sm-8 col-xs-12">
				<textarea name="description"  class="form-control" cols="30" rows="3" ><?php  echo $item['description'];?></textarea>
			</div>
		</div>
		<?php  } ?>
</div>
</div>
<div class="form-group col-sm-12">
	<?php  if(!empty($id)) { ?>
	<a class="btn btn-primary" href="<?php  echo $this->createWeburl('couponmarket')?>">返回列表</a>
	<?php  } else { ?>
	<input name="submit" id="submit" type="submit" value="提交" class="btn btn-primary col-lg-1" data-dismiss="modal">
	<?php  } ?>
	<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
</div>
</form>
</div>
<script>
	window.openidnum = $('#custom_person');
	$('.help').hide();
	$('.daterange-date').blur(function() {
		$('#cash_time').trigger('click');
	});
	$('#group_list').change(function() {
		$('#group').trigger('click');
	});
	$('.give_member').click(function() {
		var type = $(this).data('member');
		var param = '';
		if (type == 'group_member' || type == 'openids' || type == 'cash_time') {
			var param = {};
			if (type == 'group_member') {
				var groupid = $('[name="groupid"]').val();
				var groupid = $('#group_list').val();
				param = {'groupid' : groupid};
			}
			if (type == 'cash_time') {
				var start = $('[name="daterange[start]"]').val();
				var end = $('[name="daterange[end]"]').val();
				param = {
					'start' : start,
					'end' : end
				}
			}
		}
		$.post("<?php  echo $this->createWeburl('couponmarket', array('op' => 'get_member_num'))?>", {'type' : type, 'param' : param}, function(data) {
			var data = $.parseJSON(data);
			$('.help').hide();
			if (param == '') {
				$('#'+type).show();
				$('#num').show();
				$('#member_num').html(data.message.message);
			} else {
				$('#custom_help').show();
				$('#custom_person').html(data.message.message);
			}
		});
		$('.give_member').attr('class', 'give_member');
		$('.give_member').children().css('color', 'gray');
		$(this).attr('class', 'give_member active');
		$(this).children().css('color', 'white');
		$('#members').val($(this).data('member'));
		var type = $(this).data('member');
		$('#date').hide();
		$('#group_list').hide();
		if (type == 'group_member') {
			$('#group_list').show();
		}
		if (type == 'cash_time') {
			$('#date').show();
		}
		if (type == 'openids') {
		}
	});

	$('#save').click(function() {
		<?php  if(COUPON_TYPE == WECHAT_COUPON) { ?>
			var coupon = $('#coupon:checked').val();
			$.post("<?php  echo $this->createWeburl('couponmarket', array('op' => 'checkcoupon'))?>", {'coupon' : coupon}, function (data) {
				var data = $.parseJSON(data);
				if (data.message.errno != 0) {
					$('#coupon_list').html('');
					util.message(data.message.message+'不可用', '', 'info');
				}
			});
		<?php  } ?>
	});
	var page = 1;
	window.openids = new Array();
	<?php  if(!empty($item['members']['openids'])) { ?>
	<?php  if(is_array($item['members']['openids'])) { foreach($item['members']['openids'] as $opid) { ?>
		<?php  if(!empty($opid)) { ?>
			openids.push('<?php  echo $opid;?>');
		<?php  } ?>
	<?php  } } ?>
	<?php  } ?>
	window.showFans= function(page, nickname) {
		nickname = nickname == undefined ? '' : nickname;
		var footer = '<button type="button" class="btn btn-primary" id="save" data-dismiss="modal">添加</button>';
		var modalobj = util.dialog('粉丝列表', ['./index.php?c=utility&a=fans&page='+ page+'&nickname='+nickname], footer, {containerName:'link-container'})
		modalobj.find('.modal-body').css({'overflow-y':'auto' });
		modalobj.modal('show');
	};
	$('#openids').click(function() {
		showFans(page);
	});
	var coupons = new Array();
	$('.member').click(function () {
		if ($(this).val() != 'group_member') {
			$('#group_list').hide();
		}
		if ($(this).val() != 'cash_time') {
			$('#date').hide();
		}
	});
	<?php  if(!empty($item['coupons']) && is_array($item['coupons'])) { ?>
	<?php  if(is_array($item['coupons'])) { foreach($item['coupons'] as $cou) { ?>
	var cid = <?php  echo $cou['id'];?>;
	var title = '<?php  echo $cou['title'];?>';
	var type = <?php  echo $cou['type'];?>;
	coupons[cid] = [{'title' : title,'type': type}];
	<?php  } } ?>
	<?php  } ?>
	$('.check').click(function() {
		var check = $(this).prop('checked');
		if (check) {
			coupons[$(this).val()] = new Array();
			coupons[$(this).val()] = [{'couponid' : $(this).val(), 'title' : $(this).data('title'), 'type' : $(this).data('type')}]
		} else {
			delete coupons[$(this).val()];
		}
	});
	$('#save').click(function() {
		$('#coupon_list').html('');
		for (var key in coupons) {
			var type = coupons[key][0].type == 1? '折扣券' : '代金券';
			$('#coupon_list').append(
					'<tr>' +
					'<td style="width: 50px">'+coupons[key][0].title+'</td>' +
					'<td style="width: 50px">'+type+'</td>' +
					'</tr>');
		}
	});
	$('form').submit(function() {
		if ($('[name="title"]').val() == '') {
			util.message('请填写活动名称', '', 'info');
			return false;
		}
		if ($('#members').val() == '') {
			util.message('请选择发放用户', '', 'info');
			return false;
		}
		if ($('.member:checked').val() == 'openids' && openids.length == 0) {
			util.message('请选择发放粉丝', '', 'info');
			return false;
		}
		if ($('.coupon:checked').val() == undefined) {
			util.message('请选择卡券', '', 'info');
			return false;
		}
	});
</script>
<?php  } ?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>