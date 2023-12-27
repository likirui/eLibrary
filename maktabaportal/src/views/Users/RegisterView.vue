<template>
  <NavBar />
  <body>
  <div class="container">
  <div class="card" align="left">
      <div class="card-header">
        <h4>Create User</h4>
        <RouterLink class="btn btn-secondary float-end" to="/users">Back</RouterLink>
      </div>
      <div class="card-body"> 
          <form @submit.prevent="saveData"> 
              <div class="row">
                 <div class="col-md-6">
                     <label>First Name</label>
                     <input type="text" v-model="student.name" name="name" id="name" class ="form-control"/> 
                 </div>
                 <div class="col-md-6">
                     <label>Email</label>
                     <input type="email" v-model="student.email" name="email" id="email" class ="form-control"/> 
                 </div>
              </div>
              <!-- Repeat the above div pattern for other fields as needed -->
              <div class="row">
                 <div class="col-md-6">
                     <label>Physical Address</label>
                     <input type="text" v-model="student.physical_address" name="physical_address" id="physical_address" class ="form-control"/> 
                 </div>
                 <div class="col-md-6">
                     <label>Password</label>
                     <input type="password" v-model="student.password" name="password" id="password" class ="form-control"/> 
                 </div>
              </div>
              <div class="row">
                 <div class="col-md-6">
                     <label>Role</label>
                     <select v-model="student.role" name="role" id="role" class="form-control">
                         <option value="user">User</option>
                         <option value="admin">Admin</option>
                     </select>
                 </div>
              </div><br>
              <div class="row">
                <div class="col-md-8">
              <input type="submit" value="Save" class="btn btn-lg btn-primary float-end">
            </div> 
            </div>
          </form>
      </div>
  </div>
</div>
</body>
<Footer />
</template>

 
 <script setup>
  import NavBar from '@/components/NavBar.vue';
   import Footer from '@/components/Footer.vue';
 import 'bootstrap/dist/css/bootstrap.min.css';
   import 'bootstrap-icons/font/bootstrap-icons.css';
   import 'font-awesome/css/font-awesome.min.css';
   import 'bootstrap';
 import axios from 'axios';
 import { useRouter } from 'vue-router';
 
 let student = {
    name: '',
    email: '',
    'role': '',
    'physical_address':'',
    password: ''
 };
 const router = useRouter();

 const saveData = async () => {
 try {
    const token = localStorage.getItem('token');
  const response = await axios.post("http://127.0.0.1:8000/api/register", student, {
   headers: {
     'Content-Type': 'application/json',
     'Authorization': `Bearer ${token}`
   }
 });
 //console.log(response.data);
 //alert("User saved successfully");
 router.push({ name: 'users' });
 } catch (err) {
 if (err.response && err.response.status === 422) {
   // Validation failed
   const errors = err.response.data.errors;
   // Handle errors as needed
   let errorMessage = '';
   for (let field in errors) {
     errors[field].forEach(error => {
       errorMessage += `Validation failed for ${field}: ${error}\n`;
     });
   }
   alert(errorMessage);
 } else {
   // Other errors
   alert("An error occurred: " + err.message);
 }
 }
};

 </script>