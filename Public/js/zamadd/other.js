var error = new Array();
$(function() {
	$('#tooltip1').attr('class', 'tooltip-info  prompt');
	$('#mess1').html('2到20个字符，支持英文、数字和"_"格式，不能以数字开头');
	$('#tooltip2').attr('class', 'tooltip-info  prompt invisible');
	$('#tooltip22').attr('class', 'tooltip-info  prompt invisible');
	$('#tooltip3').attr('class', 'tooltip-info  prompt invisible');

	error['username'] = 1;
	error['password'] = 1;
	error['submit'] = 1;
	error['email'] = 1;

	$('#username').focus(function() {
		$('#tooltip1').attr('class', 'tooltip-info  prompt');
		$('#mess1').html('2到20个字符，支持英文、数字和"_"格式，不能以数字开头');
	});

	$('#username').blur(
			function() {
				var username = $(this).val();
				$.post("index.php/Home/Index/checkName", {
					'username' : username
				}, function(data) {
					if (data == 0) {
						error['username'] = 0;
						$('#tooltip1').attr('class',
								'tooltip-info visible-inline success');
					} else {
						error['username'] = 1;
						$('#tooltip1').attr('class',
								'tooltip-info visible-inline error');
						$('#mess1').html(data);
					}
				})
			});
	$('#password').blur(
			function() {
				var password = $('#password').val() + "";
				var repassword = $('#repassword').val() + "";

				if (password == "") {
					error['password'] = 1;
					$('#tooltip2').attr('class',
							'tooltip-info visible-inline error');
					$('#mess2').html("密码不能为空!");
				} else if (password.length < 6) {
					error['password'] = 1;
					$('#tooltip2').attr('class',
							'tooltip-info visible-inline error');
					$('#mess2').html("密码长度必须大于6位！");
				} else {
					error['password'] = 0;
					$('#tooltip2').attr('class',
							'tooltip-info visible-inline success');
				}
			});
	$('#repassword').blur(
			function() {
				var password = $('#password').val() + "";
				var repassword = $('#repassword').val() + "";

				if (repassword == "") {
					error['repassword'] = 1;
					$('#tooltip22').attr('class',
							'tooltip-info visible-inline error');
					$('#mess22').html("确认密码不能为空!");
				} else if (repassword.length < 6) {
					error['repassword'] = 1;
					$('#tooltip22').attr('class',
							'tooltip-info visible-inline error');
					$('#mess22').html("密码长度必须大于6位！");
				} else if (password != repassword) {
					error['repassword'] = 1;
					$('#tooltip22').attr('class',
							'tooltip-info visible-inline error');
					$('#mess22').html("两次密码不一致!");
				} else {
					error['repassword'] = 0;
					$('#tooltip22').attr('class',
							'tooltip-info visible-inline success');
				}
			});

	$('#email').blur(
			function() {
				console.log("email", "blur");
				var email = $(this).val();
				$.post('index.php/Home/Index/checkName', {
					'email' : email
				}, function(data) {
					if (data == 0) {
						error['email'] = 0;
						$('#tooltip3').attr('class',
								'tooltip-info visible-inline success');
						if (error['submit'] == 0) {
							$('#submit1').click();
						}
					} else {
						error['email'] = 1;
						$('#tooltip3').attr('class',
								'tooltip-info visible-inline error');
						$('#mess3').html(data);
					}
				})
			});

	$('#submit1').click(function() {
		error['submit'] = 0;//标志用户已经点击过注册
		if ($('#username').val() == '') {
			$('#tooltip1').attr('class', 'tooltip-info visible-inline error');
			$('#mess1').html("用户名不能为空!");
		}
		if ($('#password').val() == '') {
			$('#tooltip2').attr('class', 'tooltip-info visible-inline error');
			$('#mess2').html("密码不能为空!");
		}
		if ($('#repassword').val() == '') {
			$('#tooltip22').attr('class', 'tooltip-info visible-inline error');
			$('#mess22').html("确认密码不能为空!");
		}
		if ($('#email').val() == '') {
			$('#tooltip3').attr('class', 'tooltip-info visible-inline error');
			$('#mess3').html("邮箱不能为空!");
		}

		if (error['username'] == 1) {
			var scroll_offset = $("#tooltip1").offset(); // 得到pos这个div层的offset，包含两个值，top和left
			$("body,html").animate({
				scrollTop : scroll_offset.top
			// 让body的scrollTop等于pos的top，就实现了滚动
			}, 0);
			return false;
		} else if (error['password'] == 1) {

			return false;
		} else if (error['email'] == 1) {

			return false;
		} else {
			$('#submit1').submit();
			return true;
		}
	});
});