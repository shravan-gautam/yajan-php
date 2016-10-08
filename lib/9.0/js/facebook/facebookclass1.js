function Facebook(id)
{
	var div = document.createElement('div');
	div.id = "fb-root";
	document.body.appendChild(div);
	this.appId = id;
	FB.init({
      appId      : this.appId,
      status     : true,
      xfbml      : true,
	  cookie     : true
    });
	this.loginStatus = false;
	this.connection  = null;

	FB.getLoginStatus(function(response) 
	{
		alert(response.status);
		this.connection = response;
		if (response.status=='connected') 
		{
			this.loginStatus=true;
        }
	});


	this.isLogin = function()
	{
		return this.loginStatus;
	}
}

function FacebookPage(id)
{
	this.id = id;
}