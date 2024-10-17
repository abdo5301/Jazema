//
// /**
//  * First we will load all of this project's JavaScript dependencies which
//  * includes Vue and other libraries. It is a great starting point when
//  * building robust, powerful web applications using Vue and Laravel.
//  */
//
// require('./bootstrap');
//
// window.Vue = require('vue');
//
// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */
//
// Vue.component('example', require('./components/Example.vue'));
//
// const app = new Vue({
//     el: '#app'
// });
//
//
import Echo from "laravel-echo"

window.io = require('socket.io-client');
let token = document.head.querySelector('meta[name="csrf-token"]');
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
    reconnectionAttempts: 5,
    csrfToken: token.content
});
//console.log('online');
window.Echo.join('online')
    .here((users) => {
        console.log('here');

        console.log(users);
        users.forEach(function (user) {
           // $('#online_users').append(`<li class="list-group-item">${user.name}</li>`);
        });

    })
    .joining((user) => {
        console.log(user.name);
    })
    .leaving((user) => {
        console.log(user.name);
    });


$('#chat_text').keypress(function (e){
    if(e.which == 13){
        e.preventDefault();
        let message = $(this).val();
        let url = $(this).data('url');
        console.log(url);
    }

    });

