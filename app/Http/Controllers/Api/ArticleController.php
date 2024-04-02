<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{

    //get articles
    public function index()
    {
        $articles = Article::paginate(2);
        return ArticleResource::collection($articles);
    }
    public function show($id)
    {
        $article = Article::find($id);
        if (is_null($article)) {
            return response()->json([
                'message' => 'article Not Found'
            ], 404);
        }
        return new ArticleResource($article);
    }

    //add article

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'content' => 'required|string',
            'img' => 'required|image|max:1024|mimes:jpg,jpeg,png'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 409);
        }
        // Generate a unique filename
        $filename = uniqid() . '_' . $request->file('img')->getClientOriginalName();

        // Upload the image to the "articles" folder
        $imgPath = $request->file('img')->storeAs('articles', $filename, 'uploads');

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'img' => $imgPath
        ]);

        return response()->json([
            'message' => 'article created successfully',
        ], 201);
        // 200 success 201 created anything successfully
    }

    //update article


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'content' => 'required|string',
            'img' => 'image|max:1024|mimes:jpg,jpeg,png'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 409);
        }

        $article = Article::find($id);
        if (is_null($article)) {
            return response()->json([
                'message' => 'article Not Found'
            ], 404);
        }

        $filename = uniqid() . '_' . $request->file('img')->getClientOriginalName();

        // Upload the image to the "articles" folder
        $imgPath = $request->file('img')->storeAs('articles', $filename, 'uploads');

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'img' => $imgPath
        ]);

        return response()->json([
            'message' => 'article updated successfully',
        ], 200);
    }

    //delete article

    public function delete($id)
    {
        $article = Article::find($id);
        if (is_null($article)) {
            return response()->json([
                'message' => 'article Not Found'
            ], 404);
        }
        Storage::delete($article->img);
        $article->delete();
        return response()->json([
            'message' => 'article deleted successfully',
        ], 200);
    }
}

