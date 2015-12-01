<?php

class AuthController extends Controller {

	public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
    }

	public function postLogin()
	{
		$rules = [
            'email' => 'required|exists:users',
            'password' => 'required'
        ];

        $input = Input::only('email', 'password');

        $validator = Validator::make($input, $rules);

        if($validator->fails())
        {
            return Redirect::back()->withInput(Input::except('password'))->withErrors($validator, 'login');
        }

        $remember_me = false;
		if (Input::get('remember'))
		{
			$remember_me = true;
		}

		$credentials = [
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'confirmed' => 1
        ];

        if ( ! Auth::attempt($credentials, $remember_me))
        {
            return Redirect::back()
                ->withInput(Input::except('password'))
                ->withErrors([
                    'credentials' => 'We were unable to log you in. Verify that your email/password are correct, and that you have verified your account.'
                ], 'login');
        }
        else
        {
        	return Redirect::back();
        }
	}

	public function postRegister()
	{
		$input = Input::all();

		require_once app_path() . '/lib/recaptchalib.php';

		$config = include app_path() . '/config/recaptcha.php';
		$secret = $config['privateKey'];

		if (!$input['g-recaptcha-response'])
		{
			return Redirect::back()
                ->withInput(Input::except('password'))
                ->withErrors([
                    'captcha' => 'The reCAPTCHA was\'t entered correctly. Please try again.'
                ], 'register');
		}
		else
		{
			$reCaptcha = new ReCaptcha($secret);

		    $resp = $reCaptcha->verifyResponse(
		        $_SERVER['REMOTE_ADDR'],
		        $input['g-recaptcha-response']
		    );

		    if ($resp == null || !$resp->success)
		    {
		    	return Redirect::back()
	                ->withInput(Input::except('password'))
	                ->withErrors([
	                    'captcha' => 'The reCAPTCHA was\'t entered correctly. Please try again.'
	                ], 'register');
		    }
		}

		$input['email'] = strtolower($input['email']);

		$rules = array(
			'email' => 'required|unique:users,email',
			'password' => 'required|min:6|confirmed',
	        'password_confirmation' => 'required|min:6',
		);

		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {
		    return Redirect::back()
		        ->withErrors($validator, 'register')
		        ->withInput(Input::except('password'));
		}

		$confirmation_code = str_random(30);

		$social_link_1 = Input::get('social_link_1', '');
		if ($social_link_1 && strpos(strtolower($social_link_1), 'http://') === false && strpos(strtolower($social_link_1), 'https://') === false)
		{
			$social_link_1 = 'http://'.$social_link_1;
		}
		$social_link_2 = Input::get('social_link_2', '');
		if ($social_link_2 && strpos(strtolower($social_link_2), 'http://') === false && strpos(strtolower($social_link_2), 'https://') === false)
		{
			$social_link_2 = 'http://'.$social_link_2;
		}

		User::create(
			array(
				'email' => Input::get('email'),
				'password' => Hash::make(Input::get('password')),
				'role_id' => '3',
				'first_name' => Input::get('first_name'),
				'last_name' => Input::get('last_name'),
				'city' => Input::get('city'),
				'state' => Input::get('state'),
				'zip' => Input::get('zip'),
				'organization' => Input::get('organization'),
				'bio' => Input::get('bio', ''),
				'role_title' => serialize(Input::get('role')),
				'role_title_other' => Input::get('role_title_other'),
				'photo' => Input::get('photo', 'default_profile.png'),
				'background_image' => Input::get('background_image'),
				'confirmation_code' => $confirmation_code,
				'share_email' => Input::get('share_email', 0),
				'social_link_1' => $social_link_1,
				'social_link_2' => $social_link_2
			));

		Mail::queue('emails.auth.welcome', array('firstname'=>Input::get('first_name'),'confirmation_code'=>$confirmation_code), function($message){
	        $message->to(strtolower(Input::get('email')), Input::get('first_name').' '.Input::get('last_name'))->subject('Welcome to NetworkEd!');
	    });

		$user = User::where('email', '=', $input['email'])->first();

		return View::make('auth.registered');
	}

	public function getVerify($confirmation_code)
	{
		if( ! $confirmation_code)
        {
            return View::make('auth.verified')
            			->withErrors(array('No confirmation code specified.'));
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if ( ! $user)
        {
            return View::make('auth.verified')
            			->withErrors(array('Confirmation code not found.'));
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        return View::make('auth.verified');
	}

	public function postUpdate()
	{
		if (Auth::check())
		{
			$user = Auth::user();

			$input = Input::all();

			$input['email'] = strtolower($input['email']);

			//if this is a different email, make sure another user isn't already using it
			if ($input['email'] != $user->email)
			{
				$rules = array(
					'email' => 'required|unique:users,email',
				);

				$validator = Validator::make($input, $rules);

				if ($validator->fails()) {
				    return Redirect::route('profile', array('user_id' => $user->id))
				        ->withInput(Input::except('password'))
				        ->withErrors($validator, 'profileupdate');
				}
			}

			//validate new password, if there is one
			if (Input::get('password') != '')
			{
				$rules = array(
					'password' => 'required|min:6|confirmed',
			        'password_confirmation' => 'required|min:6',
				);

				$validator = Validator::make($input, $rules);

				if ($validator->fails()) {
				    return Redirect::route('profile', array('user_id' => $user->id))
				        ->withInput(Input::except('password'))
				        ->withErrors($validator, 'profileupdate');
				}
			}

			$user->email = $input['email'];

			if (Input::get('password') != '')
			{
				$user->password = Hash::make(Input::get('password'));	
			}
			
			$user->first_name = Input::get('first_name');
			$user->last_name = Input::get('last_name');
			$user->city = Input::get('city');
			$user->state = Input::get('state');
			$user->zip = Input::get('zip');
			$user->organization = Input::get('organization');
			$user->bio = Input::get('bio', '');
			$user->role_title = serialize(Input::get('role'));
			$user->role_title_other = Input::get('role_title_other');
			$user->photo = Input::get('photo', 'default_profile.png');
			$user->background_image = Input::get('background_image');
			$user->share_email = Input::get('share_email', 0);

			$social_link_1 = Input::get('social_link_1', '');
			if ($social_link_1 && strpos(strtolower($social_link_1), 'http://') === false && strpos(strtolower($social_link_1), 'https://') === false)
			{
				$social_link_1 = 'http://'.$social_link_1;
			}
			$user->social_link_1 = $social_link_1;

			$social_link_2 = Input::get('social_link_2', '');
			if ($social_link_2 && strpos(strtolower($social_link_2), 'http://') === false && strpos(strtolower($social_link_2), 'https://') === false)
			{
				$social_link_2 = 'http://'.$social_link_2;
			}
			$user->social_link_2 = $social_link_2;

			$user->save();

			return Redirect::route('profile', array('user_id' => 
				$user->id));
		}

		return Redirect::route('login');
	}

	public function getLogout()
	{
		Auth::logout();

		return Redirect::route('/');
	}

}
