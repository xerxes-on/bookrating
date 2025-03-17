<script setup>
const props = defineProps({
    type: {
        type: String,
        default: 'text',
    },
    label: {
        type: String,
        required: true,
    },
})
</script>

<template>
    <div class="input">
        <label :for="props.label">{{ props.label }}:</label>
        <input :label="props.label" :type="props.type" class="rounded-2xl p-2 bg-primary m-5 w-1/3 border-dark_blue" />
    </div>
</template>

<style scoped></style>
