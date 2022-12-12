import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((Vue) => {
    Vue.component('index-advanced-image-field', IndexField)
    Vue.component('detail-advanced-image-field', DetailField)
    Vue.component('form-advanced-image-field', FormField)
})
