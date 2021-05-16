<template>
    <p>
        <img
            v-if="field.thumbnailUrl"
            :src="field.thumbnailUrl"
            style="object-fit: cover;"
            class="w-8 h-8"
            :class="{ 'rounded-full': field.rounded, rounded: !field.rounded }"
            :alt="field.name"
        />
        <span v-else>&mdash;</span>
    </p>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
}
</script>
