<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import { RouterLink } from 'vue-router'

const props = defineProps({
    suggestedBooks: {
        type: Array,
        required: true,
    }
})

const currentIndex = ref(0)
const slideContainer = ref(null)
const isAnimating = ref(false)
const timer = ref(null)

const visibleSlides = 3
const gapSize = 8 // 2px on each side

const slideWidth = computed(() => {
    return `calc(${100 / visibleSlides}% - ${gapSize}px)`
})

const containerStyle = computed(() => {
    return {
        transform: `translateX(-${currentIndex.value * (100 / visibleSlides)}%)`
    }
})

function next() {
    if (isAnimating.value) return
    isAnimating.value = true

    currentIndex.value++

    // If we've gone past the last slide, loop back
    if (currentIndex.value > props.suggestedBooks.length - visibleSlides) {
        setTimeout(() => {
            // Disable transition temporarily
            slideContainer.value.style.transition = 'none'
            currentIndex.value = 0

            // Force reflow
            slideContainer.value.offsetHeight

            // Re-enable transition
            slideContainer.value.style.transition = 'transform 0.5s ease'
            isAnimating.value = false
        }, 500)
    } else {
        setTimeout(() => {
            isAnimating.value = false
        }, 500)
    }
}

function prev() {
    if (isAnimating.value) return
    isAnimating.value = true

    currentIndex.value--

    if (currentIndex.value < 0) {
        setTimeout(() => {
            // Disable transition temporarily
            slideContainer.value.style.transition = 'none'
            currentIndex.value = props.suggestedBooks.length - visibleSlides

            // Force reflow
            slideContainer.value.offsetHeight

            // Re-enable transition
            slideContainer.value.style.transition = 'transform 0.5s ease'
            isAnimating.value = false
        }, 500)
    } else {
        setTimeout(() => {
            isAnimating.value = false
        }, 500)
    }
}

function startAutoplay() {
    timer.value = setInterval(() => {
        next()
    }, 2000)
}

function stopAutoplay() {
    if (timer.value) {
        clearInterval(timer.value)
    }
}

onMounted(() => {
    startAutoplay()
})

onBeforeUnmount(() => {
    stopAutoplay()
})
</script>

<template>
    <div class="custom-slider" @mouseenter="stopAutoplay" @mouseleave="startAutoplay">
        <div class="slider-wrapper" ref="slideContainer" :style="containerStyle">
            <div v-for="book in suggestedBooks" :key="book.id" class="slide" :style="{ width: slideWidth }">
                <RouterLink :to="`/book/${book.id}`" class="slide-link">
                    <img :src="book.image" alt="book image" class="slide-image" />
                </RouterLink>
            </div>
        </div>

        <button @click="prev" class="nav-button prev">❮</button>
        <button @click="next" class="nav-button next">❯</button>
    </div>
</template>

<style scoped>
.custom-slider {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: hidden;
}

.slider-wrapper {
    display: flex;
    height: 100%;
    transition: transform 0.5s ease;
}

.slide {
    flex-shrink: 0;
    height: 100%;
    padding: 0 2px;
    box-sizing: border-box;
}

.slide-link {
    display: block;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.slide-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 16px;
}

.nav-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.5);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    font-size: 18px;
    cursor: pointer;
    z-index: 10;
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}
</style>
