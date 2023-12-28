import './assets/main.css'
import "@popperjs/core";

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axiosInstance from './axiosInstance'
import { useRouter } from 'vue-router'


const app = createApp(App)

app.use(router)

app.mount('#app')

/*createApp(App)
 .use(router)
 .mount('#app')

axiosInstance.interceptors.response.use(
 response => response,
 error => {
   if (error.response.status === 401) {
     const router = useRouter();
     router.push('/');
   }
   return Promise.reject(error);
 }
);*/
