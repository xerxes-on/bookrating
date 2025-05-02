<script setup>
import { ref, onMounted } from 'vue';
import Layout from "@/components/common/Layout.vue";
import { RouterLink } from 'vue-router';
import bookApi from '@/api/book';

// State
const books = ref([]);
const loading = ref(false);
const page = ref(1);
const reachedEnd = ref(false);

// Methods
const fetchBooks = async () => {
    if (loading.value || reachedEnd.value) return;

    loading.value = true;
    try {
        const response = await bookApi.getBooks(page.value);

        const newBooks = response.data.data || [];
        books.value = [...books.value, ...newBooks];

        if (newBooks.length === 0) {
            reachedEnd.value = true;
        } else {
            page.value++;
        }
    } catch (error) {
        console.error('Error fetching books:', error);
    } finally {
        loading.value = false;
    }
};

const loadMore = () => {
    fetchBooks();
};

const formatDate = (dateString) => {
    if (!dateString) return 'Unknown';
    const date = new Date(dateString);
    return date.getFullYear();
};

// Initialize
onMounted(() => {
    fetchBooks();
});
const defaultImage = '/images/cat.png';
</script>

<template>
        <div class="books-container">
            <h1 class="page-title">Books</h1>
            <div class="books-grid">
                <div v-for="book in books" :key="book.id" class="book-card">
                    <RouterLink :to="`/book/${book.id}`" class="book-link">
                        <div class="book-image-container">
                            <img
                                :src="book.image"
                                :alt="book.title"
                                class="book-image"
                            />
                            <div class="book-overlay">
                                <div class="book-rating" v-if="book.average_rating">
                                    <span class="star">★</span>
                                    <span>{{ book.average_rating.toFixed(1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="book-info !bg-gray-200">
                            <h3 class="book-title">{{ book.title }}</h3>
                            <p class="book-author" v-if="book.author">{{ book.author.data.name }}</p>
                            <div class="book-details">
                                <span class="book-year" v-if="book.published_date">{{ formatDate(book.published_date) }}</span>
                                <span class="book-reviews">{{ book.reviews_count }} reviews</span>
                            </div>
                        </div>
                    </RouterLink>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="books.length === 0 && !loading" class="empty-state">
                <p>No books available.</p>
            </div>

            <!-- Load more button -->
            <div class="load-more-container" v-if="!reachedEnd">
                <button
                    @click="loadMore"
                    :disabled="loading"
                    class="load-more-button !bg-orange_dark"
                >
                    {{ loading ? 'Loading...' : 'Load More Books' }}
                </button>
            </div>
        </div>
</template>

<style scoped>
.books-container {
    padding: 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-title {
    font-size: 2rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1.5rem;
}

.book-card {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: white;
    height: 100%;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.book-link {
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none;
    color: inherit;
}

.book-image-container {
    position: relative;
    height: 260px;
    overflow: hidden;
}

.book-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 0.5rem;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
}

.book-rating {
    display: inline-flex;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.6);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    color: white;
    font-size: 0.875rem;
}

.star {
    color: #FFD43B;
    margin-right: 0.25rem;
}

.book-info {
    padding: 1rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.book-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.5rem;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.book-author {
    font-size: 0.875rem;
    color: #666;
    margin: 0 0 0.5rem;
}

.book-details {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: #666;
    margin-top: auto;
}

.empty-state {
    text-align: center;
    padding: 3rem 0;
    color: #666;
}

.load-more-container {
    text-align: center;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.load-more-button {
    background-color: #FF9F00;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.load-more-button:hover:not(:disabled) {
    background-color: #e08e00;
}

.load-more-button:disabled {
    background-color: #ffc571;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}

@media (max-width: 480px) {
    .books-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .book-image-container {
        height: 220px;
    }
}
</style>
