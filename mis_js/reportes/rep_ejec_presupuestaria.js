$(function () {
    var mes = document.getElementById("mes").value;
    var gestion = document.getElementById("gestion").value;
    var ejec_prog = parseInt(document.getElementById("ejec_prog").value);//ejecutado respecto a lo programado
    Highcharts.setOptions({
        lang: {
            contextButtonTitle: 'Opciones',
            printChart: 'Imprimir Tacometro',
            downloadJPEG: 'Descargar en JPEG',
            downloadPDF: 'Descargar en PDF',
            downloadPNG: 'Descargar en PNG',
            downloadSVG: 'Descargar en SVG',
            loading: 'loading....'
        }
    });
    Highcharts.chart('graf_tacometro', {
            chart: {
                type: 'gauge',
                plotBackgroundColor: null,
                plotBackgroundImage: null,
                plotBorderWidth: 0,
                plotShadow: false
            },

            title: {
                text: '<b>EJECUCIÓN PRESUPUESTARIA A NIVEL INSTITUCIONAL AL MES DE  ' + mes + ' GESTIÓN ' + gestion+'</b>'
            },

            pane: {
                startAngle: -120,
                endAngle: 120,
                background: [{
                    backgroundColor: {
                        linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                        stops: [
                            [0, '#FFF'],
                            [1, '#333']
                        ]
                    },
                    borderWidth: 0,
                    outerRadius: '109%'
                }, {
                    backgroundColor: {
                        linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                        stops: [
                            [0, '#333'],
                            [1, '#FFF']
                        ]
                    },
                    borderWidth: 1,
                    outerRadius: '107%'
                }, {
                    // default background
                }, {
                    backgroundColor: '#DDD',
                    borderWidth: 0,
                    outerRadius: '105%',
                    innerRadius: '103%'
                }]
            },

            credits: {
                enabled: true,
                href: "#",
                position: {
                    align: 'center',
                    x: -3,
                    verticalAlign: 'bottom',
                    y: -115
                },
                style: {
                    color: '#ffff0',
                    fontSize: '12px'
                },
                text: '<br><strong>EJECUCIÓN A ' + mes + '<br> <strong>RESPECTO A LO PROGRAMADO</strong> </strong><br><strong>'+ejec_prog+'%</strong>'
            },
            // the value axis
            yAxis: {
                min: 0,
                max: 100,

                minorTickInterval: 'auto',
                minorTickWidth: 1,
                minorTickLength: 10,
                minorTickPosition: 'inside',
                minorTickColor: '#000000',

                tickPixelInterval: 30,
                tickWidth: 2,
                tickPosition: 'inside',
                tickLength: 10,
                tickColor: '#000000',
                labels: {
                    step: 2,
                    rotation: 'auto'
                },
                title: {
                    text: 'Procentaje [%]'
                },
                plotBands: [{
                    from: 0,
                    to: 30,
                    color: '#FF0000' // rojo
                }, {
                    from: 30,
                    to: 50,
                    color: '#FF9900' // naranja
                }, {
                    from: 50,
                    to: 65,
                    color: '#FFFF00' // amarillo
                }, {
                    from: 65,
                    to: 79,
                    color: '#006400' // verde
                }, {
                    from: 79,
                    to: 90,
                    color: '#00CCFF' // celeste
                }, {
                    from: 90,
                    to: 100,
                    color: '#0000CC' // celeste
                }


                ]
            },

            series: [{
                name: 'Ejecución a ' + mes + ' respecto a lo programado',
                data: [ejec_prog],
                tooltip: {
                    valueSuffix: '[%]'
                }
            }]

        }
    );


});