<template>
  <NavBar />
   <body>
     <div class="container">
   <div class="row">
     <!-- Main Body -->
       <h2 class="mb-4">Admin > Dashboard</h2>
       </div>
       </div>

       <!-- Four Cards -->
       <div class="container">
 <div class="row">
 <div class="col">
  <div class="card bg-primary text-white">
    <div class="card-body">
      <h5 class="card-title">Total Books</h5>
      <p class="card-text">{{ totals.totalBooks }}</p>
    </div>
  </div>
 </div>
 <div class="col">
  <div class="card bg-secondary text-white">
    <div class="card-body">
      <h5 class="card-title">Borrowed Books</h5>
      <p class="card-text">{{ totals.borrowedBooks }}</p>
    </div>
  </div>
 </div>
 <div class="col">
  <div class="card bg-success text-white">
    <div class="card-body">
      <h5 class="card-title">Pending Approval</h5>
      <p class="card-text">{{ totals.pendingApproval }}</p>
    </div>
  </div>
 </div>
 <div class="col">
  <div class="card bg-danger text-white">
    <div class="card-body">
      <h5 class="card-title">Total Users</h5>
      <p class="card-text">{{ totals.totalUsers }}</p>
    </div>
  </div>
 </div>
 </div>
</div>
<br>
<div class="container">
 <div class="row">
   <div class="col-sm-6" v-if="loaded">
     <h4>Users registered per Month Graph</h4>
 <Bar :data="chartData" />
</div>
<div class="col-sm-6" v-if="loaded">
 <h4>Books borrowed per Month Graph</h4>
 <Bar :data="chartData2" />
</div>
</div>
</div>
 </body>
 <Footer />
</template>

<script>
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';
import axios from 'axios';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
 components: {
   NavBar,
   Footer,
   Bar
 },
 data() {
   return {
     loaded: false,
     totals: {
       totalBooks: 0,
       borrowedBooks: 0,
       pendingApproval: 0,
       totalUsers: 0,
     },
     chartData: {
       labels: [],
       datasets: [
         {
           label: 'Total borrowed books',
           backgroundColor: '#f87979',
           data: []
         }
       ]
     },
     chartData2: {
       labels: [],
       datasets: [
         {
           label: 'Total users registered',
           backgroundColor: '#007bff',
           data: []
         }
       ]
     }
   }
 },
 async mounted() {
   try {
     const token = localStorage.getItem('token');
     
     const response1 = await axios.get(`https://ebrary.000webhostapp.com/api/totalborrowedbooks`, {
       headers: {
         'Authorization': `Bearer ${token}`
       }
     });
     this.chartData.labels = response1.data.months;
     this.chartData.datasets[0].data = response1.data.counts;
    

     const response2 = await axios.get(`https://ebrary.000webhostapp.com/api/totalregisteredusers`, {
       headers: {
         'Authorization': `Bearer ${token}`
       }
     });
     this.chartData2.labels = response2.data.months;
     this.chartData2.datasets[0].data = response2.data.counts;
     console.log("Month: ", response2.data.months);
     
     const response = await axios.get(`https://ebrary.000webhostapp.com/api/gettotals`, {
       headers: {
         'Authorization': `Bearer ${token}`
       }
     });
     this.totals = response.data;
     this.loaded = true;
   } catch (error) {
     console.error('Error fetching data:', error);
   }
 },
 name: 'BarChart',
}
</script>


<style scoped>
body {
 padding-bottom: 80px; 
}
</style>
