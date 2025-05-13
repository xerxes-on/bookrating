<script setup>
import { ref, onMounted } from "vue";
import { useRoute, RouterLink } from "vue-router";
import Layout from "@/components/common/Layout.vue";
import authorApi from '@/api/author';

const route = useRoute();
const author = ref(null);
const loading = ref(true);
const error = ref(null);

const formatDate = (dateString) => {
    if (!dateString) return 'Unknown';
    return new Date(dateString).toLocaleDateString();
};

onMounted(async () => {
    try {
        const authorId = route.params.id;
        const response = await authorApi.getAuthorDetails(authorId);

        if (response.status === 200 && response.data) {
            author.value = response.data.data || response.data;
        } else {
            error.value = "Failed to load author details";
        }
    } catch (err) {
        error.value = "An error occurred while loading the author";
        console.error(err);
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <Layout>
        <div class="author-page">
            <!-- Loading state -->
            <div v-if="loading" class="loading-state">
                <div class="loading-spinner"></div>
                <p>Loading author details...</p>
            </div>

            <!-- Error state -->
            <div v-else-if="error" class="error-state">
                <p>{{ error }}</p>
                <RouterLink to="/authors" class="back-link">Back to Authors</RouterLink>
            </div>

            <!-- Content when loaded -->
            <div v-else-if="author" class="author-content">
                <!-- Author header section -->
                <div class="author-header">
                    <div class="author-img-container">
                        <img
                            :src="'/images/author.jpeg'"
                            :alt="author.name"
                            class="author-img"
                            @error="$event.target.src = '/images/default-author.jpg'"
                        />
                    </div>

                    <div class="author-info">
                        <h1 class="author-name">{{ author.name }}</h1>
                        <p class="author-dates">
                            {{ formatDate(author.date_of_birth) }} -
                            {{ author.date_died ? formatDate(author.date_died) : 'Present' }}
                        </p>
                        <div class="author-stats">
                            <div class="stat">
                                <span class="stat-value">{{ author.written_books.length }}</span>
                                <span class="stat-label">Books</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value">{{ author.quotes.length }}</span>
                                <span class="stat-label">Quotes</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Author biography -->
                <div class="author-bio">
                    <h2 class="section-title">Biography</h2>
                    <p class="bio-text">{{ author.description }}</p>
                </div>

                <!-- Author's books -->
                <div class="author-books">
                    <h2 class="section-title">Books by {{ author.name }}</h2>

                    <div v-if="author.written_books.length === 0" class="no-books">
                        <p>No books available for this author.</p>
                    </div>

                    <div v-else class="books-grid">
                        <div v-for="book in author.written_books" :key="book.id" class="book-card">
                            <RouterLink :to="`/book/${book.id}`" class="book-link">
                                <div class="book-cover-container">
                                    <img
                                        :src="book.image"
                                        :alt="book.title"
                                        class="book-cover"
                                    />
                                </div>
                                <div class="book-details">
                                    <h3 class="book-title">{{ book.title }}</h3>
                                    <p v-if="book.published_date" class="book-year">
                                        {{ new Date(book.published_date).getFullYear() }}
                                    </p>
                                    <div class="book-rating" v-if="book.rating">
                                        <span class="stars">★★★★★</span>
                                        <span class="rating-value">{{ book.rating.toFixed(1) }}</span>
                                    </div>
                                </div>
                            </RouterLink>
                        </div>
                    </div>
                </div>

                <!-- Author quotes section -->
                <div v-if="author.quotes.length > 0" class="author-quotes">
                    <h2 class="section-title">Notable Quotes</h2>
                    <div class="quotes-container">
                        <div v-for="(quote, index) in author.quotes.slice(0, 3)" :key="index" class="quote-card">
                            <p class="quote-text !text-dark_blue">"{{ quote.quote }}"</p>
                            <p class="quote-source" v-if="quote.source">- {{ quote.source }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
.author-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.loading-state, .error-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 50vh;
    text-align: center;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #4a6cf7;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.back-link {
    display: inline-block;
    margin-top: 1rem;
    color: #4a6cf7;
    text-decoration: none;
}

.author-header {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
}

.author-img-container {
    flex: 0 0 250px;
    height: 300px;
}

.author-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.author-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.author-name {
    font-size: 2.5rem;
    margin: 0 0 0.5rem;
}

.author-dates {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 1.5rem;
}

.author-stats {
    display: flex;
    gap: 2rem;
    margin-top: auto;
}

.stat {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #4a6cf7;
}

.stat-label {
    font-size: 1rem;
    color: #666;
}

.section-title {
    font-size: 1.8rem;
    margin: 2rem 0 1rem;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 0.5rem;
}

.author-bio {
    margin-bottom: 2rem;
}

.bio-text {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #333;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1.5rem;
}

.book-card {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.book-card:hover {
    transform: translateY(-5px);
}

.book-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.book-cover-container {
    height: 250px;
    overflow: hidden;
}

.book-cover {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-details {
    padding: 1rem;
}

.book-title {
    font-size: 1rem;
    margin: 0 0 0.5rem;
    font-weight: 600;
}

.book-year {
    font-size: 0.875rem;
    color: #666;
    margin: 0.25rem 0;
}

.book-rating {
    display: flex;
    align-items: center;
    margin-top: 0.5rem;
}

.stars {
    color: #ffc107;
    margin-right: 0.5rem;
    font-size: 0.875rem;
}

.rating-value {
    font-size: 0.875rem;
    font-weight: 600;
}

.quotes-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.quote-card {
    background-color: #f9f9f9;
    border-left: 4px solid #4a6cf7;
    padding: 1.5rem;
    border-radius: 0 8px 8px 0;
}

.quote-text {
    font-style: italic;
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

.quote-source {
    text-align: right;
    font-weight: 600;
}

.no-books {
    padding: 2rem;
    text-align: center;
    background-color: #f9f9f9;
    border-radius: 8px;
    color: #666;
}

@media (max-width: 768px) {
    .author-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .author-img-container {
        flex: 0 0 auto;
        width: 200px;
        height: 250px;
    }

    .author-stats {
        justify-content: center;
        margin-top: 1rem;
    }

    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .quotes-container {
        grid-template-columns: 1fr;
    }
}
</style>
