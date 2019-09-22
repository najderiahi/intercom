import Vue from 'vue';
import VueRouter from 'vue-router';

import LoadedConversation from '../components/LoadedConversation'

Vue.use(VueRouter)

let $conversations = document.querySelector("#conversations");


let router = null
if($conversations)  {
    const routes = [
        {path: "/"},
        {path: "/:id", name: "conversation", component: LoadedConversation}

    ];

    router = new VueRouter({
        mode: 'history',
        routes,
        base: $conversations.getAttribute('data-base')
    });
}

export default router;
