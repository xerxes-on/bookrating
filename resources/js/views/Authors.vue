<script setup>
import { ref, onMounted, computed } from 'vue';
import Layout from "@/components/common/Layout.vue";
import { RouterLink } from 'vue-router';
import authorApi from '@/api/author';

// State
const authors = ref([]);
const loading = ref(false);
const page = ref(1);
const reachedEnd = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);
const searching = ref(false);

// Computed properties
const displayedAuthors = computed(() => {
    return searchQuery.value ? searchResults.value : authors.value;
});

// Methods
const fetchAuthors = async () => {
    if (loading.value || reachedEnd.value) return;

    loading.value = true;
    try {
        const response = await authorApi.getAuthors(page.value);

        // Add new authors to the list
        const newAuthors = response.data.data || [];
        authors.value = [...authors.value, ...newAuthors];

        // Check if we've reached the end
        if (newAuthors.length === 0) {
            reachedEnd.value = true;
        } else {
            page.value++;
        }
    } catch (error) {
        console.error('Error fetching authors:', error);
    } finally {
        loading.value = false;
    }
};

const searchAuthors = async () => {
    if (!searchQuery.value.trim()) {
        searchResults.value = [];
        return;
    }

    searching.value = true;
    try {
        const response = await authorApi.searchAuthors(searchQuery.value);
        searchResults.value = response.data.data || [];
    } catch (error) {
        console.error('Error searching authors:', error);
        searchResults.value = [];
    } finally {
        searching.value = false;
    }
};

const loadMore = () => {
    if (!searchQuery.value) {
        fetchAuthors();
    }
};

const formatDate = (dateString) => {
    if (!dateString) return 'Unknown';
    return new Date(dateString).toLocaleDateString();
};

// Initialize
onMounted(() => {
    fetchAuthors();
});
</script>

<template>
    <Layout>
        <div class="authors-container">
            <h1 class="page-title">Authors</h1>

            <!-- Search bar -->
            <div class="search-container">
                <input
                    v-model="searchQuery"
                    @input="searchAuthors"
                    type="text"
                    placeholder="Search authors..."
                    class="search-input"
                />
                <div v-if="searching" class="search-loading">
                    Searching...
                </div>
            </div>

            <!-- Authors grid -->
            <div class="authors-grid">
                <div v-for="author in displayedAuthors" :key="author.id" class="author-card">
                    <RouterLink :to="`/author/${author.id}`" class="author-link">
                        <div class="author-image-container">
                            <img
                                :src="'images/author.jpeg'"
                                :alt="author.name"
                                class="author-image"
                                @error="$event.target.src = '/images/default-author.jpg'"
                            />
                        </div>
                        <div class="author-info">
                            <h3 class="author-name">{{ author.name }}</h3>
                            <p class="author-dates">
                                {{ formatDate(author.date_of_birth) }} -
                                {{ author.date_died ? formatDate(author.date_died) : 'Present' }}
                            </p>
                            <p class="author-books">{{ author.written_books.length }} Books</p>
                        </div>
                    </RouterLink>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="displayedAuthors.length === 0 && !loading" class="empty-state">
                <p>{{ searchQuery ? 'No authors found matching your search.' : 'No authors available.' }}</p>
            </div>

            <!-- Load more button -->
            <div class="load-more-container" v-if="!searchQuery && !reachedEnd">
                <button
                    @click="loadMore"
                    :disabled="loading"
                    class="load-more-button !bg-orange_dark"
                >
                    {{ loading ? 'Loading...' : 'Load More Authors' }}
                </button>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
.authors-container {
    padding: 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-title {
    font-size: 2rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.search-container {
    margin-bottom: 2rem;
    position: relative;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
}

.search-loading {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 0.875rem;
}

.authors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.author-card {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.author-card:hover {
    transform: translateY(-5px);
}

.author-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.author-image-container {
    height: 200px;
    overflow: hidden;
}

.author-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.author-info {
    padding: 1rem;
}

.author-name {
    font-size: 1.25rem;
    margin: 0 0 0.5rem;
    font-weight: 600;
}

.author-dates, .author-books {
    font-size: 0.875rem;
    color: #666;
    margin: 0.25rem 0;
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
    background-color: #4a6cf7;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.load-more-button:hover:not(:disabled) {
    background-color: #3a5be8;
}

.load-more-button:disabled {
    background-color: #a2b0f9;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .authors-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}

@media (max-width: 480px) {
    .authors-grid {
        grid-template-columns: 1fr;
    }
}
</style>
