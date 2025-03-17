<script setup></script>
<template>
    <section>
        <div class="star">
            <div class="corner corner--top-left"></div>
            <div class="corner corner--top-right"></div>
            <div class="corner corner--bottom-right"></div>
            <div class="corner corner--bottom-left"></div>
        </div>
    </section>
</template>
<style scoped>
section {
    width: 100vw;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f6ffde;
}

.star {
    height: 22vh;
    width: 12vw;
    position: relative;
    animation: rotate 2s ease infinite;
}

.corner {
    height: 48%;
    width: 48%;
    position: absolute;
    background-color: #a4c0ed;
    animation: radius 2s ease infinite;
}

.corner--top-left {
    top: 0;
    left: 0;
}

.corner--top-right {
    top: 0;
    right: 0;
}

.corner--bottom-right {
    bottom: 0;
    right: 0;
}

.corner--bottom-left {
    bottom: 0;
    left: 0;
}

@keyframes rotate {
    to {
        transform: rotate(360deg);
    }
}

@keyframes radius {
    0% {
        border-radius: 15%;
    }
    50% {
        border-radius: 55%;
    }
    100% {
        border-radius: 15%;
    }
}

* {
    box-sizing: border-box;
}
</style>
