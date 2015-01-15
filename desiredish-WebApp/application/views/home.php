<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Disired Dish</title>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- css -->
	
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap-fluid-adj.css" rel="stylesheet">	
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" media="screen"/>	
	<link href="css/home.css" rel="stylesheet" type="text/css" />
	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	
	<!-- js -->
	
    <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/jquery.boutique_min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-filestyle.js"> </script>
	   
	
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	 <script type="text/javascript"
     src="js/bootstrap-datetimepicker.min.js">
    </script>
    <script type="text/javascript"
     src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js">
    </script>
	
	<script type="text/javascript">
	
    $(function() {
// 登陆和注册的tab
	/*	$("#dialog").dialog({
				dialogClass: "no-close",
				autoOpen: false,
				buttons: [
						{
							text: "OK",
							click: function() {
								$( this ).dialog( "close" );
							}
						}
				]
		});*/
        $("#get-list").click(function(){
			$.post("ajax/get_list", 
			{		
				user_id:<?php echo $this->session->userdata('user_id');?>
            },
			function(data,status){				
				data = eval("(" + data + ")");				
				//console.log(data);
				var innerHTML="<ul>";
				for (var i=0;i<data.length;i++){
					innerHTML+="<div class=\"thumbnail\">";
					innerHTML+="<img src=\""+data[i].thumbimg_dir+"\">";
					innerHTML+="<div class=\"caption\"><h4>"+data[i].description+"</h4></div>"
					innerHTML+="<p>"+data[i].dining_time;
					if (data[i].restaurant_name!="") innerHTML+=" @ "+data[i].restaurant_name;
					innerHTML+="</p></div>	<hr />";
				}
				innerHTML+="</ul>";
				document.getElementById("list").innerHTML = innerHTML;	
				//data.metadata.image_path_prefix+data.data.relationships.primary_image.items[0].path
				//var innerHTML="<a class=\"pull-left\" href=\"#\"> <img src=\""+data.metadata.image_path_prefix+data.data.relationships.primary_image.items[0].path+"\" class=\"media-object\" onclick=\"get_metrics(this)\" width=100px> </a>";			
				//innerHTML+="<h4 class=\"media-heading\">"+document.getElementById("company_name").value+"</h4>";
				//document.getElementById("focus").innerHTML += innerHTML;		   
			});
			$("#welcome").hide();
			$("#profile").hide();
			$("#export").hide();
			$("#list").show();
			$("#meal").hide();
		});		
		$("#get-welcome").click(function(){
            $("#welcome").show();
			$("#profile").hide();
			$("#export").hide();
			$("#list").hide();
			$("#meal").hide();
        });
        $("#get-profile").click(function(){
            $("#welcome").hide();
			$("#profile").show();
			$("#export").hide();
			$("#list").hide();
			$("#meal").hide();
        });
		$("#get-export").click(function(){
            $("#welcome").hide();
			$("#profile").hide();
			$("#export").show();
			$("#list").hide();
			$("#meal").hide();
        });
		$("#add-meal").click(function(){
            $("#welcome").hide();
			$("#profile").hide();
			$("#export").hide();
			$("#list").hide();
			$("#meal").show();
        });
		$("#get-welcome").trigger("click");
	});
	var opt={
		
		dialogClass: "no-close",
				autoOpen: false,
				buttons: [
						{
							text: "OK",
							click: function() {
								$( this ).dialog( "close" );
							}
						}
				]
	}
	function upload(){						
		var file_data = $("#userfile").prop("files")[0];   		
		var fileName = $("#userfile").val();
		
		if(fileName.lastIndexOf("png")===fileName.length-3 || fileName.lastIndexOf("jpg")===fileName.length-3){								
			//$('#upload-icon').html('<i class="fa fa-spin fa-spinner"></i>');
			var form_data = new FormData();                  
			form_data.append("f_file", file_data); 
			form_data.append("RestName", $("#rest-name").val()); 				
			form_data.append("Description", $("#description").val()); 	
			form_data.append("isRestaurant", $("#is-restaurant").val()); 	
			var x=document.getElementsByName("rating");
			var rating=0;
			var i;
			for (i = x.length-1; i > -1; i--) {
				if (x[i].checked){
					if (rating<x[i].value) {
						rating=x[i].value;
					}					
				}
			}
			form_data.append("Rating", rating); 	
			form_data.append("time", $("#dining-time").val()); 	
			$.ajax({
                url: "ajax/upload",               
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
				enctype: 'multipart/form-data',

                complete: function(data){		
					console.log(data['responseText']);	
					console.log(data['responseText']['fullimg_success']);
					var message=eval("(" + data['responseText'] + ")");
					if (message["fullimg_success"]){
						$("#dialog").html("<p>Meal added success! Continue add another meal!</p>");
					}else{
						$("#dialog").html("<p>"+message['error']+"</p>");		
					}
					$(':input').not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('');
					$(':checkbox, :radio').prop('checked', false);
					var theDialog = $("#dialog").dialog(opt);					
					var dialog = theDialog.dialog("open");
					setTimeout(function() { dialog.dialog("close"); }, 2000);
					
					//console.log(data['fullimg_success']);														
                }
			});				            			     	
		}else{
			alert("Not file choosen!");
		}
	};			
	</script>
	<script>
$(function() {$("#logout").click(function(){
	FB.getLoginStatus(function(response) {
        if (response && response.status === 'connected') {
			console.log(response.id);
			console.log(response);
            FB.logout(function(response) {					
            });
        };
		window.location.href = "/desireddish/logout";
	});
});
});
window.fbAsyncInit = function() {
  FB.init({
    appId      : '1517433775207522',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use version 2.1
  });  
  
  //FB.logout();
};
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
 </script>
