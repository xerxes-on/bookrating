import { defineStore } from 'pinia';
import { reactive } from 'vue';

export const useReviewsStore = defineStore('reviewsStore', () => {
    const reviews = reactive({
        data: [], // Holds the reviews array
        current_page: 1, // Current page number
        last_page: 1, // Total number of pages
        next_page_url: null // The next page URL for loading more reviews
    });

    const resetStore = () => {
        reviews.data = [];
        reviews.current_page = 1;
        reviews.last_page = 1;
        reviews.next_page_url = null;
    };

    const addReviews = (newReviews, currentPage, lastPage, nextPageUrl) => {
        reviews.data.push(...newReviews); // Append new reviews to the array
        reviews.current_page = currentPage;
        reviews.last_page = lastPage;
        reviews.next_page_url = nextPageUrl;
    };

    return {
        reviews,
        resetStore,
        addReviews
    };
}, { persist: true });
