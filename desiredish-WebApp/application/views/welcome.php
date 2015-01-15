<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Disired Dish</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="css/bootstrap-fluid-adj.css" rel="stylesheet">
	<!-- js -->
    <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/jquery.boutique_min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	
	<script type="text/javascript">
        $(function() {
// 登陆和注册的tab
            $("#sign-in-tab").click(function(){
                $("#sign-in-form-outer").show();
                $("#sign-up-form-outer").hide();
				$("#upload-form-outer").hide();
            });
            $("#sign-up-tab").click(function(){
                $("#sign-in-form-outer").hide();
                $("#sign-up-form-outer").show();
				$("#upload-form-outer").hide();
            });
			var signup="<?php echo $sign_up_prompt;?>";
			if (signup!=""){
				$("#sign-up-tab").trigger("click");
			}else{
				$("#sign-in-tab").trigger("click");
			}
            
		});
	</script>
</head>
<body>

	
<script>
  // This is called with the results from from FB.getLoginStatus().
  /*function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
	  console.log(response.authResponse.userID);
	  console.log(response.authResponse.accessToken);
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';		
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {	
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }
*/
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '<?php echo $this->config->item('api_id','facebook'); ?>',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use version 2.1
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.
/*
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
*/
   FB.Event.subscribe('auth.authResponseChange', function(response) {
     // Here we specify what we do with the response anytime this event occurs. 
     if (response.status === 'connected') {
       // The response object is returned with a status field that lets the app know the current
       // login status of the person. In this case, we're handling the situation where they 
       // have logged in to the app.
	   console.log(response.authResponse.userID);
		console.log(response.authResponse.accessToken);
       testAPI();
     } else if (response.status === 'not_authorized') {
       // In this case, the person is logged into Facebook, but not into the app, so we call
       // FB.login() to prompt them to do so. 
       // In real-life usage, you wouldn't want to immediately prompt someone to login 
       // like this, for two reasons:
       // (1) JavaScript created popup windows are blocked by most browsers unless they 
       // result from direct interaction from people using the app (such as a mouse click)
       // (2) it is a bad experience to be continually prompted to login upon page load.
       FB.login();
     } else {
       // In this case, the person is not logged into Facebook, so we call the login() 
       // function to prompt them to do so. Note that at this stage there is no indication
       // of whether they are logged into the app. If they aren't then they'll see the Login
       // dialog right after they log in to Facebook. 
       // The same caveats as above apply to the FB.login() call here.
       FB.login();
     }
   });
  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
	//console.log();
	window.location = "login_facebook";
   /* FB.api('/me', function(response) {		
			$.post("ajax/facebook_login", 
			{		
				facebook_id:response.id,
				user_name:response.name
            },
			function(data,status){				
				data = eval("(" + data + ")");				
				console.log(data);				
			});		      
		window.location.href = "home";	
		//document.getElementById('status').innerHTML ='Thanks for logging in, ' + response.name + '!';
    });*/
  }
 /* $('#facebook').click(function(e) {
    FB.login(function(response) {
	  if(response.authResponse) {
		  parent.location ='<?php echo $base_url; ?>home/fblogin';
	  }
	},{scope: 'email,public_profile'});
	});*/
</script>

<div class="container">			
	<div class="navbar navbar-inverse navbar-fixed-top">			
		<div  class="navbar-inner">
				<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href='#'>DesiredDish</a>

					<div class="nav-collapse collapse">
						<ul class="nav pull-right">							
							<button class="btn btn-primary" id="sign-in-tab" type="button">sign in</button>
							<button class="btn btn-default" id="sign-up-tab" type="button">sign up</button>														
						</ul>

						<ul class="nav">
							
						</ul>
					</div>
					<!-- /.nav-collapse 
					<a href="#" id="facebook"><img src="images/fblogin.png" style="cursor:pointer;"></img></a>

					-->
				</div>	
		</div>
	</div>		
	<div class="container" role="main">
		<div class="jumbotron">
			<fb:login-button scope="public_profile,email,user_friends"></fb:login-button>

			<div id="status">
</div>

                    <div id="sign-in-form-outer">
                        <form id="sign-in-form" method="post" class="form-signin" action="login_web">
							<h3 class="form-signin-heading">Sign In</h3>
							<hr />
                            <div class="form-group"><input placeholder="user name" type="text" id="sign-in-user-name" name="user_name"></div>
                            <div class="form-group"><input placeholder="password" type="password" id="sign-in-password" name="password"></div>
                            <div class="prompt"><?php echo $login_prompt;?></div>
							<input type="submit" class="btn btn-primary" value="sign in">
                        </form>
                    </div>
                    <div id="sign-up-form-outer">
						<h3 class="form-signup-heading">Sign Up</h3>
						<hr />
                        <form id="sign-up-form" action="sign_up_web" method="post" class="form-signin">
                            <div class="form-group"><input placeholder="user name" type="text" id="sign-up-user-name" name="user_name"></div>
                            <div class="form-group"><input placeholder="password" type="password" id="sign-up-password" name="password"></div>
                            <div class="form-group"><input placeholder="password confirm" type="password" id="sign-up-password-confirm" name="password_confirm"></div>
                            <div class="prompt"><?php echo $sign_up_prompt; ?></div>
                            <input type="submit" class="btn btn-primary" value="sign up">
                        </form>
                    </div>			
		</div>
		</div>
    </div>
</div>
<footer>
		<div class="footer">
			<p>Coryright &copy; 2014 DesiredDish</p>
		</div>
</footer>
</body>
</html>