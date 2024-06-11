<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class postController extends Controller
{
     function postsList()
    {
        return Post::all();

    }

    function addpost(Request $request)
         {
        $posts = new Post;
        $posts->name = $request->name;
        $posts->post_date  = $request->post_date ;
        $posts->description  = $request->description ;
        $imagePath = $request->file('image')->store('public');
        $posts->image = $imagePath;
        $result = $posts->save();

        if ($result) {
            $fromDatabase = Post::find($posts->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];
            return response()->json($response);
        } else {
            return response()->json(["error" => "Failed to add posts"], 500);
        }

    }
    function editPost(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:posts,id',
            'name' => 'required|string',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $post = Post::find($validated['id']);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        $post->name = $validated['name'];
        $post->description = $validated['description'];
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::delete($post->image);
            }
            $imagePath = $request->file('image')->store('public/images');
            $post->image = $imagePath;
        }

       $result = $post->save();
        if ($result)
        {
            $fromDatabase = Post::find($post->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];
            return response()->json($response);
        }
        else
        {
            return response()->json(["error" => "Failed to edit posts"], 500);
        }
    }
    function deletePost($id)
{
    $post = Post::find($id);
    if (!$post) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    if ($post->image) {
        Storage::delete($post->image);
    }

    $post->delete();

    return response()->json(['message' => 'Post deleted successfully']);
}

}
