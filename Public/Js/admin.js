$(function (){
	function getdata(){
		setTimeout(getdata,10000);
		$.ajax({
			type: "get",
			url: "./Admin/getcolumninfo",
			success: function(data){
				var dataval = new Array;
				var i ;
				dataval[0] = "this is a array";
				dataval[1] = data.zcyhs;
				dataval[2] = data.sjyhs;
				dataval[3] = data.zxyhs;
				dataval[4] = data.qtyhs;
				dataval[5] = data.yxyhs;
				dataval[6] = data.exyhs;
				dataval[7] = data.sxyhs;
				for(i=1;i<=7;i++){
					$('#datatable'+i).html(dataval[i]);
				
				}
				
				 $('#system').highcharts({
					data: {
						table: document.getElementById('datatable')
					},
					chart: {
						type: 'column'
					},
					title: {
						text: '用户数量及相关信息'
					},
					yAxis: {
						allowDecimals: false,
						title: {
							text: '图表'
						}
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.series.name +'</b><br/>'+
								this.point.y +' '+ this.point.name.toLowerCase();
						}
					}
				});
			},
			error: function(){
				console.log("error");
			}
		})
	}
	getdata();
	setTimeout(getdata,10000);
});

$(function () {
    var chart;
	var they;
	function getdataforline(){
		setTimeout(getdataforline,10000);
		$.ajax({
			type: "get",
			url: "./Admin/cleanSession",
			success: function(data){
			
				they = data;
			},
			error: function(){
				console.log("error");
			}
		});
	}
	$(document).ready(function() {
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
    
        $('#picture').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, 
                marginRight: 10,
                events: {
                    load: function() {
                        var series = this.series[0];
                        setInterval(function() {
                            var x = (new Date()).getTime(), // current time
                                y = they;
                            series.addPoint([x, y], true, true);
                        }, 1000);
                    }
                }
            },
            title: {
                text: '实时在线人数统计图'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '在线人数'
                },
                plotLines: [{
                    value: 1,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
                        Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '实时数据',
                data: (function() {
                    
                    var data = [],
                        time = (new Date()).getTime(),
                        i;
                    for (i = -19; i <= 0; i++) {
                        data.push({
                            x: time + i * 1000,
                            y: they
                        });
                    }
                    return data;
                })()
            }]
        });
    });
	getdataforline();
	setTimeout(getdataforline,10000);
});