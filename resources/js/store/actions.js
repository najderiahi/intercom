import Echo from 'laravel-echo';


const title = document.title;
const audio = new Audio("/notification.mp3");
const updateTitle = (conversations) => {
    let unread = Object.values(conversations).reduce((acc, conversations) => conversations.unread + acc, 0)
    if(unread === 0) {
        document.title = title
    } else {
        document.title = `(${unread}) ${title}`
    }
}

const fetchApi = async (url, options) => {
    let response = await fetch(url, {
        headers: {
            "X-Requested-With" : "XMLHttpRequest",
            "Content-Type" : "application/json",
            "X-CSRF-TOKEN" : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        ...options
    })

    if(response.ok) {
        return response.json();
    } else {
        const error = await response.json()
        throw Error(error);
    }
};


export default {
    async loadConversations (context) {
        let response = await fetchApi("/api/conversations");
        context.commit("addConversation", {conversations: response.conversations});
    },
    async loadConversationMessages(context, conversationId) {
        console.log({id: conversationId})
        context.commit('openConversation', parseInt(conversationId, 10));
        if(!context.getters.conversation(conversationId).loaded) {
            let response = await fetchApi("/api/conversation/"+conversationId);
            context.commit("addMessages", {messages: response.messages, id: conversationId, count: response.count})
        }

        context.getters.messages(conversationId).forEach(message => {
             if( message.read_at === null && message.receiver_id === context.state.user ) {
                 context.dispatch('markAsRead', message)
             }
        });
        context.commit("markAsRead", conversationId);
        updateTitle(context.state.conversations)
    },
    async sendMessage (context, {content, userId}) {
        let response = await fetchApi('/api/conversations/'+userId, {
            method: "POST",
            body: JSON.stringify({content: content})
        });

        context.commit("addMessage", {message: response.message, conversationId: userId})
    },
    setUser(context, user) {
        context.commit("setUser", user)

        new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ":6001",
        })
            .private(`App.User.${user}`)
            .listen("NewMessage", function (e) {
                context.commit('addMessage', {message: e.message, conversationId: e.message.sender_id})
                if(!context.state.openedConversations.includes(e.message.sender_id) || document.hidden) {
                    context.commit('incrementUnread', e.message.sender_id)
                    audio.play()
                    updateTitle(context.state.conversations)
                } else {
                    context.dispatch('markAsRead', e.message)
                }
            });
    },
    markAsRead(context, message) {
        fetchApi('/api/messages/'+message.id, {method: 'PATCH'})
        context.commit('readMessage', message)
    },
    async loadPreviousMessages(context, {conversationId}) {
        let message = context.getters.messages(conversationId)[0]
        if(message) {
            let url = `/api/conversation/${conversationId}?before=${message.created_at}`;
            let response = await fetchApi(url);
            context.commit('prependMessages', {id: conversationId, messages: response.messages})
        }
    }
}
