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
    },
    openConversation(state, conversationId) {
        state.openedConversations = [conversationId];
    },
    readMessage(state, message) {
        let conversation = state.conversations[message.sender_id]
        if(conversation && conversation.messages) {
            let msg = conversation.messages.find(m => m.id === message.id)
            if (msg) {
                msg.read_at = (new Date()).toISOString()
            }
        }
    },
    incrementUnread(state, conversationId) {
        let conversation = state.conversations[conversationId];
        if(conversation) {
            console.log(conversation.unread)
            conversation.unread++;
        }
    }
}
