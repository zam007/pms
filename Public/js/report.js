$(function () {
    var examId = window.location.href.substr(51); 
    $.ajax({
         url: "/Home/Exam/ajaxExam",  
         type: "POST",
         data:{exam_id:examId},
         //dataType: "json",
         error: function(){  
             alert('Error loading XML document');  
         },  
         success: function(msg){//如果调用php成功 
            var json=eval('('+msg+')');
            var data = json['classifys'];
            var relative = json['relative'];
            var inclination = json['inclination'];
            var totle = new Array();
            var avg = new Array();                       
            var incl = new Array();
            /*总体*/
            for (x in data){
                i = x - 1;
                totle[i] = data[x]['total_score'];
                avg[i] = data[x]['avg_score'];
            }
            for(x in inclination){
                i = x - 1;
                incl[i] = ["'"+inclination[x]['inclination']+"'",inclination[x]['count']];
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

            //循环生成图表
            $.each(data,function(index,item){
                var 
                    _graphItem          = item.classify,
                    Array_Score_avg     = [],
                    Array_Score_totle   = [],
                    Array_graphItemList = [];

                $.each(_graphItem,function(index,item){
                    Array_Score_avg.push(item.avg_score);
                    Array_Score_totle.push(item.total_score);
                    Array_graphItemList.push(item.name);
                });

                $('#'+item.en+'_graph').highcharts({
                    credits:{
                        enabled:false // 禁用版权信息
                    },
                    chart: {
                        polar: true,
                        type: 'line'
                    },
                    title: {
                        text: item.classify_name + '得分比例',
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
                        categories: Array_graphItemList,
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
                        data: Array_Score_avg,
                        pointPlacement: 'on'
                    }, {
                        name: '您的得分',
                        data: Array_Score_totle,
                        pointPlacement: 'on'
                    }]
                });
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
                    data: incl,
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