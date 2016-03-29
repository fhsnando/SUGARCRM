({
    plugins: ['Dashlet'],
    events: {
        'click #buttonFilterA' : 'filterA',
        'click #buttonFilterR' : 'filterR',
        'click #buttonFilterU' : 'filterU',
        'change #selectPeriodoA' : 'filterA',
        'change #selectPeriodoR' : 'filterR',
        'change #selectPeriodoU' : 'filterU',
        'change #selectRegionalU' : 'filterU'
    },

    initDashlet : function() {

    },

    filterA: function(){
        var selectGroupData="A";
        var period=$('#selectPeriodo'+selectGroupData).val()!="" ? $('#selectPeriodo'+selectGroupData).val():"not";
        var host = "ChartFilter/"+selectGroupData+"/not/"+period;
        this.call(host);
    },
    filterR: function(){
        var selectGroupData="R";
        var period=$('#selectPeriodo'+selectGroupData).val()!="" ? $('#selectPeriodo'+selectGroupData).val():"not";
        var host = "ChartFilter/"+selectGroupData+"/not/"+period;
        this.call(host);
    },
    filterU: function(){
        var selectGroupData="U";
        var region=$('#selectRegional'+selectGroupData).val()!="" ? $('#selectRegional'+selectGroupData).val():"not";
        var period=$('#selectPeriodo'+selectGroupData).val()!="" ? $('#selectPeriodo'+selectGroupData).val():"not";
        var host = "ChartFilter/"+selectGroupData+"/"+region+"/"+period;
        this.call(host);
    },

    call: function(host){
        app.api.call('GET', app.api.buildURL(host), null, {
            success : function(data) {
                //console.log(data);
                var data2 = jQuery.parseJSON(data);
                that.chart(data2);
            },
            error : function(error) {
                console.error('Erro:' + error);
            }
        });
    },

    loadData: function (options) {

        if (_.isUndefined(this.model)) {
            return;
        }
        var host = "ChartData/R";
        that = this;
        app.api.call('GET', app.api.buildURL(host), null, {
            success : function(data) {
                //console.log(data);
                var data2 = jQuery.parseJSON(data);
                that.chart(data2);
                that.fieldsData(data2);

            },
            error : function(error) {
                console.error('Erro:' + error);
            }
        });

        var host = "ChartData/U";
        this.call(host);

        var host = "ChartData/A";
        this.call(host);


    },
    fieldsData:function(data){
        var c=0;
        data.Regionais.forEach(function(entry) {
            $("#selectRegionalU").append('<option value="'+entry+'">'+entry+'</option>');
            c++;
        });
    },
    chart:function(chart){
        //console.log(chart);
        $(chart['container']).highcharts({
            title: {
                text: chart['title']
            },
            xAxis: {
                categories: chart['xAxis']
            },
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Atendimentos',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Percentual de retenção',
                    style: {
                        color: Highcharts.getOptions().colors[3]
                    }
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: Highcharts.getOptions().colors[3]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                align: 'right',
                x: -100,
                verticalAlign: 'top',
                y: 25,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black'
                        }
                    }
                }
            },
            series: chart['series']
        });
    }
})