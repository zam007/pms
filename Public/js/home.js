var Home = {
	init: function() {
		this.bind();
	},
	bind: function() {
		var _self = this;
		$('[bind="show_modal_login"]').on('click',function() {_self.showLoginModal();});
		$('[bind="show_modal_register"]').on('click',function() {_self.showRegisterModal();});
		$('[bind="hide_modal"]').on('click',function(e) {_self.hideModal(e.target);});
	},
	showLoginModal: function() {
		$('.login-modal .mask').css({
			'background':'rgba(0,0,0,.5)',
			'z-index':'1'
		});
	},
	showRegisterModal: function() {
		$('.register-modal .mask').css({
			'background':'rgba(0,0,0,.5)',
			'z-index':'1'
		});
	},
	hideModal: function(target) {
		if ($(target).attr('bind') == 'hide_modal') {
			$('.modal .mask').css({
				'background':'#fff',
				'z-index':'-1'
			});
		}
	}
};

Home.init();
