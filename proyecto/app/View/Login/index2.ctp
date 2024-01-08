<style>
	html, body {
		background: rgba(241,50,40,1);
		background: -moz-linear-gradient(top, rgba(241,50,40,1) 0%, rgba(69,39,36,1) 100%);
		background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(241,50,40,1)), color-stop(100%, rgba(69,39,36,1)));
		background: -webkit-linear-gradient(top, rgba(241,50,40,1) 0%, rgba(69,39,36,1) 100%);
		background: -o-linear-gradient(top, rgba(241,50,40,1) 0%, rgba(69,39,36,1) 100%);
		background: -ms-linear-gradient(top, rgba(241,50,40,1) 0%, rgba(69,39,36,1) 100%);
		background: linear-gradient(to bottom, rgba(241,50,40,1) 0%, rgba(69,39,36,1) 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f13228', endColorstr='#452724', GradientType=0 );
	}
</style>
<div>
	<div style="width: 340px; margin: 0 auto; padding-top: 20px; padding-bottom: 20px;">
		<img src="/img/logo-sis-rai.png" alt="" width="340px" />
	</div>
</div>

<div>
	<div style="width: 242px; margin: 0 auto;margin-top: 200px;">
		<div id="my-signin2"></div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
<meta name="google-signin-client_id" content="481807052410-9ajq2cto6md909gfk227a4r4e6cvs8fn.apps.googleusercontent.com">

<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
		$.post( "callout/", function( data ) {
			console.log(data);
			location.reload();
		});
    });
  }
signOut();
function onSuccess(googleUser) {
	var profile = googleUser.getBasicProfile();
	console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
	console.log('Name: ' + profile.getName());
	console.log('Image URL: ' + profile.getImageUrl());
	console.log('Email: ' + profile.getEmail());
	console.log('Token: ' + googleUser.getAuthResponse());
	console.dir(googleUser.getAuthResponse());
	$.post( "callback/"+googleUser.getAuthResponse().id_token+"/"+profile.getId()+"/"+profile.getName()+"/"+profile.getEmail(), function( data ) {
		 window.location=data;
	});
}
function onFailure(error) {
      console.log(error);
    }
    function renderButton() {
      gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': false,
        'theme': 'dark',
        'onsuccess': onSuccess,
        'onfailure': onFailure
      });
    }
</script>

<?php
	echo @$this->Session->read('uid');
?>

<a href="#" onclick="signOut();">Sign out</a>