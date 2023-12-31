<?php


namespace App\Http\Controllers\Api\Individuals;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiTrait;
use App\Traits\Media;

class UserController extends Controller
{
    use ApiTrait, Media; 
    
    public function getAllUsers()
    {
    try {
        // Retrieve all users
        $users = User::all();

        // You can customize the data you want to return for each user
        $userData = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'imageUrl' => $user->imageUrl,
                'track' => $user->track,
            ];
        });

        return $this->data(['users' => $userData], 'All users retrieved successfully', 200);
    } catch (\Exception $e) {
        return $this->errorMessage([], 'Failed to retrieve users', 500);
    }
    }


    public function getUserById($id)
    {
      try {
        // Find the user by their ID
        $user = User::find($id);

        if (!$user) {
            return $this->errorMessage([], 'User not found', 404);
        }

        // Check if the user has a profile
        if ($user->profile) {
            $profile = $user->profile;
        } else {
            // Set profile properties to null or default values
            $profile = (object)[
                'governate' => null,
                'university' => null,
                'faculty' => null,
                'birthDate' => null,
                'emailProfile' => null,
                'phoneNumber' => null,
                'projects' => null,
                'progLanguages' => null,
                'cvUrl' => null,
                'githubUrl' => null,
                'linkedinUrl' => null,
                'behanceUrl' => null,
                'facebookUrl' => null,
                'twitterUrl' => null,
            ];
        }

        // You can customize the data you want to return here
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'imageUrl' => $user->imageUrl,
            'track' => $user->track,
            'bio' => $user->bio,
            'governate' => $profile->governate,
            'university' => $profile->university,
            'faculty' => $profile->faculty,
            'birthDate' => $profile->birthDate,
            'emailProfile' => $profile->emailProfile,
            'phoneNumber' => $profile->phoneNumber,
            'projects' => $profile->projects,
            'progLanguages' => $profile->progLanguages,
            'cvUrl' => $profile->cvUrl,
            'githubUrl' => $profile->githubUrl,
            'linkedinUrl' => $profile->linkedinUrl,
            'behanceUrl' => $profile->behanceUrl,
            'facebookUrl' => $profile->facebookUrl,
            'twitterUrl' => $profile->twitterUrl,
        ];

        return $this->data(['user' => $userData], 'User retrieved successfully', 200);
      } catch (\Exception $e) {
        return $this->errorMessage([], 'Failed to retrieve user', 500);
      }
    }


}
