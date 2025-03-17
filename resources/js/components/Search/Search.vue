<script>
import { AisConfigure, AisHighlight, AisHits, AisInstantSearch, AisSearchBox } from 'vue-instantsearch/vue3/es'
import { liteClient as algoliaSearch } from 'algoliasearch/lite'
import 'instantsearch.css/themes/algolia-min.css'
import { getPropertyByPath } from 'instantsearch.js/es/lib/utils'
import { ref } from 'vue'

export default {
    data() {
        return {
            searchClient: algoliaSearch(
                'N468XT7PJZ',
                '3c473fbd09058b9b7a8b313585180f21',
            ),
            search_content: ref('books'),
            tags: [
                'Tag 1',
                'Tag 2',
                'Tag 3',
                'Tag 4',
                'Tag 5',
                'Tag 6',
                'Tag 7',
                'Tag 8',
                'Tag 9',
                'Tag 10',
                'Tag 11',
                'Tag 12',
                'Tag 13',
                'Tag 14',
                'Tag 15',
                'Tag 16',
                'Tag 17',
                'Tag 18',
                'Tag 19',
                'Tag 2'],
            groups: [
                {
                    id: 1,
                    name: 'Group Name (Official or Unofficial)',
                    members: 234324,
                    activeHours: 2,
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vitae Donec viverra.',
                    image: 'https://img.freepik.com/free-photo/young-happy-students-studying-together-library_23-2148205098.jpg',
                },
                {
                    id: 2,
                    name: 'Group Name (Official or Unofficial)',
                    members: 154324,
                    activeHours: 1,
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vitae Donec viverra.',
                    image: 'https://img.freepik.com/free-photo/group-diverse-people-having-business-meeting_53876-25064.jpg',
                },
                {
                    id: 3,
                    name: 'Group Name (Official or Unofficial)',
                    members: 154320,
                    activeHours: 3,
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vitae Donec viverra.',
                    image: 'https://img.freepik.com/free-photo/people-meeting-office-1_53876-13861.jpg',
                },
                {
                    id: 4,
                    name: 'Group Name (Official or Unofficial)',
                    members: 194324,
                    activeHours: 4,
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vitae Donec viverra.',
                    image: 'https://img.freepik.com/free-photo/young-man-having-video-call-with-teacher_23-2148882530.jpg',
                },
            ],

        }
    },
    methods: {
        getPropertyByPath,
        changeContent(){
            if(this.search_content === 'books'){
                this.search_content = 'quotes'
            }else{
                this.search_content = 'books'
            }
            console.log(this.search_content)
        }
    },
    components: {
        AisInstantSearch,
        AisSearchBox,
        AisConfigure,
        AisHits,
        AisHighlight,
    },
};
</script>

<template>
    <main class="min-h-screen bg-primary p-6">
        <div class=" flex justify-between items-center mb-6 w-full">
            <span></span>
            <h1 class="text-3xl font-bold text-center">Search</h1>
            <div class="bg-dark_blue py-2 px-5 rounded-2xl">
                <button @click.prevent="changeContent" class="text-white ">{{ search_content === 'books' ? 'Quotes':'Books' }}</button>
            </div>
        </div>
        <section class="flex">
            <div class="w-2/3 ">
                <ais-instant-search v-if="search_content === 'books'"
                    :search-client="searchClient"
                    :index-name="search_content">
                    <ais-configure :hits-per-page.camel="20"></ais-configure>
                    <ais-search-box
                        class="m-5 p-3 rounded-2xl shadow-xl mb-5"></ais-search-box>
                    <ais-hits>
                        <template v-slot:item="{ item }">
                            <RouterLink :to="'book/'+ item.id">
                                <div class="search-result-card">
                                    <img :src="item.image" :alt="item.title" class="rounded-2xl h-40" />
                                    <div class="hit-title text-center text-sm mt-2">
                                        <ais-highlight attribute="title" :hit="item"></ais-highlight>
                                    </div>
                                </div>
                            </RouterLink>
                        </template>
                    </ais-hits>
                </ais-instant-search>
                <ais-instant-search v-else
                                    :search-client="searchClient"
                                    :index-name="search_content">
                    <ais-configure :hits-per-page.camel="15"></ais-configure>
                    <ais-search-box
                        class="m-5 p-3 rounded-2xl shadow-xl mb-5"></ais-search-box>
                    <ais-hits>
                        <template v-slot:item="{ item }">
                            <RouterLink :to="'quotes/'+ item.id">
                                <div class="search-result-card">
                                    <div class="hit-title text-center text-sm mt-2">
                                        <ais-highlight attribute="quote" :hit="item"></ais-highlight>
                                    </div>
                                </div>
                            </RouterLink>
                        </template>
                    </ais-hits>
                    <ais-pagination class="my-5"></ais-pagination>
                </ais-instant-search>
            </div>
            <div class="col-span-4 m-4">
                <div class="bg-orange p-6 rounded-2xl shadow-md">
                    <h2 class="text-xl font-semibold mb-4">Browse Groups by Tags</h2>
                    <div class="grid grid-cols-4 gap-2 bg-white p-4 rounded-2xl">
                        <button v-for="tag in tags" :key="tag"
                                class="bg-light_blue w-fit py-2 px-4 rounded-2xl text-sm text-white hover:bg-dark_blue">
                            {{ tag }}
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <section class="grid grid-cols-12 gap-8 ml-4">
            <div class="col-span-8">
                <h2 class="text-2xl font-semibold text-dark_blue mb-4">Featured Groups</h2>
                <div v-for="group in groups" :key="group.id" class="mb-6 bg-orange p-3 rounded-2xl shadow-md flex">
                    <div class="w-24 h-28 bg-gray-100 rounded-md overflow-hidden mr-4">
                        <img :src="group.image" alt="Group Image" class="w-full h-full object-cover" />
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">{{ group.name }}</h3>
                        <p class="text-gray-500">{{ group.members }} Members</p>
                        <p class="mt-2 text-gray-600">{{ group.description }}</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<style scoped>
*:focus {
    outline: none;
}
.ais-Hits-item {
    margin-left: 0;
    border: none !important;
    border-radius: 20px !important;
    width: 100%;
}
.search-result-card {
    border: none;
    box-shadow: none;
    padding: 0;
    margin: 0;
}

.ais-Hits-item img {
    margin-right: 1em
}

.ais-Hits-list {
    margin-top: 12rem !important;
}
</style>
