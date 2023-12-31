<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Traits\ApiTrait;

class CommentController extends Controller
{
    use ApiTrait;

    public function showAllComments(Post $post)
    {
        try {
            $comments = $post->comments->map(function ($comment) {
                $user = $comment->user;
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'post_id' => $comment->post_id,
                    'user_id' => $comment->user_id,
                    'user_name' => $user->name, 
                    'user_imageUrl' => $user->imageUrl, 
                    'deleted_at' => $comment->deleted_at,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                ];
            });

            return $this->data(compact('comments'));
        } catch (\Exception $e) {
            return $this->errorMessage([], 'An error occurred while fetching comments', 500);
        }
    }

    public function createComment(CommentRequest $request, Post $post)
    {
        try {
            // Create a new comment
            $comment = new Comment();
            $comment->content = $request->input('content');
            $comment->user_id = Auth::id(); // Set the user ID from the authenticated user
            $comment->post_id = $post->id; // Set the post ID
            $comment->save();

            // Update the comments_count for the post
            $post->increment('comments_count');

            // Fetch all comments for the post
            $comments = $post->comments->map(function ($comment) {
                $user = $comment->user;
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'post_id' => $comment->post_id,
                    'user_id' => $comment->user_id,
                    'user_name' => $user->name, 
                    'user_imageUrl' => $user->imageUrl, 
                    'deleted_at' => $comment->deleted_at,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                ];
            });

            return $this->data(compact('comments'), 'Comment created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorMessage([], 'An error occurred while creating the comment', 500);
        }
    }

    public function editComment(CommentRequest $request, Post $post, Comment $comment)
    {
        try {
            // Check if the comment belongs to the post
            if ($comment->post_id !== $post->id) {
                return $this->errorMessage([], 'Comment not found for the specified post', 404);
            }

            // Update the comment content
            $comment->content = $request->input('content');
            $comment->save();

            // Fetch all comments for the post
            $comments = $post->comments->map(function ($comment) {
                $user = $comment->user;
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'post_id' => $comment->post_id,
                    'user_id' => $comment->user_id,
                    'user_name' => $user->name, 
                    'user_imageUrl' => $user->imageUrl, 
                    'deleted_at' => $comment->deleted_at,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                ];
            });

            return $this->data(compact('comments'), 'Comment updated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage([], 'An error occurred while updating the comment', 500);
        }
    }

    public function deleteComment(Post $post, Comment $comment)
    {
        try {
            // Check if the comment belongs to the post
            if ($comment->post_id !== $post->id) {
                return $this->errorMessage([], 'Comment not found for the specified post', 404);
            }

            // Decrement the comments_count for the post
            $post->decrement('comments_count');

            // Delete the comment
            $comment->delete();

            return $this->data(['post_id' => $post->id],'Comment deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage([], 'An error occurred while deleting the comment', 500);
        }
    }
}
