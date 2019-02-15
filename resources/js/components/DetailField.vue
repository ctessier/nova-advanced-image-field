<template>
    <panel-item :field="field">
        <div slot="value">
            <template v-if="shouldShowLoader">
                <image-loader :src="field.previewUrl" class="max-w-xs" @missing="(value) => missing = value" />
            </template>

            <template v-if="field.value && !field.previewUrl">
                {{ field.value }}
            </template>

            <span v-if="!field.value && !field.previewUrl">&mdash;</span>
            <span v-if="deleted">&mdash;</span>

            <p
                v-if="shouldShowToolbar"
                class="flex items-center text-sm mt-3"
            >
                <a
                    v-if="field.downloadable"
                    :dusk="field.attribute + '-download-link'"
                    @keydown.enter.prevent="download"
                    @click.prevent="download"
                    tabindex="0"
                    class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"
                >
                    <icon class="mr-2" type="download" view-box="0 0 24 24" width="16" height="16" />
                    <span class="class mt-1">
                        {{ __('Download') }}
                    </span>
                </a>
            </p>
        </div>
    </panel-item>
</template>

<script>
import ImageLoader from '@/components/Image/ImageLoader'

export default {
    props: ['field', 'resourceId', 'resourceName'],

    components: { ImageLoader },

    data: () => ({ missing: false, deleted: false }),

    methods: {
        /**
         * Download the linked image
         */
        download() {
            const { resourceName, resourceId } = this
            const attribute = this.field.attribute

            let link = document.createElement('a')
            link.href = `/nova-api/${resourceName}/${resourceId}/download/${attribute}`
            link.download = 'download'
            link.click()
        },
    },

    computed: {
        hasValue() {
            return (
                Boolean(this.field.value || this.field.previewUrl) &&
                !Boolean(this.deleted) &&
                !Boolean(this.missing)
            )
        },

        shouldShowLoader() {
            return !Boolean(this.deleted) && Boolean(this.field.previewUrl)
        },

        shouldShowToolbar() {
            return Boolean(this.field.downloadable || this.field.deletable) && this.hasValue
        },
    },
}
</script>
