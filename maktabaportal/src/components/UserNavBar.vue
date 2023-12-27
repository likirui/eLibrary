<template>
    <header>
        <div class="wrapper">
          <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
              <RouterLink class="navbar-brand" to="/userdashboard">eLibrary</RouterLink>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                 
                  <li class="nav-item">
                    <RouterLink class="nav-link" to="/requestbook">Request Book</RouterLink>
                  </li>
                  <li class="nav-item">
                    <RouterLink class="nav-link" to="/issuedbooks">Issued Books</RouterLink>
                  </li>
                  <li class="nav-item">
                    <RouterLink class="nav-link" :to="`/myprofile/${userId}`">My Profile</RouterLink>
                  </li>
                  <li class="nav-item">
                    <RouterLink class="nav-link" to="#" @click.prevent="logout">Logout</RouterLink>
                  </li>
                </ul>
              </div>
            </div>
          </nav>
        </div>
        </header>
    </template>
    
    <script>
    import axios from 'axios';
    import { ref } from 'vue';
    const userData = JSON.parse(localStorage.getItem('user'));
    
    export default {
      name: 'ViewUser',
     computed: {
       userId() {
         return JSON.parse(localStorage.getItem('user')).id;
       }
     },
     name: 'LogoutButton',
     setup() {
       
       const userRole = ref(userData.role);
       console.log('Role:', userRole.value);
    
       return { userRole };
     },
     methods: {
      async logout() {
      const userId = JSON.parse(localStorage.getItem('user')).id;
      const token = localStorage.getItem('token');
      try {
        await axios.post(`http://127.0.0.1:8000/api/userlogout/${userId}`, {}, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        location.reload(); // Force a full page reload
        this.$router.push('/');
      } catch (err) {
        console.error("An error occurred during logout: ", err.message);
        this.$router.push('/');
      }
     },
    },
    };
    </script>
    
    <style scoped>
    /* Adjust the default color of the links */
    .navbar-nav .nav-link {
      color: #fff !important; /* White for default */
    }
    
    /* Add the following styles for hover and active effects */
    .navbar-nav .nav-link:hover {
      background-color: #007bff !important; /* Blue shade for hover */
      color: #fff !important; /* White for hover */
    }
    
    .navbar-nav .nav-link.active {
      background-color: #0056b3 !important; /* Darker blue for active */
      color: #fff !important; /* White for active */
    }
    </style>