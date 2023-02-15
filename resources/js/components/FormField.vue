<template>
  <DefaultField
    :field="currentField"
    :label-for="labelFor"
    :errors="errors"
    :show-help-text="!isReadonly && showHelpText"
    :full-width-content="fullWidthContent"
  >
    <template #field>
      <!-- Existing Image -->
      <div class="space-y-4">
        <div
          v-if="hasValue && previewFile && files.length == 0"
          class="grid grid-cols-4 gap-x-6 gap-y-2"
        >
          <FilePreviewBlock
            v-if="previewFile"
            :file="previewFile"
            :removable="shouldShowRemoveButton"
            @removed="confirmRemoval"
            :rounded="field.rounded"
            :dusk="field.attribute + '-delete-link'"
          />
        </div>

        <!-- Upload Removal Modal -->
        <ConfirmUploadRemovalModal
          :show="removeModalOpen"
          @confirm="removeUploadedFile"
          @close="closeRemoveModal"
        />

        <div v-if="file && field.croppable" class="w-full">
          <advanced-cropper
            v-if="file"
            :file="file"
            :aspectRatio="field.aspectRatio"
            @removed="removeFile"
            @changed="handleCropChange"
            ref="cropper"
          />
        </div>

        <!-- DropZone -->
        <DropZone
          v-if="shouldShowField"
          @change="handleFileChange"
          @file-changed="handleFileChange"
          :files="field.croppable ? [] : files"
          @file-removed="removeFile"
          :rounded="field.rounded"
          :accepted-types="field.acceptedTypes"
          :disabled="file?.processing"
          :dusk="field.attribute + '-delete-link'"
          :input-dusk="field.attribute"
        />
      </div>
    </template>
  </DefaultField>
</template>

<script>
import { DependentFormField, Errors, HandlesValidationErrors } from 'laravel-nova'

import AdvancedCropper from './AdvancedCropper.vue'
import 'vue-advanced-cropper/dist/theme.compact.css';

function createFile(file) {
  return {
    name: file.name,
    extension: file.name.split('.').pop(),
    type: file.type,
    originalFile: file,
  }
}

export default {
  emits: ['file-deleted'],

  props: [
    'resourceId',
    'relatedResourceName',
    'relatedResourceId',
    'viaRelationship',
  ],

  mixins: [HandlesValidationErrors, DependentFormField],

  components: { AdvancedCropper },

  data: () => ({
    previewFile: null,
    file: null,
    removeModalOpen: false,
    missing: false,
    deleted: false,
    uploadErrors: new Errors(),
    uploadProgress: 0,
    startedDrag: false,

    uploadModalShown: false,
  }),

  async mounted() {
    this.preparePreviewImage()

    this.field.fill = formData => {
      let attribute = this.field.attribute

      if (this.file) {
        formData.append(attribute, this.file.originalFile, this.file.name)
        if (this.field.croppable) {
          const cropResult = this.$refs.cropper.$refs.cropper.getResult()
          formData.append(`${attribute}_data`, JSON.stringify(cropResult))
        }
      }
    }
  },

  methods: {
    preparePreviewImage() {
      if (this.hasValue && this.imageUrl) {
        this.fetchPreviewImage()
      }

      if (this.hasValue && !this.imageUrl) {
        this.previewFile = createFile({
          name: this.currentField.value,
          type: this.currentField.value.split('.').pop(),
        })
      }
    },

    async fetchPreviewImage() {
      let response = await fetch(this.imageUrl)
      let data = await response.blob()

      this.previewFile = createFile(
        new File([data], this.currentField.value, { type: data.type })
      )
    },

    handleFileChange(newFiles) {
      this.file = createFile(newFiles[0])
    },

    removeFile() {
      this.file = null
    },

    confirmRemoval() {
      this.removeModalOpen = true
    },

    closeRemoveModal() {
      this.removeModalOpen = false
    },

    async removeUploadedFile() {
      this.uploadErrors = new Errors()

      const {
        resourceName,
        resourceId,
        relatedResourceName,
        relatedResourceId,
        viaRelationship,
      } = this
      const attribute = this.field.attribute

      const uri =
        this.viaRelationship &&
        this.relatedResourceName &&
        this.relatedResourceId
          ? `/nova-api/${resourceName}/${resourceId}/${relatedResourceName}/${relatedResourceId}/field/${attribute}?viaRelationship=${viaRelationship}`
          : `/nova-api/${resourceName}/${resourceId}/field/${attribute}`

      try {
        await Nova.request().delete(uri)
        this.closeRemoveModal()
        this.deleted = true
        this.$emit('file-deleted')
        Nova.success(this.__('The image was deleted!'))
      } catch (error) {
        this.closeRemoveModal()

        if (error.response?.status === 422) {
          this.uploadErrors = new Errors(error.response.data.errors)
        }
      }
    },
  },

  computed: {
    files() {
      return this.file ? [this.file] : []
    },

    /**
     * Determine if the field has an upload error.
     */
    hasError() {
      return this.uploadErrors.has(this.fieldAttribute)
    },

    /**
     * Return the first error for the field.
     */
    firstError() {
      if (this.hasError) {
        return this.uploadErrors.first(this.fieldAttribute)
      }
    },

    /**
     * The ID attribute to use for the file field.
     */
    idAttr() {
      return this.labelFor
    },

    /**
     * The label attribute to use for the file field.
     */
    labelFor() {
      let name = this.resourceName

      if (this.relatedResourceName) {
        name += '-' + this.relatedResourceName
      }

      return `advanced-image-${name}-${this.field.attribute}`
    },

    /**
     * Determine whether the field has a value.
     */
    hasValue() {
      return (
        Boolean(this.field.value || this.imageUrl) &&
        !Boolean(this.deleted) &&
        !Boolean(this.missing)
      )
    },

    /**
     * Determine whether the field should show the loader component.
     */
    shouldShowLoader() {
      return !Boolean(this.deleted) && Boolean(this.imageUrl)
    },

    /**
     * Determine whether the file field input should be shown.
     */
    shouldShowField() {
      return Boolean(!this.currentlyIsReadonly)
    },

    /**
     * Determine whether the field should show the remove button.
     */
    shouldShowRemoveButton() {
      return Boolean(this.currentField.deletable && !this.currentlyIsReadonly)
    },

    /**
     * Return the preview or thumbnail URL for the field.
     */
    imageUrl() {
      return this.currentField.previewUrl || this.currentField.thumbnailUrl
    },
  },
}
</script>
