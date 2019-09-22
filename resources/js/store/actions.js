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

        if(!context.getters.conversation(conversationId).loaded) {
            let response = await fetchApi("/api/conversation/"+conversationId);
            context.commit("addMessages", {messages: response.messages, id: conversationId, count: response.count})
            context.commit("markAsRead", conversationId);
        }
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
    },
    async loadPreviousMessages(context, {conversationId}) {
        let message = context.getters.messages(conversationId)[0]
        console.log({message})
        if(message) {
            let url = `/api/conversation/${conversationId}?before=${message.created_at}`;
            let response = await fetchApi(url);
            context.commit('prependMessages', {id: conversationId, messages: response.messages})
        }
    }
}
