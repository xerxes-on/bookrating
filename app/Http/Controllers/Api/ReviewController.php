<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Rating;
use App\Recources\ReviewsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Rating::where('id', '=', auth()->user()->id)->get();
        if ($reviews) {
            return response(ReviewsResource::collection($reviews));
        } else {
            return response('No reviews', 404);
        }
    }

    public function getReviews(Book $book): JsonResponse
    {
        $perPage = 10;
        $reviews = Rating::query()->where('book_id', $book->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($reviews);
    }

    public function getAllReviews(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);
        $reviews = Rating::with('user')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'page', $page);
        return response()->json($reviews);
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'rating' => 'min:1|max:10|required',
                'comment' => 'nullable|min:10',
                'book_id' => 'required|integer|exists:books,id'
            ]
        );
        $inserting = [
            'comment' => $request->input('comment'),
            'book_id' => $request->input('book_id'),
            'rating' => $request->input('rating') * 2
        ];
        $inserting['user_id'] = auth()->user()->id;
        Rating::create($inserting);
        return response()->json([
            'message' => 'Created Successfully 🙂',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $review = Rating::with('user')->find($id);
        if ($review) {
            return response(new ReviewsResource($review));
        } else {
            return response('There is no review with such id');
        }
    }

    public function update(Request $request, string $id)
    {

        $review = Rating::with('user')->find($id);
        $request->validate(
            [
                'rating' => 'min:1|max:10|required',
                'comment' => 'required| min:10',
            ]
        );
        if (!$review->user->id == auth()->user()->id) {
            return response('Unauthorized');
        }

        $inserting = $request->all();
        $inserting['user_id'] = auth()->user()->id;

        $review->update($inserting);

        return response()->json([
            'message' => 'Updated Successfully',
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $review = Rating::find($id);
        if ($review->user->id == auth()->user()->id) {
            $review->delete();
        } else {
            return response('Unauthorized');
        }
    }

    public function like($id): JsonResponse
    {
        $user = auth()->user();
        $rating = Rating::find($id);

        if ($user->likedRatings()->where('rating_id', $id)->exists()) {
            $user->likedRatings()->detach($id);
            $rating->decrement('likes');
            return response()->json([
                'message' => 'Unliked successfully',
                'likes' => $rating->likes
            ]);
        } else {
            $user->likedRatings()->attach($id);
            $rating->increment('likes');
            return response()->json([
                'message' => 'Liked successfully',
                'likes' => $rating->likes
            ]);
        }
    }
}
