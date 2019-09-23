import Vue from 'vue'
import Vuex from 'vuex'
import actions from './actions'
import mutations from './mutations'

Vue.use(Vuex)

export default new Vuex.Store({
    strict: true,
    state: {
        user: null,
        openedConversations: [],
        conversations: {}
    },
    getters: {
        conversations (state)  {
            return state.conversations
        },
        messages (state) {
            return function (id)  {
                const conversation = state.conversations[id];
                if(conversation && conversation.messages) {
                    return conversation.messages
                } else {
                    return []
                }
            }
        },
        conversation (state) {
            return function(id) {
                return state.conversations[id] || {}
            }
        },
        user (state) {
            return state.user;
        }
    },
    mutations: mutations,
    actions: actions
});
