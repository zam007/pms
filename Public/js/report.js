$(function () {
    $.ajax({
         url: "/Home/Exam/ajaxExam",  
         type: "POST",
         data:{answer_sheet_id:113},
         //dataType: "json",
         error: function(){  
             alert('Error loading XML document');  
         },  
         success: function(msg){//如果调用php成功 
         console.log(msg);
            var json=eval('('+msg+')');
            var data = json['classifys'];
            var relative = json['relative'];
            
            /*总体*/
            for(var i = 1; i <= data.length;i++){
                if(i == 1){
                    var totle = new Array(data[i]['total_score']);
                    var avg = new Array(data[i]['avg_score']);
                }else{
                    totle.concact(data[i]['total_score']);
                    avg.concact(data[i]['avg_score']);
                }
            }
            $('#zongti_graph').highcharts({
                credits:{
                    enabled:false // 禁用版权信息
                },
                chart: {
                    polar: true,
                    type: 'line'
                },
                title: {
                    text: '总体得分比例',
                    x: -65,
                    style: {
                        color: '#333',
                        fontWeight: 'bold',
                        fontSize: '18px'
                    }
                },
                pane: {
                    size: '80%'
                },
                xAxis: {
                    categories: ['1.投钱', '2.挣钱', '3.保钱', '4.花钱','5.借钱'],
                    tickmarkPlacement: 'on',
                    lineWidth: 0
                },
                yAxis: {
                    gridLineInterpolation: 'polygon',
                    lineWidth: 0,
                    min: 0
                },
                tooltip: {
                    shared: true,
                    pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
                },
                legend: {
                    align: 'right',
                    verticalAlign: 'top',
                    y: 70,
                    layout: 'vertical'
                },
                series: [{
                    name: '年龄组平均分',
                    data: avg,
                    pointPlacement: 'on'
                }, {
                    name: '您的得分',
                    data: totle,
                    pointPlacement: 'on'
                }]
            });

            /*花钱*/
            var huaqian = data[4]['classify'];
            if(typeof(huaqian) == "undefined"){
                var long = 0;
            }else{
                var long = huaqian.length;
            }
            for(var i = 0; i < long;i++){
                var n = i+1;
                if(i == 0){
                    var hua_name = new Array(n+"."+huaqian[i]['name']);
                    var avg = new Array(huaqian[i]['avg_score']);
                    var totle = new Array(huaqian[i]['total_score']);
                }else{
                    hua_name.concact(n+"."+huaqian[i]['name']);
                    totle.concat(huaqian[i]['total_score']);
                    avg.concat(huaqian[i]['avg_score']);
                }
            }
            $('#huaqian_graph').highcharts({
                credits:{
                    enabled:false // 禁用版权信息
                },
                chart: {
                    polar: true,
                    type: 'line'
                },
                title: {
                    text: '花钱得分比例',
                    x: -65,
                    style: {
                        color: '#333',
                        fontWeight: 'bold',
                        fontSize: '18px'
                    }
                },
                pane: {
                    size: '80%'
                },
                xAxis: {
                    categories: hua_name,
                    tickmarkPlacement: 'on',
                    lineWidth: 0
                },
                yAxis: {
                    gridLineInterpolation: 'polygon',
                    lineWidth: 0,
                    min: 0
                },
                tooltip: {
                    shared: true,
                    pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
                },
                legend: {
                    align: 'right',
                    verticalAlign: 'top',
                    y: 70,
                    layout: 'vertical'
                },
                series: [{
                    name: '年龄组平均分',
                    data: avg,
                    pointPlacement: 'on'
                }, {
                    name: '您的得分',
                    data: totle,
                    pointPlacement: 'on'
                }]
            });

            /*挣钱*/
            var huaqian = data[2]['classify'];
            if(typeof(huaqian) == "undefined"){
                var long = 0;
            }else{
                var long = huaqian.length;
            }
            for(var i = 0; i < long;i++){
                var n = i+1;
                if(i == 0){
                    var hua_name = new Array(n+"."+huaqian[i]['name']);
                    var avg = new Array(huaqian[i]['avg_score']);
                    var totle = new Array(huaqian[i]['total_score']);
                }else{
                    hua_name.concat(n+"."+huaqian[i]['name']);
                    totle.concat(data[i]['total_score']);
                    avg.concat(data[i]['avg_score']);
                }
            }
            $('#zhengqian_graph').highcharts({
                credits:{
                    enabled:false // 禁用版权信息
                },
                chart: {
                    polar: true,
                    type: 'line'
                },
                title: {
                    text: '挣钱得分比例',
                    x: -65,
                    style: {
                        color: '#333',
                        fontWeight: 'bold',
                        fontSize: '18px'
                    }
                },
                pane: {
                    size: '80%'
                },
                xAxis: {
                    categories: hua_name,
                    tickmarkPlacement: 'on',
                    lineWidth: 0
                },
                yAxis: {
                    gridLineInterpolation: 'polygon',
                    lineWidth: 0,
                    min: 0
                },
                tooltip: {
                    shared: true,
                    pointFormat: '<span style="color:{series.color}">{series.name}: <b>${point.y:,.0f}</b><br/>'
                },
                legend: {
                    align: 'right',
                    verticalAlign: 'top',
                    y: 70,
                    layout: 'vertical'
                },
                series: [{
                    name: '年龄组平均分',
                    data: avg,
                    pointPlacement: 'on'
                }, {
                    name: '您的得分',
                    data: totle,
                    pointPlacement: 'on'
                }]
            });
            if(typeof(relative) == "undefined"){
                var long = 0;
            }else{
                var long = relative.length;
            }
            var namearr;
            var scorearr ;
            for(var i = 0; i < long;i++){
                
                if(i == 0){
                     namearr = new Array(relative[i]['name']);
                     scorearr = new Array(relative[i]['score']);
                }else{
                    namearr.concat(relative[i]['name']);
                    scorearr.concat(relative[i]['score']);
                }
            }
            
            /*相对得分*/
            $('#xiangdui').highcharts({
                credits:{
                    enabled:false // 禁用版权信息
                },
                chart: {
                    type: 'column'
                },
                title: {
                    text: null
                },
                pane: {
                    size: '100%'
                },
                xAxis: {
                    categories: namearr,
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: '得分',
                    data: scorearr,
                }]
            });

            /*偏向性统计*/
            $('#pianxiangxing').highcharts({
                credits:{
                    enabled:false // 禁用版权信息
                },
                chart: {
                    type: 'column'
                },
                title: {
                    text: null
                },
                pane: {
                    size: '100%'
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                legend: {
                    enabled: false
                },
                series: [{
                    name: '得分',
                    data: [
                        ['观察力强', 23.7],
                        ['善良', 16.1],
                        ['目标明确', 14.2],
                        ['自我中心', 14.0],
                        ['正直', 12.5],
                        ['太理想化', 12.1],
                        ['自私自利', 11.8],
                        ['表达能力好', 11.7],
                        ['勤奋', 11.1],
                        ['自我主义', 11.1],
                        ['亲和力强', 10.5],
                        ['反应敏捷', 10.4],
                        ['有魄力', 10.0],
                        ['有创意', 9.3],
                        ['思考能力强', 9.3],
                        ['莽撞草率', 9.0]
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
            });
         }
     });
    
});