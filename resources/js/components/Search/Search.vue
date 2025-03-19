<script setup>
import { ref, watchEffect } from 'vue';
import { RouterLink } from 'vue-router';
import { debounce } from 'lodash';
import bookApi from '@/api/book';

// State
const searchQuery = ref('');
const searchContent = ref('books');
const searchResults = ref([]);
const loading = ref(false);
const recentSearches = ref(JSON.parse(localStorage.getItem('recentSearches')) || []);

// Search functions using API clients
const searchBooks = async (query) => {
    if (!query.trim()) return [];
    loading.value = true;
    try {
        const response = await bookApi.searchBooks(query);
        loading.value = false;
        return response.data.data || [];
    } catch (error) {
        console.error('Error searching books:', error);
        loading.value = false;
        return [];
    }
};

const searchQuotes = async (query) => {
    if (!query.trim()) return [];
    loading.value = true;
    try {
        const response = await bookApi.searchQuotes(query);
        loading.value = false;
        return response.data.data || [];
    } catch (error) {
        console.error('Error searching quotes:', error);
        loading.value = false;
        return [];
    }
};

// Debounced search function
const debouncedSearch = debounce(async (query) => {
    if (!query.trim()) return;

    if (searchContent.value === 'books') {
        searchResults.value = await searchBooks(query);
    } else {
        searchResults.value = await searchQuotes(query);
    }

    // Save search to recent searches
    if (query.trim() && !recentSearches.value.includes(query)) {
        recentSearches.value.unshift(query);
        if (recentSearches.value.length > 5) {
            recentSearches.value.pop();
        }
        localStorage.setItem('recentSearches', JSON.stringify(recentSearches.value));
    }
}, 300);

// Watch for search query changes
watchEffect(() => {
    if (searchQuery.value) {
        debouncedSearch(searchQuery.value);
    } else {
        searchResults.value = [];
    }
});

// Toggle between books and quotes search
const changeContent = () => {
    searchContent.value = searchContent.value === 'books' ? 'quotes' : 'books';
    // Reset search results and perform new search if query exists
    if (searchQuery.value) {
        debouncedSearch(searchQuery.value);
    } else {
        searchResults.value = [];
    }
};

// Use a recent search
const useRecentSearch = (query) => {
    searchQuery.value = query;
    debouncedSearch(query);
};

// Clear search
const clearSearch = () => {
    searchQuery.value = '';
    searchResults.value = [];
};

// Clear all recent searches
const clearRecentSearches = () => {
    recentSearches.value = [];
    localStorage.removeItem('recentSearches');
};

// Highlight matching text in search results
const highlightText = (text, query) => {
    if (!query || !text) return text;

    try {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    } catch (e) {
        // If regex fails (e.g., with special characters), return original text
        return text;
    }
};
</script>

