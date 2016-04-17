$(document).ready(function () {
	// Initial assignment of dropbox values
	var top = $("#top").val();
	var middle = $("#middle").val();
	var bottom = $("#bottom").val();

	// On dropdown changes, replace image with selected bot piece image, and alter alt description
	$('#top').change(function () {
		top = $(this).val();
		$('#hidden_topPiece').val($('#top option:selected').text());
		$('#topPiece').attr({
			src: window.location.pathname.replace("/assemble", "") + '/assets/images/bot/' + top.substr(0,5) + '.jpeg',
			alt: $('#top option:selected').text()
		});
	});
	$('#middle').change(function () {
		middle = $(this).val();
		$('#hidden_middlePiece').val($('#middle option:selected').text());
		$('#middlePiece').attr({
			src: window.location.pathname.replace("/assemble", "") + '/assets/images/bot/' + middle.substr(0,5) + '.jpeg',
			alt: $('#middle option:selected').text()
		});
	});
	$('#bottom').change(function () {
		bottom = $(this).val();
		$('#hidden_bottomPiece').val($('#bottom option:selected').text());
		$('#bottomPiece').attr({
			src: window.location.pathname.replace("/assemble", "") + '/assets/images/bot/' + bottom.substr(0,5) + '.jpeg',
			alt: $('#bottom option:selected').text()
		});
	});

});