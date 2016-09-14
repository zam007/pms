var InfoConfirm = {
    init: function() {
        this.bind();
    },
    bind: function() {
        var _self = this;
        $('[bind="show_modal"]').on('click',function() {_self.showModal();});
        $('[bind="hide_modal"]').on('click',function() {_self.hideModal();});
    },
    showModal: function() {
        $('.modal .mask').css({
            'background':'rgba(0,0,0,.5)',
            'z-index':'1'
        });
    },
    hideModal: function() {
        $('.modal .mask').css({
                'background':'#fff',
                'z-index':'-1'
        });
    },
  checkAnswer: function () {
    $('.content-main-form-info').on('click','li',function () {
      var radio = $(this).find('input');
      radio.prop('checked',true);
	  alert(1);
    });
  },
};

InfoConfirm.init();
