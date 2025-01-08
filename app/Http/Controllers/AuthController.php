<?php

//namespace for the controller
namespace App\Http\Controllers;
//handles http requests in laravel(forms)
use Illuminate\Http\Request;
//imports the auth facade - authentication
use Illuminate\Support\Facades\Auth;
//imports the hash facade - password hashing
use Illuminate\Support\Facades\Hash;
//imports the password facade - handling password reset
use Illuminate\Support\Facades\Password;
//imports the User model
use App\Models\User;
//imports the log facade for debugging
use Illuminate\Support\Facades\Log;
//imports the Cart model
use App\Models\Cart;
//imports the event class
use App\Events\UserLoggedIn;
//imports the EmailVerificationRequest class for email verification
use Illuminate\Foundation\Auth\EmailVerificationRequest;
//imports the str class for string manipulation
use Illuminate\Support\Str;
//imports the DB facade for interacting with the Database
use Illuminate\Support\Facades\DB;
//imports the PasswordReset event class 
use Illuminate\Auth\Events\PasswordReset;
//imports the email verification  class - it is triggered when a user has successfully verified their email
use Illuminate\Auth\Events\Verified;
//imports the url facade that generates signed URLS  for email verification
use Illuminate\Support\Facades\URL;


class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration process.
     */

    public function register(Request $request)
    {
        //validating the user input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get current cart data from session
        $cartData = session('cart', []);
        
        // Create user with cart data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'store_data' => json_encode($cartData)
        ]);

        //logging output in the laravel.log file
        Log::info('User created with cart data stored: ', [
            'user_id' => $user->id,
            'cart_items' => count($cartData)
        ]);

        // Send verification email
        $user->sendEmailVerificationNotification();

        // Clear the cart session after storing it
        session()->forget('cart');

        // Redirect to verification notice page
        return redirect()->route('verification.notice')->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm(Request $request)
    {
        // Store the intended URL to redirect after login
        // the intended url allows the system to track where the user was intending to go before they logged in
        if ($request->session()->has('cart')) {
            //navigates the user to the checkout page
            //intended_url is stored in the sessions
            session(['intended_url' => route('cart.display')]);
        } else {
            session(['intended_url' => url()->previous()]); 
        }

        //navigate the user to the login form
        return view('auth.login');
    }

    /**
     * Handle the login process.
     */
    public function login(Request $request)
    {

        //validate user input
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        //finds the user with the attached email
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Get the current session ID
            $currentSessionId = session()->getId();
            // Log the user in manually
            Auth::login($user);
            // Set the session ID to remain the same after login
            session()->setId($currentSessionId);

            // Check if the login is after email verification
            $loginType = $user->email_verified_at ? 'standard' : 'email_verification';

            // Fire the event
            event(new UserLoggedIn($user, $loginType));

            // Using the Cart model directly
            // User has a cart
            $cart = $user->cart()->firstOrCreate([]);

            // Transferring data from session to the Cart
            if (session()->has('cart')) {
                // Session holds the cart data
                $cartItems = session('cart'); 

                // Loop through the cart items 
                foreach ($cartItems as $itemData) {
                    // Confirm if the product exists
                    $cartItem = $cart->cartItems()->where('product_id', $itemData['product_id'])->first();
                    // Check if product is in the cart
                    if ($cartItem) {
                        // Add quantity of the product
                        $cartItem->quantity += $itemData['quantity'];
                        // Save updated cart item
                        $cartItem->save();
                    } else {
                        // Handle case where no product is found in the cart
                        // Create a new cart item
                        $cart->cartItems()->create([
                            'product_id' => $itemData['product_id'],
                            'quantity' => $itemData['quantity'],
                        ]);
                    }
                }

                // Clear the session cart after transferring
                session()->forget('cart');
            }

            // Navigate the user to the checkout page after successful login
            return redirect()->intended(route('cart.display'))->with('success', 'Login successful!');
        }

        // Return the user to the previous page with an error if login fails
        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    /**
     * Logout the user.
     */
     public function logout(Request $request)
    {
        // Get the user ID before logging out
        $userId = Auth::id();
        // Logs out the user
        Auth::logout();
        // Clear the cart for the authenticated user
        if ($userId) {
            // references the Cart Model
            // deletes all the records that matches the condition
            \App\Models\Cart::where('user_id', $userId)->delete();
        }
        // Clear session data for guest users
        $request->session()->forget('cart');

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Navigates the user to the landing page
        return redirect('/')->with('success', 'Logged out successfully.');
    }

     /**
     * Show the form to request a password reset link.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.passwords.letter');
    }

    /**
     * Handle sending the password reset link email.
     */

     public function sendResetLinkEmail(Request $request)
    {
        //validates the email address input from the user
        $request->validate([
            'email' => 'required|email',
        ]);

        // logging the output in the laravel.log file
        Log::info('Password reset request for email: ' . $request->email);

        // gets the user associated with the email from the database
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // logging output in the laravel.log file
            Log::info('User found for email: ' . $request->email);

            // Get current cart data from session
            $cartData = $request->session()->get('cart', []);
            // logging output in the laravel.log file
            Log::info('Cart data for email ' . $request->email . ': ' . json_encode($cartData));

            // Laravel inbuilt handling of token creation and storage
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                // After the reset link is sent, get the token from the database
                $tokenRecord = DB::table('password_reset_tokens')
                    ->where('email', $user->email)
                    ->first();

                    // logging output in the laravel.log file
                    Log::info('Reset link generated with URL: ' . url('reset-password/' . $tokenRecord->token));
                    Log::info('Token stored in database: ' . $tokenRecord->token);

                // Store the cart data alongside the existing token
                if ($tokenRecord) {
                    DB::table('password_reset_tokens')
                        ->where('email', $user->email)
                        //update the token with the cart data
                        ->update([
                            //store the cart data as a json string
                            'cart_data' => json_encode($cartData)
                        ]);

                        //logging output in the laravel.log file
                    Log::info('Cart data stored with existing reset token');
                }

                // logging output in the laravel.log file
                Log::info('Password reset link successfully sent to: ' . $request->email);
                //returns the error message
                return back()->with('status', trans($status));
            }

            //logging output in the laravel.log file
            Log::error('Failed to send password reset link for email: ' . $request->email);
            //return back the error message
            return back()->withErrors(['email' => trans($status)]);
        }

        // logging output in the laravel.log file
        Log::warning('No user found for email: ' . $request->email);
        // returns back the error message
        return back()->withErrors(['email' => 'We could not find a user with that email address.']);
    }
    

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request, $token)
    {

        //getting the email from the query
        $email = $request->query('email');

        //getting the token from the database for the email
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        //checks if the token record exists and matches the hashed 
        if (!$tokenRecord || !Hash::check($token, $tokenRecord->token)) {
            //logging output in the laravel.log file
            Log::info('No token record found for token: ' . $token);
            //redirects the user back to the password request page with an error message
            return redirect()->route('password.request')->withErrors(['token' => 'Invalid or expired token.']);
        }

        // Proceed to show the reset form
        // passes the token and the email to the password reset page
        return view('auth.passwords.reset', ['token' => $token, 'email' => $email]);
    }


    /**
     * Handle resetting the password.
     */
    public function reset(Request $request)
    {
        //validates the incoming request
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        //logs the output in the laravel.log file
        Log::info('Starting password reset process', [
            'email' => $request->email,
            'token' => $request->token,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        //finds the user by the email
        $user = User::where('email', $request->email)->first();

        //checks if the new password is same as the current password
        if ($user && Hash::check($request->password, $user->password)) {
            Log::warning('Attempt to reuse the current password', [
                'email' => $request->email
            ]);
            //returns an error message
            return back()->withErrors([
                'password' => 'The new password cannot be the same as your current password.'
            ]);
        }

        // gets the token record for the email
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // validates the token and email combination
        if (!$tokenRecord || !Hash::check($request->token, $tokenRecord->token)) {
            Log::error('Invalid token or email combination', [
                'email' => $request->email,
                'submitted_token' => $request->token,
                'stored_token' => $tokenRecord ? $tokenRecord->token : 'No token found'
            ]);
            // returns with an error message
            return back()->withErrors(['email' => 'Invalid token or email combination.']);
        }

        // logging output in the laravel.log file
        Log::info('Token validation successful', [
            'email' => $request->email
        ]);

        // initialize cart data variable
        // to allow us to use the cartData variable and prevent the undefined error message
        $cartData = null;
        //checks if cart data exists in the token record
        if (isset($tokenRecord->cart_data)) {
            //decode the token
            $cartData = json_decode($tokenRecord->cart_data, true);
            //logging output in the laravel.log file
            Log::info('Retrieved cart data before reset', ['cart_data' => $cartData]);
        }

        // this is where the password reset takes place
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request, $cartData) {
                //logging output in the laravel.log file
                Log::info('Resetting password for user', ['user_id' => $user->id]);

                //updates the users password and remember token
                //forcefill securely updates critical user information and  bypasses laravel's mass assignment protection in the model file
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                //restores the cart data to the session if available
                if ($cartData) {
                    session(['cart' => $cartData]);
                    //logging output in the laravel.log file
                    Log::info('Restored cart data to session', ['cart_data' => $cartData]);
                }

                //triggers the password reset token
                event(new PasswordReset($user));
            }
        );

        //checks the status of the password reset process
        if ($status === Password::PASSWORD_RESET) {
            //logging output
            Log::info('Password reset successful for user', ['email' => $request->email]);
            //redirects the user to the login page
            return redirect()->route('login')
                ->with('status', 'Your password has been reset! Your cart has been restored.');
        }

        //displays error message if password reset process fails
        Log::error('Password reset failed', ['email' => $request->email]);
        return back()->withErrors(['email' => trans($status)]);
    }

    /**
     * Handle the email verification process.
     */
    public function verifyEmail(Request $request, $id)
    {
        //find the user by id
        $user = User::findOrFail($id);

        //logging output in the laravel.log file
        Log::info('Starting email verification process', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'verification_status' => $user->hasVerifiedEmail() ? 'already verified' : 'pending verification'
        ]);

        try {
            // Verify that the URL is a valid signed URL
            if (!URL::hasValidSignature($request)) {
                //logging the user_id and request_url
                Log::error('Invalid signature for verification URL', [
                    'user_id' => $user->id,
                    'request_url' => $request->fullUrl()
                ]);
                //redirects the user to login page if verification link fails
                return redirect()->route('login')
                    ->with('error', 'Invalid verification link.');
            }

            //checks if the user's email has been verified
            if (!$user->hasVerifiedEmail()) {
                //marks the user as verified
                $user->markEmailAsVerified();
                // triggers the verified event that confirms that the user emails is verified. It is a aravel inbuilt mechanism
                event(new Verified($user));
                
                // Restore cart data by decoding the session data
                $currentCart = json_decode($user->store_data, true);
                // if the cart exists the restore the cart data
                if ($currentCart) {
                    session(['cart' => $currentCart]);
                    Log::info('Cart data restored for user', [
                        'user_id' => $user->id,
                        'cart_items_count' => is_array($currentCart) ? count($currentCart) : 0
                    ]);
                }

                //logging output in the laravel.log file
                Log::info('Email verification completed successfully', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'verification_timestamp' => now()->toDateTimeString()
                ]);

                //redirects the user to the login page after successful email verification
                return redirect()->route('login')
                    ->with('verified', true)
                    ->with('status', 'Your email has been verified! Please login to access your account.');
            }

            //logging output in the laravel.log file
            Log::info('Email already verified for user', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'original_verification_date' => $user->email_verified_at
            ]);

            //redirects the user to the login page
            return redirect()->route('login')
                ->with('status', 'Email already verified. Please login to continue.');

        } catch (\Exception $e) {
            //logging error message in the laravel.log file
            Log::error('Verification failed', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            //redirects the user to the login page
            return redirect()->route('login')
                ->with('error', 'There was a problem verifying your email. Please try again.');
        }
    }  

}





 