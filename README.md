Laravel: automated deployment using a GitHub webhook

Make a git push to GitHub deploy the new modifications to a remote server.

How it works
GitHub sends a POST request to a specific URL on the server
That URL triggers the execution of a deployment shell script.

Make it executable.

chmod 777 deploy.sh

Create a GitHub webhook
On GitHub, on your repository page, select the Settings tab, then Webhooks in the left navigation. Or go directly to the URL:

https://github.com/<your account>/<your repository>/settings/hooks
Click Add webhook:

Payload URL: http://your-server.com/deployx
Secret: A long random string. You'll also need it in the next section.

Add the webhook secret to Laravel
In config/app.php, add:

'deploy_secret' => env('APP_DEPLOY_SECRET'),
In your .env file, add your webhook secret:

APP_DEPLOY_SECRET=fghfghfgh574ui476jn5ej65436u

Add a route for the controller method
Let's add the route used by the GitHub webhook and link it to the controller method.

In routes/web.php, 
add:

Route::post('deployx', 'GitDeployController@deployx');

Add the route on CSRF verification ignore on App\Http\Middleware\VerifyCsrfToken.php 

protected $except = [
        '/deployx'
];



Also authenticate the Server with github using SSH key 

then we can call the git command from the server 


Thank You!

