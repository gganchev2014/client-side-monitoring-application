var Utils = 
    {
        DOM: 
        {
            // Sets an element to an invalid style
            setInvalid : function (element) {
                $(element).addClass("invalid");
            },
            // Sets an element to an valid style
            setValid: function (element) {
                $(element).removeClass("invalid"); 
            },
            // Overlays an element with a loading panel
            showLoadingPanel: function (element, callback) {
                // Check if the element has a loading panel added already
                if ($(element).has(".loadingPanel").length > 0) {
                    return;
                }
                if (!this.loadingHtml) {
                    var i = 0;
                    this.loadingHtml = [];
                    this.loadingHtml[i++] = "<div class=\"popUpForm loadingPanel\">";
                    this.loadingHtml[i++] = "            <table>";
                    this.loadingHtml[i++] = "                <tr>";
                    this.loadingHtml[i++] = "                    <td style='text-align: center;'>";
                    //loadingHtml[i++] =  "                        <span class=\"loadingText\">Working on it<\/span>";
                    //loadingHtml[i++] =  "                        <br \/>";
                    this.loadingHtml[i++] = "                        <img src=\"\/PMT\/images\/ajax_loader.gif\" alt=\"AJAX Loading Gif\" \/>";
                    this.loadingHtml[i++] = "                    <\/td>";
                    this.loadingHtml[i++] = "                <\/tr>";
                    this.loadingHtml[i++] = "            <\/table>";
                    this.loadingHtml[i++] = "        <\/div>";
                }

                // Overlay on the specified element
                $(element).addClass('loadingPanelContainer');
                var overlayElement = this.loadingHtml.join("");
                $(overlayElement).appendTo(element);

                if (callback) {
                    callback();
                }
            },
            // Hides the loading panel over the specified element
            hideLoadingPanel: function (element) {
                $(element).find(".loadingPanel").remove();
                $(element).removeClass('loadingPanelContainer');
            },          
			// Displays a div over an element with the specified string
			displayTextOverlay: function (element, message) {
				"use strict";

				var overlayArr,
					overlayElement;

				// Check if the element was added already
				if ($(element).has(".messageOverlay").length > 0) {
					return;
				}
				// Populate the html for the overlay div
				if (!this.messageHtml) {
					this.messageHtml = [];
					this.messageHtml.push("<div id=\"messageOverlay\" class=\"messageOverlay\">");
					this.messageHtml.push("            <table>");
					this.messageHtml.push("                <tr>");
					this.messageHtml.push("                    <td>");
					this.messageHtml.push("                        <span class=\"overlayMessageText\">[[message]]<\/span>");
					this.messageHtml.push("                    <\/td>");
					this.messageHtml.push("                <\/tr>");
					this.messageHtml.push("            <\/table>");
					this.messageHtml.push("        <\/div>");
				}

				overlayArr = this.messageHtml.slice();
				// Replace the message with the 
				overlayArr[4] = overlayArr[4].replace("[[message]]", message);
				overlayElement = overlayArr.join("");
				$(overlayElement).appendTo(element);

			},
			// Removes the overlay from the specified element
			removeTextOverlay: function (element) {
				"use strict";
				$(element).find(".messageOverlay").remove();
			},
			// Creates a modal dialog out of the element specified by wrapping the element
			// and assigning the trigger to show the modal dialog.
			createModal: function (element, trigger, customFunc, postfunc, immediate) {
				"use strict";
				var elementID = $(element).attr("id"); 

				//Create a element for the background if there isnt one
				if ($('#modalOverlay').length === 0) {
					$('body').append("<div id='modalOverlay'></div>");
				}

				// Check to see if the modal dialog is already set up
				if ($(element).parent('.modalWrapper').length === 0) {

					// Wrap the div with the modal container
					$(element).wrap("<div class='modalContainerOuter' id='modal" + elementID + "'><div class='modalContainerInner'><div class='modalWrapper'></div></div></div>");

					// Create the close button
					$(element).after("<a href='#' class='modalClose'>close</a>");

					// Add the content css class to the element
					$(element).addClass('modalContent');

					// Create a resize event handler
					$(window).bind('resize.modal', function () {
						Utils.DOM.centerModal($(element));
					});
				}

				// Create the open event trigger on the element
				$(trigger).bind("click", function (e) {
					Utils.DOM.showModal(e, element, elementID, customFunc, postfunc);
				});

				// Create the close events
				$('.modalClose').click(function () {
					Utils.DOM.closeParentModal($('.modalClose'));
				});

				// Show modal if immediate
				if (immediate) {
					Utils.DOM.showModal(null, element, elementID, customFunc, postfunc);
				}

			},
			// Shows a modal
			showModal: function (e, element, elementID, customFunc, postfunc) {
				"use strict";
				if (e) {
					e.stopPropagation();
					e.preventDefault();
				}
				// Clear the inputs
				this.clearElementInputs($(element));

				// Perform custom function if any
				if (customFunc) {
					customFunc();
				}

				// Get the modal div
				var modalDiv = $('#modal' + elementID);
				this.centerModal(modalDiv);
				$(modalDiv).addClass('visible');
				$('#modalOverlay').show();

				// Post-show function
				if (postfunc) {
					postfunc();
				}
			},
			// Closes a modal parent if any
			closeParentModal: function (element) {
				"use strict";
				$(element).closest('.modalContainerOuter').removeClass('visible');
				$('#modalOverlay').hide();
				$(window).unbind('resize.modal');
			},
			// Centers a modal popup on the window
			centerModal: function (element) {
				"use strict";
				$(element).closest('.modalContent').width($(element).outerWidth());
			},
			// Clears all control values from the element
			clearElementInputs: function (element) {
				"use strict";
				$(element).find("input").not(":button").val("");
				$(element).find("invalid").removeClass("invalid");
				$(element).find("select option").removeAttr("selected");
				$(element).find("textarea").val("");
				$(element).find("img.toClear128").attr('src', '../images/blank128.png');
			},
			// The HTML for the default pager
        	pagerHtml: "<div id='[pagerID]' class='pager'><form>" +
	                    "<img src='/PMT/images/pager/first.png' class='first'/>" +
	                    "<img src='/PMT/images/pager/previous.png' class='prev'/>" +
	                    "<span class='pagedisplay'></span>" +
	                    "<img src='/PMT/images/pager/next.png' class='next'/>" +
	                    "<img src='/PMT/images/pager/last.png' class='last'/>" +
	                    "<select class='pagesize'>" +
	                        "<option selected='selected'  value='10'>10</option>" +
	                        "<option value='20'>20</option>" +
	                        "<option value='30'>30</option>" +
	                        "<option value='40'>40</option>" +
	                        "<option value='50'>50</option>" +
	                    "</select>" +
	                "</form></div>",
		}
    }