google.charts.load('current', {'packages': ['corechart']});

function drawChart() {
	var jsonData = $.ajax({
		url: "getdata",
		data: {
			date_from: document.getElementById("chartform-date_from").value,
			date_to: document.getElementById("chartform-date_to").value,
			id_uploading: document.getElementById("chartform-id_uploading").value,
		},
		method: 'POST',
		dataType: "json",
		async: false
	}).responseText;

	var options = {
		title: 'Simple graph',
		curveType: 'function',
		legend: {position: 'bottom'},
		pointSize: 5,
		width: '100%', height: '100%',
		hAxis: {
			title: 'Date',
			format: 'yyyy-MM-dd\nHH:mm'
		}
	}

	jsonData = JSON.parse(jsonData);

	var data = new google.visualization.DataTable();

	for (i in jsonData.columns) {
		data.addColumn(jsonData.columns[i].type, jsonData.columns[i].name);
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

	var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
	chart.draw(data, options);
}

google.charts.setOnLoadCallback(drawChart);
