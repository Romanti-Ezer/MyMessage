import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from './components/Home';
import Messages from './components/Messages';
import MessagesCreate from './components/MessagesCreate';
import MessagesEdit from './components/MessagesEdit';
import MessagesShow from './components/MessagesShow';

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        { path: '/', component: Home },
        { path: '/messages', component: Messages },
        { path: '/messages/create', component: MessagesCreate },
        { path: '/messages/:id', component: MessagesShow },
        { path: '/messages/:id/edit', component: MessagesEdit },
    ],
    mode: 'history'
})