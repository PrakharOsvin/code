<html>
<head>
<meta charset="utf-8">
    <meta name="robots" content="noindex">
    <title>MASIR</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <style type="text/css">
    
    </style>
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        window.alert = function(){};
        var defaultCSS = document.getElementById('bootstrap-css');
        function changeCSS(css){
            if(css) $('head > link').filter(':first').replaceWith('<link rel="stylesheet" href="'+ css +'" type="text/css" />'); 
            else $('head > link').filter(':first').replaceWith(defaultCSS); 
        }
        $( document ).ready(function() {
          var iframe_height = parseInt($('html').height()); 
          window.parent.postMessage( iframe_height, 'http://bootsnipp.com');
        });
    </script>
</head>
<body>
  <section id="container">
  	<h1><center>Hello !! <?php print_r($query[0]->name); print_r($query[0]->first_name);?></center></h1>
  <h2><center>Your Email Id is Verified</center></h2>
	<!-- <div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h1><center>You Can Change You Password Here</center></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<form method="post" id="passwordForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<?php echo form_error('password'); ?>
				<input type="password" class="input-lg form-control" name="password" id="password" placeholder="Old Password" autocomplete="off" required>
				<br>
				<input type="password" class="input-lg form-control" name="password1" id="password1" placeholder="New Password" autocomplete="off">
				<div class="row">
					<div class="col-sm-6">
						<span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Characters Long<br>
						<span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Uppercase Letter
					</div>
					<div class="col-sm-6">
						<span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Lowercase Letter<br>
						<span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One Number
					</div>
				</div>
				<?php echo form_error('password2'); ?>
				<input type="password" class="input-lg form-control" name="password2" id="password2" placeholder="Repeat Password" autocomplete="off">
				<div class="row">
					<div class="col-sm-12">
						<span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Passwords Match
					</div>
				</div>
				<input type="submit" id="btn" class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Changing Password..." value="Change Password">
			</form>
		</div>
	</div>
	</div> -->
	<script type="text/javascript">
	// 	$("input[type=password]").keyup(function(){
	//     var ucase = new RegExp("[A-Z]+");
	// 	var lcase = new RegExp("[a-z]+");
	// 	var num = new RegExp("[0-9]+");
		
	// 	if($("#password1").val().length >= 8){
	// 		$("#8char").removeClass("glyphicon-remove");
	// 		$("#8char").addClass("glyphicon-ok");
	// 		$("#8char").css("color","#00A41E");
	// 	}else{
	// 		$("#8char").removeClass("glyphicon-ok");
	// 		$("#8char").addClass("glyphicon-remove");
	// 		$("#8char").css("color","#FF0004");
	// 	}
		
	// 	if(ucase.test($("#password1").val())){
	// 		$("#ucase").removeClass("glyphicon-remove");
	// 		$("#ucase").addClass("glyphicon-ok");
	// 		$("#ucase").css("color","#00A41E");
	// 	}else{
	// 		$("#ucase").removeClass("glyphicon-ok");
	// 		$("#ucase").addClass("glyphicon-remove");
	// 		$("#ucase").css("color","#FF0004");
	// 	}
		
	// 	if(lcase.test($("#password1").val())){
	// 		$("#lcase").removeClass("glyphicon-remove");
	// 		$("#lcase").addClass("glyphicon-ok");
	// 		$("#lcase").css("color","#00A41E");
	// 	}else{
	// 		$("#lcase").removeClass("glyphicon-ok");
	// 		$("#lcase").addClass("glyphicon-remove");
	// 		$("#lcase").css("color","#FF0004");
	// 	}
		
	// 	if(num.test($("#password1").val())){
	// 		$("#num").removeClass("glyphicon-remove");
	// 		$("#num").addClass("glyphicon-ok");
	// 		$("#num").css("color","#00A41E");
	// 	}else{
	// 		$("#num").removeClass("glyphicon-ok");
	// 		$("#num").addClass("glyphicon-remove");
	// 		$("#num").css("color","#FF0004");
	// 	}
		
	// 	if($("#password1").val() == $("#password2").val() && $("#password1").val() != ""){
	// 		$("#pwmatch").removeClass("glyphicon-remove");
	// 		$("#pwmatch").addClass("glyphicon-ok");
	// 		$("#pwmatch").css("color","#00A41E");
	// 	}else{
	// 		$("#pwmatch").removeClass("glyphicon-ok");
	// 		$("#pwmatch").addClass("glyphicon-remove");
	// 		$("#pwmatch").css("color","#FF0004");
	// 	}
	// });
	// 	</script>
  </section>
</body>
</html>
