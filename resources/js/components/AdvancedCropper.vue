<template>
  <div class="h-full flex items-start justify-center">
    <div class="relative w-full">
      <!-- Remove Button -->
      <RemoveButton
        class="absolute z-20 top-[-10px] right-[-9px]"
        @click.stop="handleRemoveClick"
        v-tooltip="__('Remove')"
        :dusk="$attrs.dusk"
      />

      <div
        class="bg-gray-50 relative flex items-center justify-center border-2 border-gray-200 dark:border-gray-700 overflow-hidden rounded-lg"
      >
        <!-- Upload Overlay -->
        <div
          v-if="file.processing"
          class="absolute inset-0 flex items-center justify-center"
        >
          <Loader class="text-white z-10" />
          <div class="bg-primary-900 opacity-75 absolute inset-0" />
        </div>

        <!-- Cropper -->
        <cropper
          v-if="isImage"
          ref="cropper"
          class="cropper object-scale-down"
          :src="previewUrl"
          :stencil-props="{ aspectRatio }"
        ></cropper>
      </div>

      <!-- File Information -->
      <p class="font-semibold text-xs mt-1">{{ file.name }}</p>
    </div>
  </div>
</template>

<script setup>
import { useFilePreviews } from '@/composables/useFilePreviews'
import { toRef } from 'vue'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'

const emit = defineEmits(['removed'])
const props = defineProps({
  file: { type: Object },
  aspectRatio: { type: Number },
})
const components = { Cropper }
const { previewUrl, isImage } = useFilePreviews(toRef(props, 'file'))
const handleRemoveClick = () => emit('removed')
</script>

<script>
export default {
  inheritAttrs: false,
}
</script>