</head>
<body>
<script type="text/javascript">
  $(function() {
    $('#datetimepicker').datetimepicker({
      language: 'en',
      pick12HourFormat: true
    });
  });
</script>
<div class="container">			
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div  class="navbar-inner">
				<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" id="get-welcome" href='#'>DesiredDish</a>
					<div class="nav-collapse collapse">
						<ul class="nav pull-right">
							
							<li class="navbar-text">Welcome</li>
							<li><a href="#" id="logout">Logout</a></li>
							
						</ul>

						<ul class="nav">							
							<li><a id="get-list" href="#">Meal List</a></li>
							<li><a id="add-meal" href="#">Add a Meal</a></li>
							<li><a id="get-profile" href="#">View Profile</a></li>
							<li><a id="get-export" href="#">Export API</a></li>														
						</ul>
					</div>					
					<!-- /.nav-collapse -->
				</div>			
		</div>
	</div>		
	<div class="container" role="main">							
		<div  class="jumbotron" align="center" id="welcome">
			<h1>DesiredDish </h1>	
			<p>A food journaling, food sharing, and food ordering app.</p>
			<div>                          
                <h7><a href="mailto:lw555@cornell.edu?subject=Notify me when the Android app is available">Request for Android</a></h7>
            </div>
		</div>					
		<div  class="jumbotron" id="list">							
		</div>
		<div  class="jumbotron" id="meal">	
			<div class="checkbox" align="left" >
				<label>
					<input type="checkbox" id="is-restaurant"> Eat in a restaurant?
				</label>
			</div>
			<div class="form-group" align="left">
				<label for="exampleInputEmail1">Restaurant name:</label>
				<input placeholder="Restaurant name" type="text" id="rest-name" >
			</div>
			<div class="form-group" align="left">
				<label for="exampleInputEmail1">Description:</label>								
				<textarea placeholder="Description" class="form-control" rows="3" id="description"></textarea>
			</div>			
			
			<div class="form-group" align="left">
			<label >Dining time:</label>			
			<div id="datetimepicker" class="input-append date">
				<input data-format="yyyy/MM/dd/ hh:mm:ss" type="text" id="dining-time"></input>
				<span class="add-on">
					<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
				</span>
			</div>
			</div>									
												
			<div class="form-group" align="left">
				<label for="exampleInputFile" >File input:</label>
			</div>
			<div class="form-group" align="left" style="margin-left:-30px" >				
				<input type="file" class="filestyle" id="userfile" name='userfile' >												
			</div>

			<div class="form-group" align="left" style="height:60px;width:600px">
			<label >Rating:</label>
			<fieldset class="rating">				
				<input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Rocks!">5 stars</label>
				<input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Pretty good">4 stars</label>
				<input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Meh">3 stars</label>
				<input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Kinda bad">2 stars</label>
				<input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Sucks big time">1 star</label>
			</fieldset>
			</div>
			</br></br>
			<button onclick="upload()" class="btn btn-default">Submit</button>			
		</div>
		<div id="export">	
			<h1 id="desireddish-api"><a href="#desireddish-api" class="anchor">DesiredDish API</a></h1>
			<p>This project provides an unofficial json API interface to search DesiredDish restaurant data. It eliminates the need to download, parse and import data from XLS file.</p>
			<h2 id="api-reference"><a href="#api-reference" class="anchor">API Reference</a></h2>

			<h3 id="get-data-stats"><a href="#get-data-stats" class="anchor">Get data stats</a></h3>
			<pre class="no-highlight">GET /api/restaurant</pre>			
			<p>Parameters:</p>
			<ul>
				<li><code>name</code> - Name of the restaurant</li>
			</ul>
			<p>Example request:</p>
			<pre class="no-highlight">http://54.165.111.90/desireddish/api/restaurant?name=Alcala</pre>
			<p>Returns response:</p>
			<pre class="no-highlight">{
    "restaurant": [
        {
            "RestaurantID": "101",
            "RName": "Alcala",
            "Address": "246 East 44th Street",
            "City": "New York",
            "State": "NY",
            "postal_code": "10017",
            "phone": "2123701866",
            "reserve_url": "http://www.opentable.com/single.aspx?rid=101",
            "mobile_reserve_url": "http://mobile.opentable.com/opentable/?restId=101"
        }
    ],
    "data": [
        {
            "img_dir": "uploads/Tulips.jpg",
            "thumbimg_dir": "uploads/Desert.jpg",
            "description": "Great",
            "rating": "5"
        },
        {
            "img_dir": "uploads/Lighthouse.jpg",
            "thumbimg_dir": "uploads/Koala.jpg",
            "description": "hi",
            "rating": "4"
        }
    ]
}</pre>	
			
			
			<h2 id="contact"><a href="#contact" class="anchor">Contact</a></h2>
			<ul>
				<li>Lilin Wang</li>
				<li><a href="mailto:lw555@cornell.edu">lw555@cornell.edu</a></li>
				<li>Yanjing Zhang</li>
				<li><a href="mailto:yz785@cornell.edu">yz785@cornell.edu</a></li>
			</ul>

		</div>
		<div  class="jumbotron" id="profile">	
			UserName: <?php echo $this->session->userdata('user_name');?>
			</br>
			UserID: <?php echo $this->session->userdata('user_id');?>
		</div>		
	</div> 
</div>
<div id="dialog" title="Upload complete">
	
</div>
<footer>
		<div class="footer">
			<p>Coryright &copy; 2014 DesiredDish</p>
		</div>
</footer>
<div id="dialog" title="Basic dialog">
</div>
</body>
</html>