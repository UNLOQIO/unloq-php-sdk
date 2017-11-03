UNLOQ PHP SDK - Laravel integration
===================================
Develop passwordless authentication for Laravel framework using UNLOQ.io Multi-factor Authentication Solution. Understand [UNLOQ in 40 seconds](https://vimeo.com/151232399).

- [Requirements](#requirements)
- [Installation and setup](#installation-and-setup)
- [Implementation](#implementation)
    - [User level](#user-level)
        - [Passwordless Authentication](#passwordless-authentication)
    - [Admin level](#admin-level)
        - [Auth Settings](#auth-settings)
        - [Organisation Details](#organisation-details)
        - [Mobile App customisation](#mobile-app-customisation)
        - [Firewall](#firewall)
        - [Notifications](#notifications)
- [References & Thanks](#references-thanks)

<a name="requirements"></a>
## Requirements
- a Smartphone with UNLOQ application installed;
- an API key from a custom web application from your UNLOQ account. You can read more on [general concerns](general.md).  
- Homestead would be great but you can adapt everything from the tutorial;
- Laravel Framework 5.5.

<a name="installation"></a>
## Installation and setup
We need to install the Laravel framework, scaffold basic auth and install UNLOQ PHP SDK. The library will enable us to take advantage of the UNLOQ API and implement passwordless Authentication.

<a name="implementation"></a>
## Implementation
This chapter will cover basic passwordless implementation as well as an administrative interface.
 
The [User level](#user-level) (basic passwordless implementation) will allow you to take immediate advantage of UNLOQ's Authentication benefits.
 
The [Admin level](#admin-level) will help you go deeper with UNLOQ, allowing you to take full advantage fo the API an create your own administrative interface. The interface will interact with most important features of UNLOQ, bringing even more security and customisation to your new improved authentication system.

<a name="devuserlevel"></a>
### User Level
<a name="devpasswordless"></a>
#### Passwordless Authentication
We'll be implementing a passwordless registration and login, for the users of the application, overriding some of the Laravel's functionalities. We'll do that in such way that later we'll be able to allow password authentication too. That in the administrative dashboard that will be building to take advantage of UNLOQ features.
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
 
- and add the method authenticate() which will enable us to authenticate users through UNLOQ:

```php
public function authenticate($email, $method = null)
{
    $unloqClient = new Unloq(env('UNLOQ_API_KEY'));

    $payload = new Authenticate();
    $payload->setEmail($email)
        ->setMethod(isset($method) ? $method : 'UNLOQ')
        ->setIp($_SERVER['REMOTE_ADDR']);

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
###### 1. EMAIL login
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
- THAT'S IT :).

###### 2. Push notification login
Enable authenticated user to login using Push notifications and UNLOQ mobile app. 

IN PROGRESS

<a name="devadminlevel"></a>
### Admin Level

<a name="authsettings"></a>
#### Auth Settings
Setup Passwordless/Multifactor/Password Authentication. Enable different UNLOQ auth methods : EMAIL, OTP, Push Notifications.

IN PROGRESS

<a name="devcustomisation"></a>
#### Mobile App Customisation
Customisation of the Mobile App look and feeling.

IN PROGRESS

<a name="devfirewall"></a>
#### Firewall
ALLOW/DENY authentication to your system from different IP addresses OR ranges. CIDR rules apply.

IN PROGRESS

<a name="devnotifications"></a>
#### Notifications
Send push notifications to all users of your system to notify them of most urgent news.

IN PROGRESS

<a name="references"></a>
## References & Thanks
@Laravel for a great [Documentation](https://laravel.com/docs/5.5).

[@Christopher Thomas](https://github.com/cwt137) for a great [2fa Laravel Tutorial](https://www.sitepoint.com/2fa-in-laravel-with-google-authenticator-get-secure/).

[@UNLOQ team](https://www.unloq.io) for helping me bring value to the current documentation.