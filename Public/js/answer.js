var Answer = {
  init: function () {
    this.bind();
  },
  bind: function () {
    this.timer();
    this.checkAnswer();
    this.hideAnswer();
  },
  countdown: function (TIME) {
    var hh = parseInt(TIME / 1000 / 60 / 60 % 24, 10);//计算剩余的小时数
    var mm = parseInt(TIME / 1000 / 60 % 60, 10);//计算剩余的分钟数
    var ss = parseInt(TIME / 1000 % 60, 10);//计算剩余的秒数
    hh = this.checkTime(hh);
    mm = this.checkTime(mm);
    ss = this.checkTime(ss);
    document.getElementById("timer").innerHTML = hh + ":" + mm + ":" + ss ;
  },
  timer: function () {
    var self = this;
    var time = 300; //设置倒计时的时间，单位为秒
    setInterval(function () {
      time = time - 1;
      if (time>0) {
        self.countdown(time * 1000);
      }else {
        self.submitForm();
      }
    },1000);
  },
  checkTime: function (i) {
    if ( i < 10 ) {
      i = '0' + i;
    }
    return i;
  },
  submitForm: function () {
    $('form').submit();
  },
  checkAnswer: function () {
    $('.options-list').on('click','li',function () {
      var radio = $(this).find('input');
      radio.prop('checked',true);
      $("form").submit();
    });
  },
  nextQuestion: function () {
    var self = this;
    $('.next').on('click',function () {
      self.submitForm();
    })
  },
  hideAnswer: function () {
    var self = this,
          video = $('video').length,
          form = $('form'),
          checkOptions = $('.check-options');
    if (video) {
      form.hide();
      checkOptions.show();
      self.showOptions();
    }
  },
  showOptions: function () {
    var self = this,
          checkOptions = $('.check-options'),
          form = $('form'),
          video = $('video');
    checkOptions.hover(function () {
      video.parent().hide();
      video[0].pause();
      form.show();
      checkOptions.hide();
      self.hideOptions();
    });
  },
  hideOptions: function () {
    var form = $('form'),
          checkOptions = $('.check-options'),
          video = $('video');
    form.hover(function () {
      form.show();
    },function () {
      form.hide();
      video.parent().show();
      var videoCurrentTime = video[0].currentTime;
      if (videoCurrentTime) {
        video[0].play();
      }
      checkOptions.show();
    })
  }
}

Answer.init();
