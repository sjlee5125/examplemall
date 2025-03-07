function tick() {
	$('#ticker_01 li:first').slideUp(function () {
		$(this).appendTo($('#ticker_01')).slideDown()
	})
}
setInterval(function () {
	tick()
}, 3000)
