<!DOCTYPE html>
<html>
<head>

<title>HTML5 AJAX Contact Form</title>

<link href="assets/css/contact.css" rel="stylesheet" type="text/css" /> <!-- AJAX Contact Form Stylesheet -->

<script src="//code.jquery.com/jquery-latest.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script><!-- AJAX Form Submit -->
jQuery(document).ready(function(){

	// When a field is left blank, jQuery will shake the form
	//	var shake = "Yes";
		var shake = "No";

	$('#message').hide();

	// Add validation parts
	$('#contact input[type=text], #contact input[type=number], #contact input[type=email], #contact input[type=url], #contact input[type=tel], #contact select, #contact textarea').each(function(){
		$(this).after('<mark class="validate"></mark>');
	});

	// Validate as you type
	$('#name, #comments, #subject').focusout(function() {
		if (!$(this).val())
			$(this).addClass('error').parent().find('mark').removeClass('valid').addClass('error');
		else
			$(this).removeClass('error').parent().find('mark').removeClass('error').addClass('valid');
	});
	$('#email').focusout(function() {
		if (!$(this).val() || !isEmail($(this).val()))
			$(this).addClass('error').parent().find('mark').removeClass('valid').addClass('error');
		else
			$(this).removeClass('error').parent().find('mark').removeClass('error').addClass('valid');
	});
	$('#website').focusout(function() {
		var web = $(this).val();
		if (web && web.indexOf("://") == -1) {
			//$(this).addClass('error').parent().find('mark').removeClass('valid').addClass('error');
			$(this).val('http://' + web);
			$(this).removeClass('error').parent().find('mark').removeClass('error').addClass('valid');
		} else if (web)
			$(this).removeClass('error').parent().find('mark').removeClass('error').addClass('valid');
		else
			$(this).removeClass('error').parent().find('mark').removeClass('error').removeClass('valid');
	});

	$('#verify').focusout(function() {
		var verify = $(this).val();
		var verify_box = $(this);
		if (!verify)
			$(this).addClass('error').parent().find('mark').removeClass('valid').addClass('error');
		else {

			// Test verification code via ajax
			$.ajax({
				type: 'POST',
				url: 'classes/ajax_check.php',
				data: { verify: verify },
				async: false,
				success: function( data ) {
					if (data=='success') {
						$(verify_box).removeClass('error').parent().find('mark').removeClass('error').addClass('valid');
					} else {
						$(verify_box).addClass('error').parent().find('mark').removeClass('valid').addClass('error');
					}
				}
			});

		}
	});

	$('#submit').click(function() {
		$("#message").slideUp(200,function() {
			$('#message').hide();

			// Kick in Validation
			$('#name, #subject, #phone, #comments, #website, #verify, #email').triggerHandler("focusout");

			if ($('#contact mark.error').size()>0) {
				if(shake == "Yes") {
					$('#contact').effect('shake', { times:2 }, 75, function(){
						$('#contact input.error:first, #contact textarea.error:first').focus();
					});
				} else $('#contact input.error:first, #contact textarea.error:first').focus();

				return false;
			}

		});
	});

	$('#contactform').submit(function(){

		if ($('#contact mark.error').size()>0) {
			if(shake == "Yes") {
			$('#contact').effect('shake', { times:2 }, 75);
			}
			return false;
		}

		var action = $(this).attr('action');

 		$('#submit')
			.after('<img src="assets/img/ajax-loader.gif" class="loader" />')
			.attr('disabled','disabled');

		$.post(action, $('#contactform').serialize(),
			function(data){
				$('#message').html( data );
				$('#message').slideDown();
				$('#contactform img.loader').fadeOut('slow',function(){$(this).remove()});
				$('#contactform #submit').removeAttr('disabled');
				if(data.match('success') != null) $('#contactform').slideUp('slow');

			}
		);

		return false;

	});

	function isEmail(emailAddress) {

		var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);

		return pattern.test(emailAddress);
	}

	function isNumeric(input) {
    	return (input - 0) == input && input.length > 0;
	}

});

</script>

<!--[if IE]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

</head>
<body>

	<section id="contact">

		<header>

			<h1><abbr title="HyperText Markup Language">HTML</abbr>5 <abbr title="Asynchronous Javascript and XML">AJAX</abbr> Contact Form</h1>
			<p>A form packed full of HTML5 and CSS3 awesomeness. With just 2 files this can be easily integrated into any <strong>HTML</strong> or <strong>PHP</strong> page!</p>

		</header>

		<mark id="message"></mark>

		<form method="post" action="classes/contact.php" name="contactform" id="contactform" autocomplete="on">

			<fieldset>

				<legend>Contact Details</legend>

				<div>
					<label for="name" accesskey="U">Your Name</label>
					<input name="name" type="text" id="name" placeholder="Enter your name" required="required" />
				</div>
				<div>
					<label for="email" accesskey="E">Email</label>
					<input name="email" type="email" id="email" placeholder="Enter your Email Address" pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$" required="required" />
				</div>

				<div>
					<label for="phone" accesskey="P">Phone <small>(optional)</small></label>
					<input name="phone" type="tel" id="phone" size="30" placeholder="Enter your phone number" />
				</div>

				<div>
					<label for="website" accesskey="W">Website <small>(optional)</small></label>
					<input name="website" type="url" id="website" placeholder="Enter your website address" />
				</div>

			</fieldset>

			<fieldset>

				<legend>Your Comments</legend>

				<div>
					<label for="subject" accesskey="S">Subject</label>
					<select name="subject" id="subject" required="required">
						<option value=""></option>
						<option value="Support">Support</option>
						<option value="A Sale">Sales</option>
						<option value="A Bug fix">Report a bug</option>
					</select>
				</div>

				<div>
					<label for="comments" accesskey="C">Comments</label>
					<textarea name="comments" cols="40" rows="3" id="comments" placeholder="Enter your comments" spellcheck="true" required="required"></textarea>
				</div>

				<div>
					<label class="radio">
						<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Choose option one?
					</label>
				</div>

				<div>
					<label class="radio">
						<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
						Or rather, option two?
					</label>
				</div>

				<div>
					<label for="agree" accesskey="A">Agree to terms</label>
					<input name="agree" type="checkbox" id="agree"/>
				</div>

			</fieldset>

			<fieldset>

				<legend>Are you human?</legend>

				<label for="verify" accesskey="V" class="verify"><img src="classes/image.php" alt="Verification code" /></label>
				<input name="verify" type="text" id="verify" size="6" required="required" style="width: 50px;" title="This confirms you are a human user and not a spam-bot." />

			</fieldset>

			<input type="submit" class="submit" id="submit" value="Submit" />

		</form>

	</section>

</body>
</html>
