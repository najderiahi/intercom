import Vue from 'vue';
import FileUploader from './components/FileUploader';
import UsersTable from './components/UsersTable';

require('./bootstrap');


const app = new Vue({
    el: '#app',
    components: {FileUploader, UsersTable}
});
