$(function () {
    alert($('#data_beat').val());
    /*总体*/
    $('#zongti_graph').highcharts({
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
            data: [80, 56, 78, 65, 34],
            pointPlacement: 'on'
        }, {
            name: '您的得分',
            data: [57, 88, 59, 65, 96],
            pointPlacement: 'on'
        }]
    });

    /*花钱*/
    $('#huaqian_graph').highcharts({
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
            categories: ['1.平稳性', '2.合理性', '3.人己比', '4.规划性','5.长效性'],
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
            data: [80, 56, 78, 65, 34],
            pointPlacement: 'on'
        }, {
            name: '您的得分',
            data: [57, 88, 59, 65, 96],
            pointPlacement: 'on'
        }]
    });

    /*挣钱*/
    $('#zhengqian_graph').highcharts({
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
            categories: ['1.理念', '2.多少', '3.稳定性', '4.发展性','5.满意程度'],
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
            data: [80, 56, 78, 65, 34],
            pointPlacement: 'on'
        }, {
            name: '您的得分',
            data: [57, 88, 59, 65, 96],
            pointPlacement: 'on'
        }]
    });

    /*相对得分*/
    $('#xiangdui').highcharts({
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
            categories: ['规划性', '保险投入', '盈利性', '满意程度', '长效性', '人己比', '融资用途', '结构性', '工具技能', '合理性', '多少', '还款保障', '承受能力', '科学性', '融资观念']
        },
        credits: {
            enabled: false
        },
        series: [{
            name: '得分',
            data: [5, 3, 4, 7, 2, 5, 3, 4, 7, 2 ,-5, -3, -4, -7, -2]
        }]
    });

    /*偏向性统计*/
    $('#pianxiangxing').highcharts({
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
});