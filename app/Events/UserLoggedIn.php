<?php
namespace App\Events;

//imports the user model
use App\Models\User;
// imports the serializesModels trait
use Illuminate\Queue\SerializesModels;


//This event is triggered when a user logs in
class UserLoggedIn
{
    //Serializes he model data when the event is triggered
    use SerializesModels;

    // declares the user object
    public $user;
    // defines the type of login event (standard or email verification)
    public $type;

    //constructor method to initialize the  event with the user details and type of login
    public function __construct(User $user, string $type = 'standard')
    {
        //set the user property
        $this->user = $user;

        //set the type property
        $this->type = $type;
    }
}

