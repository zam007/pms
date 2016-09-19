$(function(){
  $('.btn-login').click(function(){
    var userName=$("#reg-username").val();
    var userPassword=$("#user_password").val();
    if(userPassword==""||userName==""){
        alert('登录名与密码不能为空 ');
        $("#reg-username").focus();
        return false;
    }else{
        var url = "{:U('Index/register_1')}";
        $.post(url, { reg-username:userName, user_password:userPassword}, function(msg){
        if(msg.info == 'ok') {
          //alert('登录成功，正在转向后台主页！');
          window.location.href = msg.callback;
        } else {
          alert(msg.info);
        }
      }, 'json').error(function(){
        alert("网络连接错误，请稍后再试");
      });

    }
  })

  //enter回车登录
  $('input').bind('keypress',function(event){
        if(event.keyCode == "13")
        {
            $('.btn-login').trigger('click');
        }
  });

});