UNLOQ PHP SDK - Laravel passwordless authentication
===================================
Develop passwordless authentication for Laravel framework using UNLOQ.io Multi-factor Authentication Solution. 

Before continuing, just take a look at [UNLOQ in 40 seconds](https://vimeo.com/151232399).

- [Requirements](#requirements)
- [Installation and setup](#installation-and-setup)
- [Implement passwordless authentication](#implement-passwordless-authentication)
    - [EMAIL method - unique token sent to email](#1-email-method-unique-token-sent-to-email)
    - [UNLOQ method - Mobile app Push Notifications](#2-unloq-method-mobile-app-push-notifications)
- [Implement UNLOQ as second factor + Managing authentication options](#implement-unloq-as-second-factor-managing-authentication-options)    
- [References & Thanks](#references-thanks)

<a name="requirements"></a>
## Requirements
- a Smartphone with UNLOQ application installed;
- an API key from a custom web application from your UNLOQ account. You can read more on [general concerns](general.md).  
- Homestead would be great but you can adapt to your own local environment;
- Laravel Framework 5.5.

<a name="installation"></a>
## Installation and setup
We need to :
- install the Laravel framework:

```php
laravel new laravel2fa
```

- scaffold basic auth:

```php
php artisan make:auth
```

- and install UNLOQ PHP SDK. The library will enable us to take advantage of the UNLOQ API and implement passwordless Authentication:

```php
composer require unloq/unloq-php-sdk
```

<a name="implementation"></a>
## Implement passwordless authentication
<a name="email-method---unique-token-send-to-email"></a>
#### 1. EMAIL method - unique token sent to email
We'll be implementing a passwordless registration and login, for the users of the application, overriding some of the Laravel's functionalities. We'll do that in such way that later we'll be able to allow password authentication too. That in the administrative dashboard that will be building to take advantage of UNLOQ features.

***VERY IMPORTANT*** 

Should you chose to go with this passwordless system, you will have to decide how to handle the rest of auth routes. By default, Laravel will add, when scaffolding authentication, the following line to routes/web.php :
```php
Auth::routes();
```
This will load the authentication routes, that can be found here:
> vendor/laravel/framework/src/Illuminate/Routing/Router.php

within the auth() method. You will need to decide what to do with the unused routes.

##### The registration
In order to have a passwordless authentication, we need to have a passwordless registration. For that we'll need to 
allow users to register, and send them a login link to the email. 

###### UNLOQ Application settings
The follwing settings are required for the current implementation. Login to your UNLOQ account and:

- enable within your UNLOQ application the Login method "E-mail login":
    - UNLOQ Account -> Application -> {Your app name} -> Settings -> Authentication.
- create a Login Widget:
    - UNLOQ Account -> Application -> {Your app name} -> Settings -> Widgets 

Though the widget won't be used in this tutorial, it's required to authenticate users with EMAIL method. 
When you'll create a login widget, a login URL will be required with a public domain name.

Even if you have a local development environment accessible at something like laravel2fa.app, we can make it work.

Set for the Login Widget, the login url like http://laravel2fa.com/unloq/email. Later we'll hack a bit the login url received through email to login the user. 
 
###### Registration views
Comment out the "Password" and "Confirm password" fields from the registration view file 

> resources/views/auth/register.blade.php

We will need them later when we'll extend authentication within an administrave panel.

###### Registration controller
Currently the registration controller is validating if the password is sent and confirmed through the request. Then it stores it in the database, crypted, when the user is created. We need to adjust that as currently we won't be using passwords.

Update RegistrationController.php file as follows:
> app/Http/Controllers/Auth/RegisterController.php

- add the following classes 

```php
use Illuminate\Support\Facades\Request;
use Illuminate\Auth\Events\Registered;
use Unloq\Api\Contracts\Approval\Authenticate;
use Unloq\Unloq;
```
- update the protected property $redirectTo with the value `'/registration/success'`. That, as upon a successful registration we want to just let the user know what he needs to do next.  
 
- update the validation of data sent through, to ignore password validation
```php
protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            //'password' => 'required|string|min:6|confirmed',
        ]);
    }
```
- update the create function, as we won't be using passwords now

```php
protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            //'password' => bcrypt($data['password']),
            'password' => 0,
        ]);
    }
```
- copy the register() method from the RegistersUsers trait and paste it into the controller so we override it;
- remove the line with `$this->guard()->login($user);` as we don't want to login the user after registration;
- add the following line instead `(new UnloqAuthController)->authenticate($request->input('email'), 'EMAIL');`;
- this will result in : 

```php
public function register(Request $request)
{
  $this->validator($request->all())->validate();

  event(new Registered($user = $this->create($request->all())));

  (new UnloqAuthController)->authenticate($request->input('email'), 'EMAIL');

  return $this->registered($request, $user)
                  ?: redirect($this->redirectPath());
}
```
- let's create

> app/Http/Controllers/Unloq/UnloqAuthController.php
 
- and add the method authenticate() which will use later when we want to send authentication requests:

```php
public function authenticate($email, $method = null, $token = null)
{
    $unloqClient = new Unloq(env('UNLOQ_API_KEY'));

    $payload = new Authenticate();
    $payload->setEmail($email)
        ->setMethod(isset($method) ? $method : 'UNLOQ')
        ->setIp($_SERVER['REMOTE_ADDR']);

    if(isset($token))
        $payload->setToken($token);

    return $unloqClient->authenticate($payload);
}
```
- we'll also need the registration success page so let's add that too:
> routes/web.php

```php
Route::get('/registration/success', function(){
    return view('auth.registersuccess');
});
```
- and create the file

> resources/view/auth/registersuccess.blade.php

with an explanatory message for the user "Account successfully created. Please check your email for the login link."

Again, we are are not removing passwords 100% . That because as we'll build an administrative interface to use more UNLOQ functionalities, we might allow users to use passwords too, if they chose to.

The customisation of the registration is for you to implement as it best fits your app architecture. The current tutorial only demonstrates the EMAIL authentication for UNLOQ.


##### The login
###### 1. Email login ( "EMAIL" authentication method)
Now that we've managed to implement the registration and send a login link to email used to register, let's login the user that just registered.

Let's add :
- the route to receive the token:

> routes/web.php

```php
Route::get('/unloq/email', 'Unloq\UnloqAuthController@email');
```
- the email()  method that will verify the token and login the user OR return the error response:

> app/Http/Controllers/Unloq/UnloqAuthController.php

```php
public function email(Request $request)
    {
        $token = $request->get('token');
        if(strlen($token)){
            $unloqClient = new Unloq(env('UNLOQ_API_KEY'));

            $payload = new Token();
            $payload->setToken($token);

            $validateToken = $unloqClient->token($payload);

            if($validateToken->httpCode === 200){
                // we expect to receive the email of the user
                // that requested authentication through email
                if(isset($validateToken->responseMessage->result) &&
                isset($validateToken->responseMessage->result->email)){
                    if($user = User::where(['email' => $validateToken->responseMessage->result->email])->first()){
                        Auth::login($user);
                        return redirect('/home');
                    } else
                        $message = 'We could not identify you within the system. Please contact the administrator.';
                } else {
                    $message = 'Something went wrong while authenticating you. Please try again.';
                }
            } else {
                if(isset($validateToken->responseMessage->error))
                    $message = $validateToken->responseMessage->error->message;
                else
                    $message = 'Unknown error occured while validating the token';
            }
        } else {
            $message = 'Token not provided';
        }

        return view('auth.invalidtoken')->with([
            'message' => $message,
        ]);
    }
```
***VERY IMPORTANT***

The url that the user receives through email will contain a public domain name. For development purposes, on a private environment, just change the domain name from the URL.
 
 When you receive `http://laravel2fa.com/unloq/email?token=AU11aUjBUy` in the email, change it to `http://laravel2fa.app/unloq/email?token=AU11aUjBUy`. Or just use instead of `larave2fa.app` the name you are using to access your private development environment.

- - - -

This only covers the login of the user that just registered. In order to make available login of existing users, you still need to :
- customise the login view and remove the password field;
- override the login method and
    - remove authentication of the user by credentials;
    - use the following request `(new UnloqAuthController)->authenticate($request->input('email'), 'EMAIL');`;
    - redirect the user to a view where to inform him to visit his email account to login;
- check your email and visit the login link to get logged in;

**LET'S DO IT :)**

The following controller handles authentication :

> app/Http/Controllers/Auth/LoginController.php

You won't see too much code as it actually uses a Trait which contains the required methods for login `use AuthenticatesUsers;`. So in order to customize our login with:
- validating the email address used for login;
- check if the user exists in our DB and :
    - if exists, send the login email to the user's email and redirect to a success page;
    - if it doesn't exist show an error message;

we need to copy some methods from the trait, and paste them in LoginController.php to modify them as follows:

```php
public function login(Request $request)
{
    $this->validateLogin($request);

    if ($this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);

        return $this->sendLockoutResponse($request);
    }

    // if ($this->attemptLogin($request)) {
    //    return $this->sendLoginResponse($request);
    // }

    // we override the default authentication, with the flow below
    $authenticate = (new UnloqAuthController)->authenticate($request->input('email'), 'EMAIL');

    // if the request is successful, we are going to reload
    // the login page with a variable set to true, which will
    // determine the output of the login page
    if ($authenticate->httpCode === 200) {
        return view('auth.login')->with([
            'loginLinkSent' => true
        ]);
    }

    $this->incrementLoginAttempts($request);

    //return $this->sendFailedLoginResponse($request);

    // if anything goes wrong with our authentication request,
    // we just throw back the error that occurred during the 
    // authentication request
    throw ValidationException::withMessages([
        $this->username() => $authenticate->errorMessage,
    ]);
}

protected function validateLogin(Request $request)
{
    $this->validate($request, [
        $this->username() => 'required|string|exists:users',
        //'password' => 'required|string',
    ]);
}
```

As mentioned previously, we will not delete everything related to passwords, if we want to allow passwords at a certain point too.

What we did is we override the login method to send an authentication request to UNLOQ Api, instead of validating username/password.

Upon a positive http response, we proceed to show the login page without the login form, but with a success message. For this please update the file

> resources/views/auth/login.blade.php
 
as follows:
```php
@if(!isset($loginLinkSent))
    <div class="panel panel-default">
        <div class="panel-heading">Login</div>

        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

            ...
          
            </form>
    </div>
</div>
@else
    <div>
        <h5>Your login link has been sent, please check your email.</h5>
    </div>
@endif
```

This will show the login form when you arrive at the login page, but when it's loaded containing the variable `$loginLinkSent`, it will just show a success message.

And we're all set :). GIVE IT A TRY!

#### 2. UNLOQ method - Mobile app Push Notifications
Enable authenticated user to login using Push notifications and UNLOQ mobile app.

In order to do this, let's add an Authentication settings page where the logged user can chose his own UNLOQ method to login.

Let's update the layout view for the application, and add the link to the Authentication settings page:

>resources/views/layouts/app.blade.php
 
add the following lines just above the Log out link :

```php
<li>
    <a href="{{ route('unloq-settings') }}">Auth</a>
</li>
```

next let's add to the routes file the route above and also the route to save UNLOQ settings:

>routes/web.php
 
```php
Route::get('/unloq/settings', 'Unloq\UnloqController@showUnloqSettingsForm')->name('unloq-settings');
Route::post('/unloq/settings', 'Unloq\UnloqController@saveUnloqSettings');
```

Now all we need is a controller and view 

> app/Http/Controllers/Unloq/UnloqController.php

> resources/views/unloq/settings.blade.php

let's put some code inside the controller in order to :

- be accessible only to the logged users;
- render the form where the authentication settings will be displayed;
- store the UNLOQ authentication settings;
- while choosing any other method beside **EMAIL**, we need to check if the user has synchronized any device.


```php
namespace App\Http\Controllers\Unloq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Unloq\Api\Contracts\Approval\Authenticate;
use Unloq\Api\Contracts\Enrollment\Enroll;
use Unloq\Unloq;

class UnloqController extends Controller
{
    public function __construct()
    {
        // we make sure this is only accessible
        // if the user is authenticated
        $this->middleware('auth');
    }

    public function showUnloqSettingsForm()
    {
        return view('unloq.settings');
    }

    public function saveUnloqSettings(Request $request)
    {
        $this->validate($request, [
            'unloq_method' => 'in:EMAIL,UNLOQ,OTP'
        ]);

        $user = Auth::user();
        $method = $request->input('unloq_method');

        if($method == 'UNLOQ' && !$this->userHasDevicePaired($user->email))
            return redirect('/unloq/pair');

        $user->unloq_method = $method;
        $user->save();

        $request->session()->flash('unloqSettings', 'Authentication settings saved!');

        return view('unloq.settings');
    }

    protected function userHasDevicePaired($email)
    {
        $unloqClient = new Unloq(env('UNLOQ_API_KEY'));

        $payload = new Enroll();
        $payload->setEmail($email);

        $response = $unloqClient->isEnrolled($payload);

        if($response->httpCode === 200 && isset($response->responseMessage->result))
            if($response->responseMessage->result->has_device === true)
                return true;

        return false;
    }
}
```

and add the following code to the view:

```php
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Authentication options</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form>
                            <fieldset class="form-group">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="unloq_method" value="EMAIL" {{ Auth::user()->unloq_method === 'EMAIL' ? 'checked' : '' }}>
                                                Email login (default)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="unloq_method" value="UNLOQ" {{ Auth::user()->unloq_method === 'UNLOQ' ? 'checked' : '' }}>
                                                Push notifications to your UNLOQ mobile app
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <hr>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

```
Now let's update the database to store the method that the user wants to use :

```php
php artisan make:migration add_unloq_method_to_users
```

and fill it with the code below

```php
public function up()
{
    Schema::table('users', function ($table) {
        $table->string('unloq_method', 24)->default('EMAIL');
    });
}

...

public function down()
{
    Schema::table('users', function ($table) {
        $table->dropColumn('unloq_method');
    });
}
```

now just update the database:
```php
php artisan migrate
```

In order to use any other authentication method but **Email login**, the user has to:
- download the UNLOQ Mobile app;
- pair the mobile app with our web application;

Let's a new route
```php
Route::get('/unloq/pair', 'Unloq\UnloqController@showUnloqPairForm');
```

update our UnloqController.php with a new method:

```php
public function showUnloqPairForm()
{
    $unloqClient = new Unloq(env('UNLOQ_API_KEY'));

    $payload = new Enroll();
    $payload->setEmail(Auth::user()->email);

    $response = $unloqClient->enroll($payload);

    if($response->httpCode === 200 && isset($response->responseMessage->result))
        $qrCodeUrl = $response->responseMessage->result->qr_url;
    else
        $qrCodeUrl = false;

    return view('unloq.pair')->with([
        'qrCodeUrl' => $qrCodeUrl
    ]);
}
```

and a new view 

> resources/views/unloq/pair.blade.php

with the following code:

```php
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Pair device</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($qrCodeUrl)
                            <img src="{{$qrCodeUrl}}" alt="UNLOQ QR Image" class="img-thumbnail">
                            <p>Download UNLOQ Mobile app and scan the QR Code</p>
                            <a href="{{route('unloq-settings')}}">I paired my device</a>
                        @else
                            <p>There was an error while retrieving the QR Image, please refresh the page.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```

This will display the QR image you require for the logged user to synchronize his device.
 
All we need now is to update the login method to:

 - check and see what authentication method has the current user set for his account;
 - depending on the method chosen, make the appropriate authentication request to UNLOQ Api;
 
```php
...
use App\User;
use Auth;
...
public function login(Request $request)
{
    ...

    // we override with the flow below default authentication
    $email = $request->input('email');
    $user = User::where(['email' => $email])->first();

    $authenticate = (new UnloqAuthController)->authenticate($email, $user->unloq_method);

    // if the request is successful, we are going to reload
    // the login page with a variable set to true, which will
    // determine the output of the login page
    if ($authenticate->httpCode === 200) {
        if($user->unloq_method === 'UNLOQ'){
            Auth::login($user);
            return redirect('/home');
        }

        return view('auth.login')->with([
            'loginLinkSent' => true
        ]);
    }

    $this->incrementLoginAttempts($request);

    //return $this->sendFailedLoginResponse($request);

    // if anything goes wrong with our authentication request,
    // we just throw back the error that occurred
    throw ValidationException::withMessages([
        $this->username() => $authenticate->errorMessage,
    ]);
}
```

Now all you need is to **give it a try and enjoy** your passwordless Laravel application.

## Implement UNLOQ as second factor + Managing authentication options

You can follow up with the next tutorial ```larave-second-factor-authentication.md``` and learn how to :
- build and administrative interface to manage authentication options for the users;
- re-enable password but only to enforce better authentication through UNLOQ as second factor of authentication.

<a name="references"></a>
## References & Thanks
[@Laravel](https://laravel.com/) for a great [Documentation](https://laravel.com/docs/5.5).

[@Christopher Thomas](https://github.com/cwt137) for a great [2fa Laravel Tutorial](https://www.sitepoint.com/2fa-in-laravel-with-google-authenticator-get-secure/).

[@UNLOQ team](https://www.unloq.io) for helping me bring value to the current documentation.