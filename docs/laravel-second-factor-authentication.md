UNLOQ PHP SDK - Laravel second factor authentication + + Managing authentication options
========================================================================================
This tutorial is a followup on the first one ```laravel-passwordless-authentication.md``` and will cover:

- [Building an administrative interface](#administrative-interface)
- [Managing Authentication Settings](#manage-authentication-settings)
- [Authentication with the enabled options](#authentication-with-the-enabled-options). Updating:
    - [Login flow](#login-flow)
    - [Registration flow](#registration-flow)
    - [User's authentication options](#user-authentication-options)
<a name="admin-auth-settings"></a>

<a name="administrativeinterface"></a>
### Administrative interface
Let's build the administrative interface first.

We'll be updating the database structure, by adding a ```role_id``` column to the ```users``` table, so we can differentiate regular by admin users.

Let's create the migration:

```php
php artisan make:migration add_role_id_to_users
```

add the code to update the table structure:

```php
public function up()
    {
        Schema::table('users', function ($table) {
            $table->tinyInteger('role_id')->default(1);
        });
    }
    
    ...
    
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('role_id');
        });
    }
```

and run the migration to update the database structure 

```php php artisan migrate```

Let's set the routes for admin as a group of routes to which we'll apply the same middleware

> routes/web.php
 
```php
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin']
],
    function(){
        Route::get('/', 'Admin\AdminController@index')->name('admin-dashboard');
        
        Route::get('/settings', 'Admin\AdminController@settings')->name('admin-settings');
        
        Route::get('/unloq/firewall', 'Admin\UnloqController@firewall')->name('admin-authentication-settings');
        
        Route::get('/unloq/notifications', 'Admin\UnloqController@notifications')->name('admin-authentication-settings');
        
    }
);
```

while we have the ```auth``` middleware, we need to create and register a middleware called ```admin```. This middleware will simply only allow admin users within ```/admin/*``` routes.

```bash
php artisan make:middleware Admin
```

add the following code to the new Middleware created

> app/Http/Middleware/Admin.php

```php
namespace App\Http\Middleware;

use Closure;
use Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::user()->isAdmin()){
            if($request->ajax())
                return response()->json(['error' => 'You are not authorized to use this resource.'], 401);

            return redirect('/login');
        }

        return $next($request);
    }
}
```

let's register it by adding it to 

> app/Http/Kernel.php

```php
protected $routeMiddleware = [
        ...
        'admin' => \App\Http\Middleware\Admin::class,
    ];
```
and now let's seed an admin user

```bash
php artisan make:seeder AdminUsersSeeder
```
add the magic code
```php
public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin user',
            'email' => 'florin@unloq.io',
            'password' => bcrypt('myAdminUserPassword'),
            'role_id' => 0
        ]);
    }
```
**VERY IMPORTANT** use a valid email address for your admin, as at this point you can only login through UNLOQ with your email.

Update the file

> DatabaseSeeder.php
  
```php
public function run()
{
    ...
    $this->call(AdminUsersSeeder::class);
}
```

so we can now trigger the seeds and populate the database with our admin user

```bash
php artisan db:seed
```

We have specified the role_id in the seeder, as we need a user who's role_id is equal to zero. That will help us identify the user as being an admin. That through Admin middleware created earlier and also during login.

The other users that will register, will automatically have the role_id equal to 1 as when we've update the database, we've set a default value of 1 for this column.

Now that we have everything that we require, let's take care of the login.

Update 

> app/Http/Controllers/Auth/LoginController.php

by adding the following :

```php
/**
 * Returns the path where to redirect 
 * users after a successful login
 * 
 * @return string
 */
protected function redirectTo()
{
    // default path for regular users
    $path = '/home';
    
    // if the user is of type admin, we'll
    // redirect them to their own dashboard
    if(Auth::user()->isAdmin())
        $path = '/admin';
    
    return $path;
}
```

and updated the ```login()``` method by changing

```php
if($user->unloq_method === 'UNLOQ'){
    Auth::login($user);
    return redirect('/home');
}
```

to 

```php
if($user->unloq_method === 'UNLOQ'){
    Auth::login($user);
    return redirect($this->redirectTo());
}
```

You can remove the protected property ```$redirectTo``` as the newly added method ```redirectTo()``` will override it.

We also need to add the correct redirect to: 

> app/Http/Controllers/Unloq/UnloqAuthController.php

where we need to change from ```email()``` method the following line:

```php
return redirect('/home');
```

to
 
```php
return redirect($user->isAdmin() === true ? '/admin' : '/home');
```

And now when we login with our admin user, we'll be redirected to ```/admin```.

We'll also need to add the ```isAdmin()``` method to

> app/User.php

```php
/**
 * Checks if the user is administrator
 * 
 * @return bool
 */
public function isAdmin()
{
    if($this->role_id === 0)
        return true;

    return false;
}
```
Next step is to create the dashboard for admin. Create the file

> app/Http/Controllers/Admin/AdminController.php

and add the following code :

```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Show the administrative dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home');
    }
}
```

and add the adjacent view 

> resources/views/admin/home.blade.php

with the following code:

```php
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Administrative dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Welcome {{ Auth::user()->name }}!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

and as we'll want a different layout for an admin user just duplicate

> resources/views/layouts/app.blade.php

to 

> resources/views/layouts/admin.blade.php

and for now let's just update the title and branding to :

```bash
<title>Laravel - Admin</title>

...

 <!-- Branding Image -->
<a class="navbar-brand" href="{{ url('/admin') }}">
    Laravel - Admin
</a>
```

And now give it a try. Login with your admin user and get to your administrative dashboard.

<a name="authsettings"></a>
### Manage Authentication Settings
Passwordless/Multifactor/Password Authentication. Enable for your users different authentication methods.

At this point we'll implement a page where the admin user can enable the authentication options for users:
- Password and UNLOQ; 
- UNLOQ only.

Let's use the ```/settings``` route that we've previously added in our routes file and create a settings page.

Though there are better ways to store application settings, for the purpose of this tutorial we'll store them in the database.

First let's create a Model and migration for a DB table where to store our application settings with some default values:

```bash
php artisan make:model Settings -m
php artisan make:seeder SettingsTableSeeder
```

add the following code to the ```settings``` migration file

> database/migrations/..._create_settings_table.php
```php
public function up()
{
    Schema::create('settings', function (Blueprint $table) {
        $table->string('name');
        $table->string('value');
        $table->timestamps();
    });
}

...

public function down()
{
    Schema::dropIfExists('settings');
}
```

and the following to the seeder file

> database/seeds/SettingsTableSeeder.php

```php
public function run()
{
    DB::table('settings')->insert([
        'name' => 'auth_method',
        'value' => 'UNLOQ',
    ]);

    DB::table('settings')->insert([
        'name' => 'unloq_method',
        'value' => 'EMAIL',
    ]);
}
```
Now you need to add your seeder here

> database/seeds/DatabaseSeeder.php 

```php
public function run()
{
    $this->call(AdminUsersSeeder::class);
    $this->call(SettingsTableSeeder::class);
}
```

and run the migrations alongside with seeding
```bash
php artisan migrate
php artisan db:seed --class=SettingsTableSeeder
```


Let's now take care of the views and backend method that will allow an admin to set how the authentication is done within the system.

Let's add the following code to 

> app/Http/Controllers/Admin/AdminController.php

```php
use App\Settings;

...

public function settings()
{
    $settings = Settings::all();
    $data = [];

    foreach ($settings as $setting){
        $data[$setting->name] = $setting->value;
    }

    return view ('admin.settings')->with($data);
}

public function updateSettings(Request $request)
{
    $settings = $request->all();

    foreach ($settings as $name => $value) {
        if(is_array($value))
            $data = ['value' => implode(',', $value)];
        else
            $data = ['value' => $value];

        Settings::where('name', $name)
            ->update($data);
    }

    return back();
}
```

add a new view

> resources/views/admin/settings.blade.php

```php
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Application settings</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                        <ul class="nav nav-tabs" id="myTabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#authentication" id="home-tab" role="tab" data-toggle="tab" aria-controls="authentication" aria-expanded="true">Authentication</a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#unloq" role="tab" id="profile-tab" data-toggle="tab" aria-controls="unloq" aria-expanded="false">UNLOQ</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" role="tabpanel" id="authentication" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <br>
                                        <form  action="" method="post">
                                            {{ csrf_field() }}
                                            <fieldset class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="radio" name="auth_method" value="PASS.UNLOQ" {{ $auth_method == 'PASS.UNLOQ' ? 'checked' : '' }}>
                                                                Password & UNLOQ as second factor
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="radio" name="auth_method" value="UNLOQ" {{ $auth_method == 'UNLOQ' ? 'checked' : '' }}>
                                                                UNLOQ
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
                            <div class="tab-pane fade" role="tabpanel" id="unloq" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <br>
                                        <form  action="" method="post">
                                            {{ csrf_field() }}
                                            <fieldset class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" name="unloq_method[]" value="EMAIL" {{ strpos($unloq_method, 'EMAIL') !== false? 'checked' : '' }}>
                                                                Email
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" name="unloq_method[]" value="UNLOQ" {{ strpos($unloq_method, 'UNLOQ') !== false ? 'checked' : '' }}>
                                                                Push notifications
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" name="unloq_method[]" value="OTP" {{ strpos($unloq_method, 'OTP') !== false ? 'checked' : '' }}>
                                                                OTP (token)
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

and add a new route inside the 'admin' route group :

```php
Route::post('/settings', 'Admin\AdminController@updateSettings')->name('admin-settings-update');
```

With this we're all set to view and store our authentication settings in the database.

Now we "just" need to enforce the settings within our system :)

<a name="authentication-with-the-enabled-options"></a>
## Authentication with the enabled options

### Login flow

We need to update the login flow as follows:
1. If "Password and UNLOQ as second factor" authentication method is used:
- show the password field in the login screen;
- validate the users's credentials and validate his request depending on: 
    - the admin enabled UNLOQ authentication options;
    - the UNLOQ authentication option chosen by the user;
    
2. If UNLOQ is used ... well we already have that in place, don't we :) ? 
- we just need to check 
    - the admin enabled UNLOQ authentication options;
    - the UNLOQ authentication option chosen by the user;
    
Let's make it happen!

Update in 

> app/Http/Controllers/Auth/LoginController.php

the ```login()``` to be able to handle the authentication option set by admin

```php
use App\Settings;

...

public function login(Request $request)
{
    $systemAuthMethod = Settings::where('name', 'auth_method')->first();
    $systemUnloqMethod = Settings::where('name', 'unloq_method')->first();

    $this->validateLogin($request, $systemAuthMethod->value);

    if ($this->hasTooManyLoginAttempts($request)) {
        $this->fireLockoutEvent($request);

        return $this->sendLockoutResponse($request);
    }

    if($systemAuthMethod->value == 'PASS.UNLOQ'){
        // 'Password & UNLOQ as second factor' authentication method
        // is used we trigger the validation of credentials first
        if(!$this->attemptLogin($request)){
            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }
    }

    // then we continue the authentication flow through UNLOQ
    $email = $request->input('email');
    $user = User::where(['email' => $email])->first();

    // if the user's chosen UNLOQ method is enabled by the admin user
    // we'll use that one, else we'll use the first admin enabled option
    if(strpos($systemUnloqMethod->value, $user->unloq_method) !== false)
        $unloqMethod = $user->unloq_method;
    else
        $unloqMethod = explode(',', $systemUnloqMethod->value)[0];

    $authenticate = (new UnloqAuthController)->authenticate($email, $unloqMethod);

    // if the request is successful, we are going to reload
    // the login page with a variable set to true, which will
    // determine the output of the login page
    if ($authenticate->httpCode === 200) {
        if($user->unloq_method === 'UNLOQ'){
            Auth::login($user);
            return redirect($this->redirectTo());
        }

        return view('auth.login')->with([
            'loginLinkSent' => true
        ]);
    }

    $this->incrementLoginAttempts($request);

    // if anything goes wrong with our authentication request,
    // we just throw back the error that occurred
    throw ValidationException::withMessages([
        $this->username() => $authenticate->errorMessage,
    ]);
}
```

the ```validateLogin()``` method to validate password field if it's the case:

```php
protected function validateLogin(Request $request, $authMethod)
{
    $data = [
        $this->username() => 'required|string|exists:users',
    ];

    // we only require and validate a password if 'Password & UNLOQ
    // as second factor' authentication method is used
    if($authMethod == 'PASS.UNLOQ')
        $data['email'] = 'required|string';

    $this->validate($request, $data);
}
```
Now what we need is to render the login page with a password field when ```PASS.UNLOQ``` method is used for login.

For that we need to go grab from ```AuthenticatesUsers```  the method ```showLoginForm()``` and override it and send the system authentication method:

```php
public function showLoginForm()
{
    return view('auth.login', [
        'systemAuthMethod' => Settings::where('name', 'auth_method')->first()->value
    ]);
}
```

The login page is now ready to handle either of the authentication options that an admin sets.

### Registration flow
Remember we've started this as a passwordless system? Where registration & login is done just through email.
 
An account who registered through the passwordless registration process, won't have a valid password to use. So let's update the registration and store the password.

> app/Http/Controllers/Auth/RegisterController.php

Uncomment your code related to password or just paste the code below:

```php
protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);
}
```

```php
protected function create(array $data)
{
    return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
    ]);
}
```

and let's uncomment or just paste the code below, to re-enable password field in the form from the registration page

> resources/views/auth/login.blade.php

```php
<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    <label for="password" class="col-md-4 control-label">Password</label>

    <div class="col-md-6">
        <input id="password" type="password" class="form-control" name="password" required>

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group">
    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

    <div class="col-md-6">
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
    </div>
</div>
```

The registration flow will work just as before and now the registered user will also have a password set.

<a name="user-authentication-options"></a>
### User authentication options

We just need one more adjustment at user level to bring him at correct level of the current app authentication options.

He needs to be able to update his password and also choose he UNLOQ option he wants from the list of the ones that are enabled by the admin.

Let's update the code as follows:

> app/Http/Controllers/Unloq/UnloqController.php

```php
use App\Settings;

...

public function showUnloqSettingsForm()
{
    return view('unloq.settings', [
        'systemAuthMethod' => Settings::where('name', 'auth_method')->first()->value,
        'systemUnloqMethod' => Settings::where('name', 'unloq_method')->first()->value,
    ]);
}

...

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

    $password = $request->input('password');
    if(isset($password))
        $user->password = bcrypt($password);

    $user->save();

    $request->session()->flash('unloqSettings', 'Authentication settings saved!');

    return redirect(route('unloq-settings'));
}
```

> resources/views/unloq/settings.blade.php


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

                        <form action="" method="post">
                            {{ csrf_field() }}
                            <fieldset class="form-group">
                                <div class="row">

                                    <div class="col-sm-12">
                                        <p><strong>Current auth method: </strong>{{ $systemAuthMethod === 'PASS.UNLOQ' ? 'Password and UNLOQ as second factor' : 'UNLOQ' }}</p>
                                        <hr>
                                    </div>
                                    <div class="col-sm-12">
                                        <p><strong>Available UNLOQ auth options</strong></p>
                                        @if(strpos($systemUnloqMethod, 'EMAIL') !== false)
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="unloq_method" value="EMAIL" {{ Auth::user()->unloq_method === 'EMAIL' ? 'checked' : '' }}>
                                                    Email login (default)
                                                </label>
                                            </div>
                                        @endif
                                        @if(strpos($systemUnloqMethod, 'UNLOQ') !== false)
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="unloq_method" value="UNLOQ" {{ Auth::user()->unloq_method === 'UNLOQ' ? 'checked' : '' }}>
                                                    Push notifications to your UNLOQ mobile app
                                                </label>
                                            </div>
                                        @endif
                                        <hr>
                                    </div>

                                    <div class="col-sm-6">
                                        <p><strong>Update password</strong></p>
                                        <input id="password" type="password" class="form-control" name="password">
                                    </div>
                                </div>
                            </fieldset>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```


<a name="devfirewall"></a>
#### Firewall
ALLOW/DENY authentication to your system from different IP addresses OR ranges. CIDR rules apply.

IN PROGRESS

<a name="devnotifications"></a>
#### Notifications
Send push notifications to all users of your system to notify them of most urgent news.

IN PROGRESS