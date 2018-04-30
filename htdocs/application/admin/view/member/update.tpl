<extend name="Public:Base" />

<block name="body">
<include file="Public/bread" menu="member_log" section="会员" title="会员管理" />

<div id="page-wrapper">
	<div class="page-header">修改会员</div>
	<div id="page-content">
<form action="{:url('manager/update')}" method="post">
	<div class="form-group">
		<label>用户名</label>
		<input class="form-control" type="text" name="username" value="{$model.username}">
	</div>
	<div class="form-group">
		<label>真实姓名</label>
		<input class="form-control" type="text" name="realname" value="{$model.realname}" />
	</div>
	<div class="form-group">
		<label>邮箱</label>
		<input class="form-control" type="text" name="email" value="{$model.email}">
	</div>
	<div class="form-group">
		<label>新密码</label>
		<input class="form-control" type="password" name="password" placeholder="不填写则不更改">
	</div>
	<div class="form-group">
        <label>用户类型</label>
        <label class="radio-inline">
          <input type="radio" name="type" id="type" value="1" <if condition="$model.type eq 1">checked="checked"</if>>普通会员
        </label>
        <label class="radio-inline">
          <input type="radio" name="type" id="type" value="2" <if condition="$model.type eq 2">checked="checked"</if>>VIP
        </label>
    </div>
	<div class="form-group">
        <label>用户状态</label>
        <label class="radio-inline">
          <input type="radio" name="status" id="status" value="0" <if condition="$model.status eq 0">checked="checked"</if>>禁止登陆
        </label>
        <label class="radio-inline">
          <input type="radio" name="status" id="status" value="1" <if condition="$model.status eq 1">checked="checked"</if>>正常
        </label>
    </div>
	<div class="form-group">
		<input type="hidden" name="id" value="{$model.id}">
		<button class="btn btn-primary" type="submit" >更新</button>
	</div>


</form>
		</div>
</div>
</block>