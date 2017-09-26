<?php include 'common/header.php';?>
<div class="container">
	<?php include 'common/sidebar_house.php';?>
	<div class="frame">
		<form action="house_list.php" class="form">
			<div class="mb20">
				<div class="label">房屋类型：</div>
				<select name="" id="" class="select">
					<option value="">普通房</option>
					<option value="" selected="selected">大床房</option>
				</select>
			</div>
			<div class="mb20">
				<div class="label">房间号：</div>
				<input type="text" class="input">
			</div>
			<div class="mb20">
				<div class="label">是否锁房：</div>
				<select name="" id="" class="select">
					<option value="">锁房</option>
					<option value="" selected="selected">不锁</option>
				</select>
			</div>
			<div class="mb20">
				<div class="label">房间说明：</div>
				<textarea name="" id="" cols="30" rows="10" class="textarea"></textarea>
			</div>
			<a class="btn" href="javascript:history.go(-1)">取消</a>
			<button class="btn" type="submit">提交后退出</button>
			<button class="btn" type="reset">提交并新增</button>
		</form>
	</div>
</div>
</div>
<?php include 'common/footer.php';?>