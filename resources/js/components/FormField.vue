<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <image-viewer :field="field" />

            <span class="form-file mr-4">
                <input
                    ref="fileField"
                    :dusk="field.attribute"
                    class="form-file-input"
                    type="file"
                    :id="idAttr"
                    name="name"
                    @change="fileChange"
                />
                <label :for="labelFor" class="form-file-btn btn btn-default btn-primary">
                    {{__('Choose File')}}
                </label>
            </span>
            <span class="text-gray-50">
                {{ currentLabel }}
            </span>

            <VueCropper
                v-show="imgSrc"
                ref='cropper'
                :guides="true"
                :view-mode="2"
                drag-mode="crop"
                :auto-crop-area="0.5"
                :min-container-width="250"
                :min-container-height="180"
                :background="true"
                :rotatable="true"
                :src="imgSrc"
                alt=""
            />

            <p v-if="hasError" class="text-xs mt-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import ImageViewer from './ImageViewer'
import VueCropper from 'vue-cropperjs';
import { FormField, HandlesValidationErrors, Errors } from 'laravel-nova'

export default {
    props: ['resourceId', 'relatedResourceName', 'relatedResourceId', 'viaRelationship'],

    mixins: [HandlesValidationErrors, FormField],

    components: { ImageViewer, VueCropper },

    data: () => ({
        file: null,
        label: 'no file selected',
        fileName: '',
        uploadErrors: new Errors(),
        imgSrc: '',
    }),

    mounted() {
        this.field.fill = formData => {
            if (this.file) {
                formData.append(this.field.attribute, this.file, this.fileName)
                formData.append(this.field.attribute + '_data', JSON.stringify(this.$refs.cropper.getData(true)))
            }
        }
    },

    methods: {

        fill(formData) {
            this.field.fill(formData)
        },

        /**
         * Responsd to the file change
         */
        fileChange(e) {
            let path = e.target.value
            let fileName = path.match(/[^\\/]*$/)[0]
            this.fileName = fileName
            this.file = this.$refs.fileField.files[0]

            const file = e.target.files[0];
            if (!file.type.includes('image/')) {
                alert('Please select an image file');
                return;
            }

            if (typeof FileReader === 'function') {
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.imgSrc = event.target.result;
                    // rebuild cropperjs with the updated source
                    this.$refs.cropper.replace(event.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                alert('Sorry, FileReader API not supported');
            }
        },
    },

    computed: {
        hasError() {
            return this.uploadErrors.has(this.fieldAttribute)
        },

        firstError() {
            if (this.hasError) {
                return this.uploadErrors.first(this.fieldAttribute)
            }
        },

        /**
         * The current label of the file field
         */
        currentLabel() {
            return this.fileName || this.label
        },

        /**
         * The ID attribute to use for the file field
         */
        idAttr() {
            return this.labelFor
        },

        /**
         * The label attribute to use for the file field
         * @return {[type]} [description]
         */
        labelFor() {
            return `file-${this.field.attribute}`
        },
    },
}
</script>