<template>
    <main class="search-page">
        <!-- Background animation elements -->
        <div class="background-animation">
            <div v-for="n in 20" :key="n" class="floating-book" :style="{
                '--delay': `${Math.random() * 10}s`,
                '--duration': `${20 + Math.random() * 20}s`,
                '--scale': `${0.5 + Math.random() * 0.5}`,
                '--left': `${Math.random() * 100}%`,
                '--top': `${Math.random() * 100}%`
            }"></div>

            <!-- Large slowly moving circle -->
            <div class="floating-circle" :style="{
                width: '400px',
                height: '400px',
                top: '10%',
                left: '5%',
                '--delay': '2s',
                '--duration': '45s',
                '--moveX': '100px',
                '--moveY': '50px',
                '--moveX2': '-50px',
                '--moveY2': '150px'
            }"></div>

            <!-- Medium circle -->
            <div class="floating-circle" :style="{
                width: '250px',
                height: '250px',
                bottom: '15%',
                right: '10%',
                '--delay': '0s',
                '--duration': '35s',
                '--moveX': '-120px',
                '--moveY': '-80px',
                '--moveX2': '70px',
                '--moveY2': '-120px'
            }"></div>

            <!-- Small faster circle -->
            <div class="floating-circle" :style="{
                width: '150px',
                height: '150px',
                top: '60%',
                left: '25%',
                '--delay': '5s',
                '--duration': '25s',
                '--moveX': '80px',
                '--moveY': '-100px',
                '--moveX2': '-120px',
                '--moveY2': '50px'
            }"></div>

            <!-- Extra small circle -->
            <div class="floating-circle" :style="{
                width: '100px',
                height: '100px',
                top: '30%',
                right: '20%',
                '--delay': '8s',
                '--duration': '20s',
                '--moveX': '-60px',
                '--moveY': '70px',
                '--moveX2': '100px',
                '--moveY2': '30px'
            }"></div>
        </div>

        <div class="content-wrapper">
            <!-- Header -->
            <div class="search-header">
                <div class="logo-container">
                    <div class="logo">📚</div>
                    <h1 class="title">BookHaven</h1>
                </div>
                <div class="toggle-container">
                    <button @click.prevent="changeContent" class="toggle-button">
                        {{ searchContent === 'books' ? 'Switch to Quotes' : 'Switch to Books' }}
                    </button>
                </div>
            </div>

            <!-- Search section -->
            <div class="search-container">
                <div class="search-bar">
                    <div class="search-icon">🔍</div>
                    <input
                        v-model="searchQuery"
                        type="text"
                        :placeholder="`Search for ${searchContent}...`"
                        class="search-input"
                    />
                    <button v-if="searchQuery" @click="clearSearch" class="clear-button">✕</button>
                </div>

                <!-- Recent searches -->
                <div v-if="recentSearches.length > 0 && !searchQuery" class="recent-searches">
                    <div class="recent-header">
                        <h3>Recent Searches</h3>
                        <button @click="clearRecentSearches" class="clear-all">Clear All</button>
                    </div>
                    <div class="recent-list">
                        <button
                            v-for="(search, index) in recentSearches"
                            :key="index"
                            @click="useRecentSearch(search)"
                            class="recent-item"
                        >
                            <span class="recent-icon">🕒</span>
                            <span class="recent-text">{{ search }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Results section -->
            <div class="results-container">
                <!-- Loading indicator -->
                <div v-if="loading" class="loading-container">
                    <div class="loading-spinner"></div>
                    <p class="loading-text">Searching {{ searchContent }}...</p>
                </div>

                <!-- Books search results -->
                <div v-else-if="searchContent === 'books' && searchResults.length > 0" class="results-grid">
                    <RouterLink
                        v-for="book in searchResults"
                        :key="book.id"
                        :to="`book/${book.id}`"
                        class="book-card"
                    >
                        <div class="book-image-container">
                            <img :src="book.image" :alt="book.title" class="book-image" />
                            <div class="book-overlay">
                                <div class="book-rating" v-if="book.average_rating">
                                    <span class="star">★</span>
                                    <span>{{ book.average_rating.toFixed(1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="book-details">
                            <h3 class="book-title" v-html="highlightText(book.title, searchQuery)"></h3>
                            <p class="book-author" v-if="book.author">{{ book.author.data.name }}</p>
                        </div>
                    </RouterLink>
                </div>

                <!-- Quotes search results -->
                <div v-else-if="searchContent === 'quotes' && searchResults.length > 0" class="quotes-list">
                    <RouterLink
                        v-for="quote in searchResults"
                        :key="quote.id"
                        :to="`quotes/${quote.id}`"
                        class="quote-card"
                    >
                        <div class="quote-text" v-html="highlightText(quote.quote, searchQuery)"></div>
                        <div class="quote-author">— {{ quote.author?.data?.name || 'Unknown' }}</div>
                    </RouterLink>
                </div>

                <!-- Empty state -->
                <div v-else-if="searchQuery && !loading && searchResults.length === 0" class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3 class="empty-title">No results found</h3>
                    <p class="empty-text">We couldn't find any {{ searchContent }} matching "{{ searchQuery }}"</p>
                    <p class="empty-suggestion">Try different keywords or check your spelling</p>
                </div>

                <!-- Initial state -->
                <div v-else-if="!searchQuery" class="initial-state">
                    <div class="initial-icon">{{ searchContent === 'books' ? '📚' : '💬' }}</div>
                    <h3 class="initial-title">Search for {{ searchContent }}</h3>
                    <p class="initial-text">Enter a keyword in the search box above to find {{ searchContent }}</p>
                </div>
            </div>
        </div>
    </main>
</template>

<style scoped>
/* Main container */
.search-page {
    min-height: 100vh;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #F6FFDE 0%, #E4EFC9 100%);
    padding: 1rem;
}

/* Animated background */
.background-animation {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    overflow: hidden;
}

.floating-book {
    position: absolute;
    width: 30px;
    height: 40px;
    background-color: rgba(164, 192, 237, 0.7);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 3px;
    left: var(--left);
    top: var(--top);
    transform: scale(var(--scale));
    opacity: 0.6;
    animation: float var(--duration) infinite ease-in-out;
    animation-delay: var(--delay);
}

.floating-circle {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(145deg, rgba(164, 192, 237, 0.4), rgba(38, 80, 115, 0.2));
    filter: blur(10px);
    z-index: 0;
    opacity: 0.5;
    animation: moveAround var(--duration) infinite alternate ease-in-out;
    animation-delay: var(--delay);
}

@keyframes moveAround {
    0% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(var(--moveX), var(--moveY)) scale(1.1);
    }
    100% {
        transform: translate(var(--moveX2), var(--moveY2)) scale(0.9);
    }
}

.floating-book::before {
    content: '';
    position: absolute;
    top: 5%;
    left: 10%;
    width: 80%;
    height: 4px;
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 2px;
}

.floating-book::after {
    content: '';
    position: absolute;
    bottom: 5%;
    left: 10%;
    width: 80%;
    height: 4px;
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 2px;
}

@keyframes float {
    0% {
        transform: translateY(0) rotate(0deg) scale(var(--scale));
    }
    50% {
        transform: translateY(-20px) rotate(5deg) scale(var(--scale));
    }
    100% {
        transform: translateY(0) rotate(0deg) scale(var(--scale));
    }
}

/* Content wrapper */
.content-wrapper {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: 0 auto;
    background-color: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    min-height: calc(100vh - 2rem);
    border: 1px solid #EFEAD3;
}

/* Header */
.search-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.logo-container {
    display: flex;
    align-items: center;
}

.logo {
    font-size: 2.5rem;
    margin-right: 1rem;
}

.title {
    font-size: 2rem;
    font-weight: 700;
    color: #265073;
    margin: 0;
}

.toggle-container {
    display: flex;
    align-items: center;
}

.toggle-button {
    background-color: #265073;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 9999px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    box-shadow: 0 4px 6px rgba(38, 80, 115, 0.3);
}

.toggle-button:hover {
    background-color: #1e405c;
}

/* Search container */
.search-container {
    margin-bottom: 2rem;
}

.search-bar {
    display: flex;
    align-items: center;
    background-color: white;
    border-radius: 9999px;
    padding: 0.75rem 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
}

.search-icon {
    font-size: 1.5rem;
    margin-right: 0.75rem;
    color: #6b7280;
}

.search-input {
    flex: 1;
    border: none;
    font-size: 1.125rem;
    color: #1f2937;
    width: 100%;
    padding: 0.5rem 0;
}

.search-input:focus {
    outline: none;
}

.clear-button {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #9ca3af;
    cursor: pointer;
}

.clear-button:hover {
    color: #6b7280;
}

/* Recent searches */
.recent-searches {
    margin-top: 1rem;
    background-color: #EFEAD3;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.recent-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.recent-header h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #4b5563;
    margin: 0;
}

.clear-all {
    background: none;
    border: none;
    font-size: 0.875rem;
    color: #6b7280;
    cursor: pointer;
}

.clear-all:hover {
    color: #4b5563;
    text-decoration: underline;
}

.recent-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.recent-item {
    display: flex;
    align-items: center;
    background-color: #A4C0ED;
    border: none;
    border-radius: 9999px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    color: #265073;
    cursor: pointer;
    transition: background-color 0.2s;
}

.recent-item:hover {
    background-color: #88aae0;
}

.recent-icon {
    margin-right: 0.5rem;
    font-size: 0.75rem;
}

/* Results section */
.results-container {
    min-height: 300px;
}

/* Loading */
.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 0;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: #265073;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.loading-text {
    margin-top: 1rem;
    font-size: 1rem;
    color: #6b7280;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Books grid */
.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1.5rem;
}

.book-card {
    text-decoration: none;
    color: inherit;
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

.book-image-container {
    position: relative;
    height: 240px;
    overflow: hidden;
}

.book-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.book-card:hover .book-image {
    transform: scale(1.05);
}

.book-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent 70%, rgba(0, 0, 0, 0.7) 100%);
    display: flex;
    align-items: flex-end;
    padding: 0.75rem;
}

.book-rating {
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
}

.star {
    color: #FFD43B;
    margin-right: 0.25rem;
}

.book-details {
    padding: 1rem;
}

.book-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.5rem;
    color: #1f2937;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.book-author {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

/* Quotes list */
.quotes-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.quote-card {
    background-color: white;
    border-left: 4px solid #265073;
    border-radius: 8px;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.quote-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.quote-text {
    font-size: 1.125rem;
    font-style: italic;
    color: #1f2937;
    margin-bottom: 0.75rem;
    line-height: 1.6;
}

.quote-author {
    text-align: right;
    font-size: 0.875rem;
    font-weight: 600;
    color: #4b5563;
}

/* Empty state */
.empty-state, .initial-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 3rem 0;
}

.empty-icon, .initial-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
}

.empty-title, .initial-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.75rem;
}

.empty-text, .initial-text, .empty-suggestion {
    font-size: 1rem;
    color: #6b7280;
    margin: 0 0 0.5rem;
}

/* Highlight */
mark {
    background-color: rgba(255, 212, 59, 0.3);
    padding: 0.1em 0;
    border-radius: 2px;
    color: inherit;
}

/* Responsive */
@media (max-width: 768px) {
    .content-wrapper {
        padding: 1.5rem;
    }

    .search-header {
        flex-direction: column;
        gap: 1rem;
    }

    .results-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap:.5rem;
    }

    .book-image-container {
        height: 200px;
    }
}

@media (max-width: 480px) {
    .content-wrapper {
        padding: 1rem;
    }

    .title {
        font-size: 1.5rem;
    }

    .results-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .book-image-container {
        height: 180px;
    }
}
</style>
