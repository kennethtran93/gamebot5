$(document).ready(function () {
	$("table#botSummary").DataTable({
		paging: false,
		searching: false,
		fixedHeader: true,
		responsive: {
			details: {
				type: "column",
				target: "tr"
			}
		},
		columns: [
			{
				className: "control",
				data: "control",
				orderable: false
			},
			{
				responsivePriority: 1,
				className: "all dt-center"
			},
			{
				responsivePriority: 2,
				className: "dt-center"
			},
			{
				responsivePriority: 5,
				className: "dt-center"
			},
			{
				responsivePriority: 4,
				className: "dt-center"
			},
			{
				responsivePriority: 3,
				className: "dt-center"
			}
		],
		order: [
			[1, 'asc']
		]
	});
	$("table#playerSummary").DataTable({
		paging: false,
		searching: false,
		fixedHeader: true,
		responsive: {
			details: {
				type: "column",
				target: "tr"
			}
		},
		columns: [
			{
				className: "control",
				data: "control",
				orderable: false
			},
			{
				responsivePriority: 1,
				className: "all dt-center"
			},
			{
				responsivePriority: 3,
				className: "dt-center"
			},
			{
				responsivePriority: 2,
				className: "dt-center",
				orderData: [3, 1, 2]
			}
		],
		order: [
			[3, 'desc'],
			[1, 'asc']
		]
	});
	$("table#latestActivity").DataTable({
		paging: false,
		searching: false,
		fixedHeader: true,
		responsive: {
			details: {
				type: "column",
				target: "tr"
			}
		},
		columns: [
			{
				className: "control",
				data: "control",
				orderable: false
			},
			{
				responsivePriority: 1,
				className: "all dt-center"
			},
			{
				responsivePriority: 2,
				className: "dt-center"
			},
			{
				responsivePriority: 3,
				className: "dt-center"
			},
			{
				responsivePriority: 5,
				className: "dt-center"
			},
			{
				responsivePriority: 4,
				className: "dt-center"
			}
		],
		order: [
			[1, 'desc']
		]
	});
	$("table#latestTransactions").DataTable({
		paging: false,
		searching: false,
		fixedHeader: true,
		responsive: {
			details: {
				type: "column",
				target: "tr"
			}
		},
		columns: [
			{
				className: "control",
				data: "control",
				orderable: false
			},
			{
				responsivePriority: 1,
				className: "all dt-center"
			},
			{
				responsivePriority: 2,
				className: "dt-center"
			},
			{
				responsivePriority: 3,
				className: "dt-center"
			},
			{
				responsivePriority: 4,
				className: "dt-center"
			}
			,
			{
				responsivePriority: 5,
				className: "dt-left"
			}
		],
		order: [
			[1, 'desc']
		]
	});
});