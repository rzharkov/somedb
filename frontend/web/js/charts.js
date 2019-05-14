google.charts.load('current', {'packages': ['corechart']});

function drawChart() {
    //first series
    let visible_fields_1 = [];
    let tmp = document.getElementById("chartform-visible_fields").options;
    for (i = 0; i < tmp.length; i++) {
        if (tmp[i].selected) {
            visible_fields_1.push(tmp[i].value);
        }
    }

    //second series
    let visible_fields_2 = [];
    tmp = document.getElementById("chartform-visible_fields2").options;
    for (i = 0; i < tmp.length; i++) {
        if (tmp[i].selected) {
            visible_fields_2.push(tmp[i].value);
        }
    }

    var jsonData = $.ajax({
        url: "getdata",
        data: {
            date_from: document.getElementById("chartform-date_from").value,
            date_to: document.getElementById("chartform-date_to").value,
            id_station: document.getElementById("chartform-id_station").value,
            id_measurement_interval: document.getElementById("chartform-id_measurement_interval").value,
            visible_fields: visible_fields_1.concat(visible_fields_2)
        },
        method: 'POST',
        dataType: "json",
        async: false
    }).responseText;

    jsonData = JSON.parse(jsonData);

    var data = new google.visualization.DataTable();

    for (i in jsonData.columns) {
        if ( jsonData.columns[i].role != 'tooltip' ) {
            data.addColumn(jsonData.columns[i].type, jsonData.columns[i].name);
        } else {
            data.addColumn( {type: 'string', role: 'tooltip', 'p': {'html': true}} );
        }
    }

    if (jsonData.rows.length < 2) {
        alert('Less than two rows found');
        return false;
    }

    for (i in jsonData.rows) {
        for (j in jsonData.rows[i]) {
            switch (jsonData.columns[j].type) {
                case 'date':
                    jsonData.rows[i][j] = new Date(jsonData.rows[i][j]);
                    break;
                case 'number':
                    jsonData.rows[i][j] = parseFloat(jsonData.rows[i][j]);
                    break;
                default:
            }
        }

        data.addRow(jsonData.rows[i]);
    }

    var formatter = new google.visualization.DateFormat({pattern: "yyyy-MM-dd\nHH:mm"});
    formatter.format(data, 0);

    var options = {
        //title: 'Simple graph',
        curveType: 'function',
        legend: {position: 'bottom'},
        pointSize: 2,
        width: '100%', height: '100%',
        explorer: {},
        tooltip: {isHtml: true},
        hAxis: {
            title: 'Date',
            format: 'yyyy-MM-dd\nHH:mm'
        },
        series: {
        },
        vAxes: {
            0: {
                title: 'y1',
            },
            1: {
                title: 'y2',
            }
        }
    }

    //console.log(visible_fields_1.length);
    //console.log(visible_fields_2.length);

    for ( k = 0; k < visible_fields_2.length; k++ ) {
        options.series[ visible_fields_1.length + k ] = {
            targetAxisIndex: 1,
            lineDashStyle: [4, 2],
            pointShape: 'diamond',
            pointSize: 5
        };
    }

    //console.log(options.series);

    window.chart = new google.visualization.LineChart(document.getElementById('chart_div'));

    window.chart.draw(data, options);
}

function openImageInNewWindow() {
    var imgUri = window.chart.getImageURI();
    window.open(imgUri);
}

google.charts.setOnLoadCallback(drawChart);
