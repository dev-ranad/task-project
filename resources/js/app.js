import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import "@mdi/font/css/materialdesignicons.css";
import router from './router';
import './bootstrap';
import "bootstrap/dist/css/bootstrap.min.css";
import App from './App.vue';

Vue.use(Vuetify);

const vuetify = new Vuetify({
    theme: {
        themes: {
            light: {
                primary: "#e7f0ff",
                secondary: "#4b71e8",
                accent: "#82B1FF",
                error: "#FF5252",
                info: "#2196F3",
                success: "#4CAF50",
                warning: "#FFC107",
                white: "#ffffff",
                black: "#000000",
            },
        },
    },
    icons: {
        iconfont: "mdi",
    },
});


new Vue({
  render: h => h(App),
  router,
  vuetify,
}).$mount('#app');
