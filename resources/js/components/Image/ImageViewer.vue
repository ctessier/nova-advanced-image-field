<template>
    <div v-if="hasValue" class="mb-6">
        <template v-if="shouldShowLoader">
            <image-loader :src="field.previewUrl" class="max-w-xs" @missing="(value) => missing = value" />
        </template>

        <template v-if="field.value && !field.previewUrl">
            <card class="flex item-center relative border border-lg border-50 overflow-hidden p-4">
                {{ field.value }}

                <Button
                    type="delete"
                    class="ml-auto"
                    v-if="shouldShowRemoveButton"
                    @click="confirmRemoval"
                />
            </card>
        </template>

        <p
            v-if="field.previewUrl"
            class="mt-3 flex items-center text-sm"
        >
            <Button
                type="delete"
                v-if="shouldShowRemoveButton"
                @click="confirmRemoval"
            >
                <span class="class ml-2 mt-1">
                    {{__('Delete')}}
                </span>
            </Button>
        </p>

        <portal to="modals">
            <transition name="fade">
                <confirm-upload-removal-modal
                    v-if="removeModalOpen"
                    @confirm="removeFile"
                    @close="closeRemoveModal"
                />
            </transition>
        </portal>
    </div>
</template>

<script>
import { Errors } from 'laravel-nova'

import Button from '@/components/Button/Button'
import ImageLoader from '@/components/Image/ImageLoader'

export default {
    components: { Button, ImageLoader },

    props: ['field', 'resourceId', 'resourceName', 'relatedResourceId', 'relatedResourceName', 'viaRelationship'],

    data: () => ({
        removeModalOpen: false,
        missing: false,
        deleted: false,
    }),

    methods: {
        /**
         * Confirm removal of the linked file
         */
        confirmRemoval() {
            this.removeModalOpen = true
        },

        /**
         * Close the upload removal modal
         */
        closeRemoveModal() {
            this.removeModalOpen = false
        },

        /**
         * Remove the linked file from storage
         */
        async removeFile() {
            this.uploadErrors = new Errors()

            const {
                resourceName,
                resourceId,
                relatedResourceName,
                relatedResourceId,
                viaRelationship,
            } = this
            const attribute = this.field.attribute

            const uri = this.viaRelationship
                ? `/nova-api/${resourceName}/${resourceId}/${relatedResourceName}/${relatedResourceId}/field/${attribute}?viaRelationship=${viaRelationship}`
                : `/nova-api/${resourceName}/${resourceId}/field/${attribute}`

            try {
                await Nova.request().delete(uri)
                this.closeRemoveModal()
                this.deleted = true
                this.$emit('image-deleted')
            } catch (error) {
                this.closeRemoveModal()

                if (error.response.status == 422) {
                    this.uploadErrors = new Errors(error.response.data.errors)
                }
            }
        },
    },

    computed: {
        /**
         * Determine whether the field has a value
         */
        hasValue() {
            return (
                Boolean(this.field.value || this.field.previewUrl) &&
                !Boolean(this.deleted) &&
                !Boolean(this.missing)
            )
        },

        /**
         * Determine whether the field should show the loader component
         */
        shouldShowLoader() {
            return !Boolean(this.deleted) && Boolean(this.field.thumbnailUrl)
        },

        /**
         * Determine whether the field should show the remove button
         */
        shouldShowRemoveButton() {
            return Boolean(this.field.deletable)
        },
    },
}
</script>
