$(function () {



	//Define in controller

	// var activeUsers = 55;



	// CanvasJS doughnut chart to show device type of active users
	if (document.getElementById('users-device-doughnut-chart')) {

		var usersDeviceDoughnutChart = new CanvasJS.Chart("users-device-doughnut-chart", {

			animationDuration: 800,

			animationEnabled: true,

			backgroundColor: "transparent",

			colorSet: "customColorSet",

			theme: "theme2",

			legend: {

				fontFamily: "calibri",

				fontSize: 14,

				horizontalAlign: "left",

				verticalAlign: "center",

				itemTextFormatter: function (e) {

					return e.dataPoint.name + ": " + Math.round(e.dataPoint.y / activeUsers * 100) + "%";

				}

			},

			title: {

				dockInsidePlotArea: true,

				fontSize: 55,

				fontWeight: "normal",

				horizontalAlign: "center",

				verticalAlign: "center",

				text: activeUsers

			},

			toolTip: {

				cornerRadius: 0,

				fontStyle: "normal",

				contentFormatter: function (e) {

					return e.entries[0].dataPoint.name + ": " + Math.round(e.entries[0].dataPoint.y / activeUsers * 100) + "% (" + e.entries[0].dataPoint.y + ")";

				}

			},

			data: [

				{

					innerRadius: "80%",

					radius: "90%",

					legendMarkerType: "square",

					showInLegend: true,

					startAngle: 90,

					type: "doughnut",

					dataPoints: [

						// desktopUsers, mobileUsers variables are define in controller

						{ y: desktopUsers, name: "Desktop" },

						{ y: mobileUsers, name: "Mobile" }

					]

				}

			]

		});

		// chart properties cutomized further based on screen width

		function chartPropertiesCustomization(chart) {

			if ($(window).outerWidth() >= 1920) {



				chart.options.legend.fontSize = 14;

				chart.options.legend.horizontalAlign = "left";

				chart.options.legend.verticalAlign = "center";

				chart.options.legend.maxWidth = null;



			} else if ($(window).outerWidth() < 1920 && $(window).outerWidth() >= 1200) {



				chart.options.legend.fontSize = 14;

				chart.options.legend.horizontalAlign = "left";

				chart.options.legend.verticalAlign = "center";

				chart.options.legend.maxWidth = 140;



			} else if ($(window).outerWidth() < 1200 && $(window).outerWidth() >= 992) {



				chart.options.legend.fontSize = 12;

				chart.options.legend.horizontalAlign = "center";

				chart.options.legend.verticalAlign = "top";

				chart.options.legend.maxWidth = null;



			} else if ($(window).outerWidth() < 992) {



				chart.options.legend.fontSize = 14;

				chart.options.legend.horizontalAlign = "center";

				chart.options.legend.verticalAlign = "bottom";

				chart.options.legend.maxWidth = null;

			}

			chart.render();
		}

		function customizeCharts() {

			chartPropertiesCustomization(usersDeviceDoughnutChart);

		}

		(function init() {

			customizeCharts();

			$(window).resize(customizeCharts);

		})();

	}

});