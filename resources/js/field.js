Nova.booting((Vue, router) => {
    Vue.component('index-advanced-image-field', require('./components/IndexField'));
    Vue.component('detail-advanced-image-field', require('./components/DetailField'));
    Vue.component('form-advanced-image-field', require('./components/FormField'));
})
