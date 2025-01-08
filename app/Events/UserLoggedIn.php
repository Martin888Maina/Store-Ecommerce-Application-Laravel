<?php
//namespace - to avoid naming conflicts
namespace App\Events;
//importing the user model
use App\Models\User;
// Importing the serializesModels trait to convert the event object into a format that cna be stored
use Illuminate\Queue\SerializesModels;

/**
 * This event is triggered when a user logs in.
 */
class UserLoggedIn
{
    //trait
    use SerializesModels;

    /**
     * @var User The user who logged in
     */
    //holds the value of the logged in user
    public $user;

    /**
     * Constructor method to initialize the event with the user details.
     *
     * @param User $user
     */
    //initializes the user object
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
