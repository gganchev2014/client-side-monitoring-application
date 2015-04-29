function updateTable()
{
	$.ajax({
		type: "POST",
		url: "phpDataAccess.php",
		data:
			{
				"action": "getServerList"
			},
		cache: false,
		success: function (result) {
			var response = $.parseJSON(result);
			// Check the status
			if(response)
			{
				if (response.length > 0) {
					$('#tablediv').empty().html(response);
				}
			}
		}
	});
}

// On DOM ready
$(document).ready(function() {
	updateTable();
	// Prompt the updateTable function every 10 seconds
	setInterval(updateTable, 10000);
	
	// Create Button Handlers from classes as they will not be on the DOM.
	// Viewing server History
	$('body').on('click', 'input.viewHistory', function(e) {		
		// Show the loading panel
		
		// Get the server values from the button
		var serverVals = $(this).val().split(";");
		var serverID = serverVals[0];
		
		if(serverID)
		{
			Utils.DOM.showLoadingPanel($('#serverHistoryResults'));
			// Set the labels
			$('#serverHistoryName').text(serverVals[1]);
			$('#serverHistoryAddress').text(serverVals[2]);
			var getServerHistoryFunc = function() {
				$.ajax({
					type: "POST",
					url: "phpDataAccess.php",
					data:
						{
							"action": "getServerHistory",
							"serverID": serverID
						},
					cache: false,
					success: function (result) {
						var response = $.parseJSON(result);
						// Check the status
						if(response)
						{
							if (response.length > 0) {
								$('#serverHistoryResults').empty().html(response).append(Utils.DOM.pagerHtml.replace('[pagerID]','serverHistoryPager'));
								Utils.DOM.hideLoadingPanel($('#serverHistoryResults'));
								$('#serverHistoryResults > table').tablesorter(
								{
					                headers: {
					                    0: { sorter: false }

					                },
					                widgets: ['pager']
					            }).tablesorterPager(
					                { 
					                    // output default: '{page}/{totalPages}'
					                    // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
					                    // also {page:input} & {startRow:input} will add a modifiable input in place of the value
					                    output: 'Page {page:input} of {totalPages}', // '{page}/{totalPages}'
					                    // apply disabled classname to the pager arrows when the rows at either extreme is visible
					                    updateArrows: true,
					                    // starting page of the pager (zero based index)
					                    startPage: 0,
					                    // Number of visible rows
					                    size: 10,
					                    // The ID of the pager
					                    container: $("#serverHistoryPager"),
					                    removeRows: false
						            });
					            Utils.DOM.removeTextOverlay($('#serverHistoryResults'));
							}
							else // No Results
							{
								$('#serverHistoryResults').empty();
								Utils.DOM.displayTextOverlay($('#serverHistoryResults'), "There are no monitoring results for this server");
								Utils.DOM.hideLoadingPanel($('#serverHistoryResults'));
							}
						}
						else // No Results
						{
							$('#serverHistoryResults').empty();
							Utils.DOM.displayTextOverlay($('#serverHistoryResults'), "There are no monitoring results for this server");
							Utils.DOM.hideLoadingPanel($('#serverHistoryResults'));
						}
					}
				});
			};
			Utils.DOM.createModal($('#serverHistoryModal'),$(this), console.log('modal opened for server: ' + serverID), getServerHistoryFunc, true); 
		}
	});
	// Viewing server Errors
	$('body').on('click', 'input.viewErrors', function(e) {		
		// Show the loading panel
		
		// Get the server values from the button
		var serverVals = $(this).val().split(";");
		var serverID = serverVals[0];
		
		if(serverID)
		{
			Utils.DOM.showLoadingPanel($('#serverErrorResults'));
			// Set the labels
			$('#serverErrorName').text(serverVals[1]);
			$('#serverErrorAddress').text(serverVals[2]);
			var getServerErrorFunc = function() {
				$.ajax({
					type: "POST",
					url: "phpDataAccess.php",
					data:
						{
							"action": "getServerErrors",
							"serverID": serverID
						},
					cache: false,
					success: function (result) {
						var response = $.parseJSON(result);
						// Check the status
						if(response)
						{
							if (response.length > 0) {
								$('#serverErrorResults').empty().html(response).append(Utils.DOM.pagerHtml.replace('[pagerID]','serverErrorPager'));
								Utils.DOM.hideLoadingPanel($('#serverErrorResults'));
								$('#serverErrorResults > table').tablesorter(
								{
					                headers: {
					                    0: { sorter: false }

					                },
					                widgets: ['pager']
					            }).tablesorterPager(
					                { 
					                    // output default: '{page}/{totalPages}'
					                    // possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
					                    // also {page:input} & {startRow:input} will add a modifiable input in place of the value
					                    output: 'Page {page:input} of {totalPages}', // '{page}/{totalPages}'
					                    // apply disabled classname to the pager arrows when the rows at either extreme is visible
					                    updateArrows: true,
					                    // starting page of the pager (zero based index)
					                    startPage: 0,
					                    // Number of visible rows
					                    size: 10,
					                    // The ID of the pager
					                    container: $("#serverErrorPager"),
					                    removeRows: false
						            });
					            Utils.DOM.removeTextOverlay($('#serverErrorResults'));
							}
							else // No Results
							{
								$('#serverErrorResults').empty();
								Utils.DOM.displayTextOverlay($('#serverErrorResults'), "There are no errors logged for this server");
								Utils.DOM.hideLoadingPanel($('#serverErrorResults'));
							}
						}
						else // No Results
						{
							$('#serverErrorResults').empty();
							Utils.DOM.displayTextOverlay($('#serverErrorResults'), "There are no errors logged for this server");
							Utils.DOM.hideLoadingPanel($('#serverErrorResults'));
						}
					}
				});
			};
			Utils.DOM.createModal($('#serverErrorModal'),$(this), console.log('error log opened for server: ' + serverID), getServerErrorFunc, true); 
		}
	});
});	

					