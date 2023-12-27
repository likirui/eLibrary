<template>
  <div>
    <NavBar />
    <div class="container">
      <div class="card" align="left">
        <div class="card-header">
          <h4>User Details</h4>
        </div>
        <div class="card-body" v-if="Object.keys(user).length !== 0">
          <form>
            <div class="row">
              <div class="col-sm-6">
                <label>Name</label>
                <input type="text" v-model="user.name" name="name" id="name" class="form-control" />
              </div>
              <div class="col-sm-6">
                <label>Email</label>
                <input type="email" v-model="user.email" name="email" id="email" class="form-control" />
              </div>
            </div>
            <!-- Repeat the above div pattern for other fields as needed -->
            <div class="row">
              <div class="col-sm-6">
                <label>Physical Address</label>
                <input type="text" v-model="user.physical_address" name="physical_address" id="physical_address" class="form-control" />
              </div>
              <div class="col-sm-6">
                <label>Role</label>
                <select v-model="user.role" name="role" id="role" class="form-control">
                  <option value="user">User</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
            </div><br>
          </form>
        </div>
        <div class="card-body" v-else>
          Loading...
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script setup>
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';
import { ref, onMounted, watchEffect } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

let user = ref({});
let userId = ref(null);

const route = useRoute();

onMounted(() => {
  userId.value = route.params.id;
  console.log('User ID:', userId.value);
  getUser();
});

const getUser = async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.get(`http://127.0.0.1:8000/api/userdetail/${userId.value}`, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });
    console.log('API Response:', response.data);
    user.value = response.data.user;
  } catch (error) {
    console.error('Error fetching user:', error);
  }
};


</script>

<style scoped>
.body {
  padding-bottom: 70px;
}
</style>
