function SubmitSort() {
	$('#meme-memecakes-sort').submit();
}

// <select> element displays its options on mousedown, not click.
function ShowDropdown(element) {
    var event;
    event = document.createEvent('MouseEvents');
    event.initMouseEvent('mousedown', true, true, window);
    element.dispatchEvent(event);
};

$(function() {
	$('.meme-sort-icon').on('click', function() {
		var dropdown = document.getElementById('sort-memecakes');
    	ShowDropdown(dropdown);
	});

	$('#sort-memecakes').on('change', function() {
		SubmitSort();
	});
});