google.charts.load('current', {'packages': ['corechart']});

function drawChart() {
    let a = [];
    let tmp = document.getElementById("chartform-visible_fields").options;
    for (i = 0; i < tmp.length; i++ ) {
        if (tmp[i].selected) {
            a.push( tmp[i].value );
        }
    }

    var jsonData = $.ajax({
        url: "getdata",
        data: {
            date_from: document.getElementById("chartform-date_from").value,
            date_to: document.getElementById("chartform-date_to").value,
            id_station: document.getElementById("chartform-id_station").value,
            id_measurement_interval: document.getElementById("chartform-id_measurement_interval").value,
            visible_fields: a
        },
        method: 'POST',
        dataType: "json",
        async: false
    }).responseText;

    var options = {
        //title: 'Simple graph',
        curveType: 'function',
        legend: {position: 'bottom'},
        pointSize: 2,
        width: '100%', height: '100%',
        explorer: {},
        hAxis: {
            title: 'Date',
            format: 'yyyy-MM-dd\nHH:mm'
        },
        series: {
            3: {
                targetAxisIndex: 1,
                lineWidth: 1,
                lineDashStyle: [4, 2],
                pointShape: 'diamond',
                pointSize: 5
            },
            4: {
                targetAxisIndex: 1,
                lineDashStyle: [4, 2],
                pointShape: 'diamond',
                pointSize: 5
            },
            5: {
                targetAxisIndex: 1,
                lineDashStyle: [4, 2],
                pointShape: 'diamond',
                pointSize: 5
            }
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

    jsonData = JSON.parse(jsonData);

    var data = new google.visualization.DataTable();

    for (i in jsonData.columns) {
        data.addColumn(jsonData.columns[i].type, jsonData.columns[i].name);
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

    window.chart = new google.visualization.LineChart(document.getElementById('chart_div'));

    window.chart.draw(data, options);
}

function openImageInNewWindow() {
    var imgUri = window.chart.getImageURI();
    window.open(imgUri);
}

google.charts.setOnLoadCallback(drawChart);
