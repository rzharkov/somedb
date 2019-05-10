google.charts.load('current', {'packages': ['corechart']});

function drawChart() {
	/*    var data = google.visualization.arrayToDataTable([
					['Country', 'Popularity'],
					['Sweden', 300],
					['United States', 300],
					['France', 400],
					['Canada', 500],
					['Spain', 500],
					['China', 1500],
					['RU', 900]
			]);
	;*/

	var jsonData = $.ajax({
		url: "getdata",
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
		data.addColumn( jsonData.columns[i].type, jsonData.columns[i].name );
	}

	for (i in jsonData.rows) {
		for ( j in jsonData.rows[i] ) {
			switch (jsonData.columns[j].type) {
				case 'date':
					jsonData.rows[i][j] = new Date(jsonData.rows[i][j]);
					break;
				case 'number':
					jsonData.rows[i][j] = parseFloat( jsonData.rows[i][j] );
					break;
				default:
			}
		}
		data.addRow( jsonData.rows[i] );
	}

	/*data.addColumn('date', 'Col1');
	data.addColumn('number', 'Col2');
	data.addColumn('number', 'Col3');*/

	/*data.addRow([new Date(2018, 6, 20, 12, 44, 12), 42, 43]);
	data.addRow([new Date(2018, 6, 20, 13, 40, 22), 52, 73]);
	data.addRow([new Date(2018, 6, 20, 14, 37, 32), 62, 83]);
	data.addRow([new Date(2018, 6, 20, 15, 35, 12), 52, 73]);*/


	var formatter = new google.visualization.DateFormat({pattern: "yyyy-MM-dd\nHH:mm"});
	formatter.format(data, 0);


	var chart = new google.visualization.LineChart(document.getElementById('test_div'));
	chart.draw(data, options);
}

google.charts.setOnLoadCallback(drawChart);
