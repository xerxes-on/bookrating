<?php

namespace App\Filament\Admin\Resources\RatingResource\Widgets;

use App\Models\Rating;
use App\Models\Book;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class RatingStatistics extends BaseWidget
{
    protected function getStats(): array
    {
        // Get average rating across all books
        $averageRating = Rating::avg('rating');
        $averageRating = number_format($averageRating, 1);

        // Count total ratings
        $totalRatings = Rating::count();

        // Get the book with the highest average rating (minimum 5 ratings)
        $topRatedBook = Book::select('books.id', 'books.title', DB::raw('AVG(ratings.rating) as average_rating'), DB::raw('COUNT(ratings.id) as rating_count'))
            ->join('ratings', 'books.id', '=', 'ratings.book_id')
            ->groupBy('books.id', 'books.title')
            ->having('rating_count', '>=', 5)
            ->orderBy('average_rating', 'desc')
            ->first();

        $topRatedBookInfo = $topRatedBook
            ? "{$topRatedBook->title} (" . number_format($topRatedBook->average_rating, 1) . " ⭐)"
            : 'No books with 5+ ratings';

        return [
            Stat::make('Average Rating', $averageRating . ' ⭐')
                ->description('Across all books')
                ->descriptionIcon('heroicon-o-star')
                ->chart([
                    $averageRating / 5 * 100,
                    (5 - $averageRating) / 5 * 100
                ])
                ->color('warning'),

            Stat::make('Total Ratings', $totalRatings)
                ->description('From all users')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Highest Rated Book', $topRatedBookInfo)
                ->description('With minimum 5 ratings')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('success'),
        ];
    }
}
