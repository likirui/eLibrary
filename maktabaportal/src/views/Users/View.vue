<template>
    <NavBar />
    <div class="container">
      <div class="card">
          <div class="card-header">
            <h4>Library Users</h4>
            <RouterLink class="btn btn-primary float-end" to="/register">Add User</RouterLink>
          </div>
          <div class="card-body">
              <input type="text" v-model="searchTerm" placeholder="Search users...">
              <table class="table table-bordered">
               <thead>
                  <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Action</th>
              </tr>
               </thead>
               <tbody v-if="filteredUsers.length > 0">
                  <tr v-for="(user, index) in filteredUsers" :key="index">
                 <td>{{ user.id }}</td>
                 <td>{{ user.name }}</td>
                 <td>{{ user.email }}</td>
                 <td>{{ user.role }}</td>
                 <td>  
                  <RouterLink class="btn btn-success  me-2" :to="{ name: 'viewuser', params: { id: user.id } }">View</RouterLink>
                  <RouterLink class="btn btn-danger" :to="{ name: 'edituser', params: { id: user.id } }">Edit</RouterLink>
              </td>
                  </tr>
               </tbody>
               <tbody v-else>
                  <tr>
                 <td colspan="7">No users found...</td>
               </tr>
               </tbody>
              </table>
          </div>
      </div>
     </div>
    <Footer />
  </template>
  
  <script setup>
     import 'bootstrap/dist/css/bootstrap.min.css';
     import 'bootstrap-icons/font/bootstrap-icons.css';
     import 'font-awesome/css/font-awesome.min.css';
     import 'bootstrap';
  </script>
  
  <script>
  import axios from 'axios'
  import NavBar from '@/components/NavBar.vue';
  import Footer from '@/components/Footer.vue';
  
  export default {
     name: 'user',
     data(){
         return {
             users : [],
             searchTerm: ''
         }
     },
     computed: {
      filteredUsers() {
          if (this.searchTerm) {
              return this.users.filter(user => {
                  return user.name.toLowerCase().includes(this.searchTerm.toLowerCase());
              });
          } else {
              return this.users;
          }
      }
     },
     mounted (){
         this.getUsers();
     },
     methods: {
         getUsers () {
             const token = localStorage.getItem('token');
             console.log(token);
             axios.get('http://127.0.0.1:8000/api/users', {
                 headers: {
                    'Authorization': `Bearer ${token}`
                 }
             }).then(res => {
              this.users = res.data.users; 
         console.log(this.users);
             });
         }
     },
  }
  </script>
  