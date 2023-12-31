<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiTrait;

class LikesController extends Controller
{
    use ApiTrait;

    public function likePost(Post $post)
    {
        // Check if the user has already liked this post
        $existingLike = Like::where('user_id', Auth::id())->where('post_id', $post->id)->first();
    
        if ($existingLike) {
            // Remove the like record
            $existingLike->delete();
    
            // Update the likes_count in the posts table
            $post->decrement('likes_count');
    
            return $this->data(['post_id' => $post->id],'Post unliked successfully', 200);
        }
    
        // Create a new like record for the authenticated user and the post
        $like = new Like;
        $like->user_id = Auth::id();
        $like->post_id = $post->id;
        $like->save();
    
        // Update the likes_count in the posts table
        $post->increment('likes_count');
    
        return $this->data(['post_id' => $post->id],'Post liked successfully', 200);
    }

    public function getLikesForPost(Post $post)
    {
        // Get all likes for the post
        $likes = Like::where('post_id', $post->id)->get();

        // Transform the likes data to include user_name and user_imageUrl
        $likeData = $likes->map(function ($like) {
            $data = $like->toArray();
            $data['user_name'] = $like->user->name; 
            $data['user_imageUrl'] = $like->user->imageUrl; 
            unset($data['user']); 
            return $data;
        });

        return $this->data(compact('likeData'));
    }
}
