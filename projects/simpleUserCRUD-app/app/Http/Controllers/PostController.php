<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function deletePost(Post $post){
        if (auth()->user()->id === $post['user_id']) {
            $post->delete();
            
        }
        return redirect('/');
    }
    public function updatePost(Post $post, Request $request){
        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
        }

        $incomeData = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
         
        $incomeData['title'] = strip_tags($incomeData['title']);
        $incomeData['body'] = strip_tags($incomeData['body']);
        $post->update($incomeData);
        return redirect('/');
    }
    public function showEditScreen(Post $post){
        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
        }
        return view('edit-post', ['post' => $post]);
    }

    public function createPost(Request $request){
        // Ensure user is authenticated
        // if (!auth()->check()) {
        //     return redirect('/login');
        // }


        $incomeData = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
         
        $incomeData['title'] = strip_tags($incomeData['title']);
        $incomeData['body'] = strip_tags($incomeData['body']);
        $incomeData['user_id'] = auth()->id();
        Post::create($incomeData);
        return redirect('/');
    }
    
}