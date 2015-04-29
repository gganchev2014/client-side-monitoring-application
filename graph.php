		
<?php
	include("phpDataAccess.php");
?>
<div id="chart1">
    <svg style="height: 250px;"></svg>
</div>
			<script type="text/javascript" language="javascript">
		
			function updateGraph(){
			$.ajax({
				type: "POST",
				url: "phpDataAccess.php",
				data:
			{
				"action": "getServerObjects"
			},
				cache: false,

				//if request successful
				success: function (result) {
					var response = $.parseJSON(result);


					if(response.length > 0){

						console.log(response);

						//chart object
						var values = [];
						//loops through the json object
						$.each(response, function(index, value){

							console.log(index);
							console.log(value.Name);
							console.log(value.Latency);

							var name = value.Name;
							var latency = parseInt(value.Latency);
							
							//Puts data in chart object
							values.push({"label": name, "value" : latency});						   	 
						})	

						//builds chart object
						var barChartData = [
						{
							"key": "Latency",
							"values": values 
						}];
						
						console.log(barChartData);

						//creates graph
						nv.addGraph(function() {
						        var chart = nv.models.discreteBarChart()
						            .x(function(d) { return d.label })
						            .y(function(d) { return d.value })
						            .staggerLabels(true)
						            //.staggerLabels(historicalBarChart[0].values.length > 8)
						            .tooltips(true)
						            .showValues(true)
						            .duration(250);

						        d3.select('#chart1 svg')
						            .datum(barChartData)
						            .call(chart);

						        nv.utils.windowResize(chart.update);
						        return chart;
			    			});	
					}
				}
			});
		}

		updateGraph();
		setInterval(updateGraph, 10000);


			</script>
			<div id="test"></div>