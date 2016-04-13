$(document).ready(function () {
	$("table#allPlayers").DataTable({
		paging: false,
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
				className: "all dt-center",
				orderable: false
			},
			{
				responsivePriority: 2,
				className: "dt-center"
			},
			{
				responsivePriority: 4,
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
				responsivePriority: 5,
				className: "dt-center"
			}
		],
		order: [
			[2, 'asc']
		]
	});
});