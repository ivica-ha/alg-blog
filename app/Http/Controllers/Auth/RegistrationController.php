<?php

namespace App\Http\Controllers\Auth;

use Mail;
use Session;
use Sentinel;
use Activation;
use App\Http\Requests;
use Centaur\AuthManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Centaur\Mail\CentaurWelcomeEmail;

class RegistrationController extends Controller
{
    /** @var Centaur\AuthManager */
    protected $authManager;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(AuthManager $authManager)
    {
        $this->middleware('sentinel.guest');
        $this->authManager = $authManager;
    }

    /**
     * Show the registration form
     * @return View
     */
    public function getRegister()
    {
        return view('Centaur::auth.register');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Response|Redirect
     */
    protected function postRegister(Request $request)
    {
        // Validate the form data
        $result = $this->validate($request, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        // Assemble registration credentials
        $credentials = [
            'email' => trim($request->get('email')),
            'password' => $request->get('password'),
        ];
				
				$activation = true;

        // Attempt the registration
<<<<<<< HEAD
        $result = $this->authManager->register($credentials, true);
=======
        $result = $this->authManager->register($credentials, $activation);
>>>>>>> f508a5b5c9b58678fecb8cbee2e327f2d07e0d05

        if ($result->isFailure()) {
            return $result->dispatch();
        }

        $activation = true;

        // Send the activation email
<<<<<<< HEAD
        if(!$activation){
          $code = $result->activation->getCode();
          $email = $result->user->email;
          Mail::to($email)->queue(new CentaurWelcomeEmail($email, $code, 'Your account has been created!'));
          $message = 'Registration complete.  Please check your email for activation instructions.';
      } else {
          $role = Sentinel::findRoleBySlug('subscriber');
          if($role){
            $role->users()->attach($result->user);
          }
          $message = 'Registration complete.';
      }
=======
				if(!$activation){
					$code = $result->activation->getCode();
					$email = $result->user->email;
					Mail::to($email)->queue(new CentaurWelcomeEmail($email, $code, 'Your account has been created!'));
					$message = 'Registration complete.  Please check your email for activation instructions.';
				} else {
					$role = Sentinel::findRoleBySlug('subscriber');
					if($role){
						$role->users()->attach($result->user);
					}
					$message = 'Registration complete.';
				}
>>>>>>> f508a5b5c9b58678fecb8cbee2e327f2d07e0d05

        // Ask the user to check their email for the activation link
        $result->setMessage($message);

        // There is no need to send the payload data to the end user
        $result->clearPayload();

        // Return the appropriate response
        return $result->dispatch(route('dashboard'));
    }

    /**
     * Activate a user if they have provided the correct code
     * @param  string $code
     * @return Response|Redirect
     */
    public function getActivate(Request $request, $code)
    {
        // Attempt the registration
        $result = $this->authManager->activate($code);

        if ($result->isFailure()) {
            // Normally an exception would trigger a redirect()->back() However,
            // because they get here via direct link, back() will take them
            // to "/";  I would prefer they be sent to the login page.
            $result->setRedirectUrl(route('auth.login.form'));
            return $result->dispatch();
        }

        // Ask the user to check their email for the activation link
        $result->setMessage('Registration complete.  You may now log in.');

        // There is no need to send the payload data to the end user
        $result->clearPayload();

        // Return the appropriate response
        return $result->dispatch(route('dashboard'));
    }

    /**
     * Show the Resend Activation form
     * @return View
     */
    public function getResend()
    {
        return view('Centaur::auth.resend');
    }

    /**
     * Handle a resend activation request
     * @return Response|Redirect
     */
    public function postResend(Request $request)
    {
        // Validate the form data
        $result = $this->validate($request, [
            'email' => 'required|email|max:255'
        ]);

        // Fetch the user in question
        $user = Sentinel::findUserByCredentials(['email' => $request->get('email')]);

        // Only send them an email if they have a valid, inactive account
        if (!Activation::completed($user)) {
            // Generate a new code
            $activation = Activation::create($user);

            // Send the email
            $code = $activation->getCode();
            $email = $user->email;
            Mail::to($email)->queue(new CentaurWelcomeEmail($email, $code, 'Account Activation Instructions'));
        }

        $message = 'New instructions will be sent to that email address if it is associated with a inactive account.';

        if ($request->ajax()) {
            return response()->json(['message' => $message], 200);
        }

        Session::flash('success', $message);
        return redirect('/dashboard');
    }
}
