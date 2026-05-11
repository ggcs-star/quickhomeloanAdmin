<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityPost;
use App\Models\CommunityComment;

class CommunityController extends Controller
{
    public function index()
    {
        $posts = CommunityPost::orderBy('created_at', 'desc')->paginate(10);
        return view('community.index', compact('posts'));
    }

public function storePost(Request $request)
{
    $request->validate([
        'content' => 'required|string'
    ]);

    $user = auth()->user();
$content = (string) $request->input('content');



    $post = new CommunityPost();
    $post->user_id = (string)$user->_id;
    $post->user_name = $user->full_name ?? 'Admin';
    $post->user_photo = null;
    $post['content'] = $content;
    $post->likes = [];
    $post->likes_count = 0;
    $post->shares = [];
    $post->shares_count = 0;
    $post->saved_by = [];
    $post->saved_count = 0;
    $post->comments_count = 0;
    $post->save();

    return back()->with('success', 'Post created successfully');
}

    public function destroy($id)
    {
        $post = CommunityPost::findOrFail($id);
        CommunityComment::where('post_id', $id)->delete();
        $post->delete();
        return back()->with('success', 'Post deleted successfully');
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required',
            'comment' => 'required|string'
        ]);

        $user = auth()->user();

        $comment = new CommunityComment();
        $comment->post_id = $request->post_id;
        $comment->user_id = (string)$user->_id;
        $comment->user_name = $user->full_name ?? 'Admin';
        $comment->user_photo = null;
        $comment->comment = $request->comment;
        $comment->is_admin_reply = true;
        $comment->parent_id = null;
        $comment->likes = [];
        $comment->likes_count = 0;
        $comment->save();

        CommunityPost::where('_id', $request->post_id)->increment('comments_count');

        return back()->with('success', 'Reply posted successfully');
    }

    public function destroyComment($id)
    {
        $comment = CommunityComment::findOrFail($id);
        CommunityPost::where('_id', $comment->post_id)->decrement('comments_count');
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully');
    }
}