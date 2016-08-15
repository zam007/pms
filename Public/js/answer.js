var Answer = {
  init: function () {
    this.bind();
  },
  bind: function () {
    this.timer();
    this.checkAnswer();
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
    var time = 10; //设置倒计时的时间，单位为秒
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
    });
  },
  nextQuestion: function () {
    var self = this;
    $('.next').on('click',function () {
      self.submitForm();
    })
  }
}

Answer.init();
