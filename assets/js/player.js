$(document).ready(function () {
	$("table#playerCards").DataTable({
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
				responsivePriority: 3,
				className: "dt-center"
			},
			{
				responsivePriority: 2,
				className: "dt-center"
			}
		],
		order: [
			[1, 'asc'],
			[2, 'asc'],
			[3, 'desc']
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
				responsivePriority: 4,
				className: "dt-left"
			}
		],
		order: [
			[3, 'desc'],
			[1, 'asc']
		]
	});
});