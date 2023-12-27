<template>
  <div>
    <NavBar />
    <body>
      <div class="container">
        <div class="card" align="left">
          <div class="card-header">
            <h4>Edit User Details</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="updateData">
              <div class="row">
                <div class="col-md-6">
                  <label>First Name</label>
                  <input type="text" v-model="student.name" name="name" id="name" class="form-control" />
                </div>
                <div class="col-md-6">
                  <label>Email</label>
                  <input type="email" v-model="student.email" name="email" id="email" class="form-control" />
                </div>
              </div>
              <!-- Repeat the above div pattern for other fields as needed -->
              <div class="row">
                <div class="col-md-6">
                  <label>Physical Address</label>
                  <input type="text" v-model="student.physical_address" name="physical_address" id="physical_address" class="form-control" />
                </div>
                <div class="col-md-6">
                  <label>Password</label>
                  <input type="password" v-model="student.password" name="password" id="password" class="form-control" />
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
                  <input type="submit" value="Update" class="btn btn-md btn-success float-end">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </body>
    <Footer />
  </div>
</template>

<script setup>
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'font-awesome/css/font-awesome.min.css';
import 'bootstrap';
import axios from 'axios';
import { ref, onMounted } from 'vue';

import { useRoute, useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();
let student = ref({
  name: '',
  email: '',
  role: '',
  physical_address: '',
  password: ''
});

const userId =  route.params.id;

onMounted(async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.get(`http://127.0.0.1:8000/api/userdetail/${userId}`, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });
    student.value = response.data.user;
  } catch (error) {
    console.error('Error fetching user:', error);
  }
});

const updateData = async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.put(`http://127.0.0.1:8000/api/edituser/${userId}`, student.value, {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    });
    console.log(response.data);
    alert("User updated successfully");
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
