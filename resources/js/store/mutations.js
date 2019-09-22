export default {
    addConversation (state, {conversations}) {
        conversations.forEach((c) => {
            let conversation = state.conversations[c.id] || {messages: [], count: 0}
            conversation = {...conversation, ...c}
            state.conversations = {...state.conversations, ...{[c.id]: conversation}}
        });
    },
    addMessages(state, {messages, id, count}) {
        let conversation = state.conversations[id] || {}
        conversation.messages = messages
        conversation.count = count
        conversation.loaded = true
        state.conversations = {...state.conversations, ...{[id]: conversation}}
    },
    addMessage(state, {message, conversationId}) {
        state.conversations[conversationId].count++;
        state.conversations[conversationId].messages.push(message)
    },
    prependMessages(state, {id, messages}) {
        let conversation = state.conversations[id] || {}
        conversation.messages = [...messages, ...conversation.messages]
        state.conversations = {...state.conversations, ...{[id]: conversation}}
    },
    markAsRead(state, conversationId) {
        state.conversations[conversationId].unread = 0
    },
    setUser(state, userId) {
        state.user = userId;
    }
}
