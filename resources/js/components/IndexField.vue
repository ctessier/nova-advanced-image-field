<template>
  <div :class="alignmentClass" class="flex">
    <ImageLoader
      v-if="shouldShowLoader"
      :src="imageUrl"
      :max-width="field.maxWidth || field.indexWidth"
      :rounded="field.rounded"
      :aspect="field.aspect"
    />
    <span
      v-if="usesCustomizedDisplay && !imageUrl"
      class="break-words"
      v-tooltip="field.value"
    >
      {{ field.displayedAs }}
    </span>
    <p
      v-if="!usesCustomizedDisplay && !imageUrl"
      :class="`text-${field.textAlign}`"
      v-tooltip="field.value"
    >
      &mdash;
    </p>
  </div>
</template>

<script>
import { FieldValue } from 'laravel-nova'

export default {
  mixins: [FieldValue],
  props: ['viaResource', 'viaResourceId', 'resourceName', 'field'],

  data: () => ({
    loading: false,
  }),

  computed: {
    shouldShowLoader() {
      return this.imageUrl
    },

    imageUrl() {
      return this.field?.previewUrl || this.field?.thumbnailUrl
    },

    alignmentClass() {
      return {
        left: 'items-center justify-start',
        center: 'items-center justify-center',
        right: 'items-center justify-end',
      }[this.field.textAlign]
    },
  },
}
</script>
