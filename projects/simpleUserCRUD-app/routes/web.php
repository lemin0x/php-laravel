<?php

use App\Models\Post;
use PhpParser\Node\Expr\FuncCall;   
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    $posts = [];
    if (auth()->check()) {
        $posts = Post::where('user_id', auth()->id())->get();
    }

   
//    $posts = auth()->user()->usersIdiotPosts()->latest()->get(); 
   return view('home', [ 'posts' => $posts ]);
});



Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

//blog post
Route::post('/createpost', [PostController::class, 'createPost']);
Route::get('/edit-post/{post}', [PostController::class, 'showEditScreen']);
Route::put('/edit-post/{post}', [PostController::class, 'updatePost']);
Route::delete('/delete-post/{post}', [PostController::class, 'deletePost']);