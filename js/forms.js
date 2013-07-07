var Form = {
	load: function(name) {
		$.get('forms/'+name+'.html', function(data) {
			$('#main').html(data);
		});
	},
	toolbar: function(name) {
		$.get('forms/'+name+'.html', function(data) {
			$('#main').animate({
					'margin-left': '-40px'
				}, 300, 'easeOutBounce', function() { });
			
			setTimeout(function(data) {
				$('#toolbar').html(data);
				$('#toolbar').css({display: 'block', 'margin-right': '-200px', opacity: 0 });
				$('#toolbar').animate({
						'margin-right': '-20px',
						opacity: 1
					}, 300, 'easeOutBounce', function() { });
			}, 300, data);
		});
	}
};

var user = 0 ;

$(document).ready(function() {
	Form.load('home');
	
	$('header h1').click(function() {
		Form.load('home');		
	});
});