<template>
   <div class="login-form-container">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="header">
       <h1 class="text-center">eLibrary</h1>
          </div>
          <div class="card-header">
            <h4 class="text-center">Login</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="LoginData">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" v-model="student.email" class="form-control" placeholder="Email">
              </div>
              <br>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" v-model="student.password" class="form-control" placeholder="Password">
              </div>
              <button type="submit" class="btn btn-primary btn-block login-button">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 </template>
   
   <script>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
 name: 'Registration',
 setup() {
   const student = ref({
     email: '',
     password: ''
   });

   const router = useRouter();

   const LoginData = async () => {
     try {
       const response = await axios.post("http://127.0.0.1:8000/api/login", {
         email: student.value.email,
         password: student.value.password
       }, {
         headers: {
           'Content-Type': 'application/json'
         }
       });

       //console.log(response.data.user);

       if (response.status === 200) {
         //alert("Login Successfully"); 
         // Store the JWT token in local storage or session storage
         localStorage.setItem('token', response.data.authorization.token);
         localStorage.setItem('user', JSON.stringify(response.data.user));
         //router.push({ name: 'home' });
          // Redirect users based on their roles
           if (response.data.user.role === 'admin') {
       router.push({ name: 'home' });
            } else {
       router.push({ name: 'userdashboard' });
          }
       } else {
         alert("Login password/username is wrong");
       }
     } catch (err) {
       alert("Error, please try again");
     } 
   };

   return { student, LoginData };
 }
}
</script>
<style scoped src="@/assets/login.css"></style>

