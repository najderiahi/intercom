require('./bootstrap');

import Vue from 'vue';
import FileUploader from './components/FileUploader';
import UsersTable from './components/UsersTable';
import AnnonceList from './components/AnnonceList';
import CreateAnnonce from './components/CreateAnnonce'
import ConversationsComponent from "./components/ConversationsComponent";
import store from './store/store';
import router from './router/router';
const app = new Vue({
    el: '#app',
    components: {
        FileUploader, UsersTable, AnnonceList, CreateAnnonce, ConversationsComponent
    },
    store,
    router

});
