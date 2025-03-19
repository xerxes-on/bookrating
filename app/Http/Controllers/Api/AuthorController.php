<?php

namespace App\Http\Controllers\Api;

use App\Models\Author;
use App\Models\User;
use App\Recources\AuthorsResource;
use Illuminate\Http\Request;

class AuthorController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $page = $request->input('page', 1);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        $authors = Author::with(['books', 'quotes'])
            ->orderBy('name')
            ->skip($offset)
            ->take($perPage)
            ->get();

        return AuthorsResource::collection($authors);
    }
    public function search(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $query = $request->input('query');
        if (empty($query)) {
            return AuthorsResource::collection(collect([]));
        }
        $authors = Author::where('name', 'like', "%{$query}%")
            ->with(['books', 'quotes'])
            ->orderBy('name')
            ->get();

        return AuthorsResource::collection($authors);
    }
    public function show($id)
    {
        $author = Author::with(['books', 'quotes'])->findOrFail($id);
        return new AuthorsResource($author);
    }

    public function follow(Author $author)
    {
        $user = User::find(auth()->user()->id);
        if($user->followingAuthors()->where('author_id',$author->id )->exists()){
            auth()->user()->followingAuthors()->detach($author->id);
            return response()->json(['message' => 'Author unfollowed successfully']);
        }else{
            auth()->user()->followingAuthors()->attach($author->id);
            return response()->json(['message' => 'Author followed successfully']);
        }
    }
    public function isFollowing(Author $author)
    {
        $isFollowing = auth()->user()->followingAuthors()->where('author_id', $author->id)->exists();
        return response()->json(['isFollowing' => $isFollowing]);
    }
}
