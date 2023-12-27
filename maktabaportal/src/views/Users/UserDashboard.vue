<template>
    <UserNavBar />
     <body>
       <div class="container">
     <div class="row">
       <!-- Main Body -->
         <h2 class="mb-4">User > Dashboard</h2>
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
        <h5 class="card-title">Total Penalty</h5>
        <p class="card-text">Ksh. 0</p>
      </div>
    </div>
   </div>
   </div>
  </div>
  <br>
  <div class="container">
   <div class="row">
     <div class="col-sm-6" v-if="loaded">
       <h4>Total Penalty per Month Graph</h4>
   <Bar :data="chartData3" />
  </div>
  <div class="col-sm-6" v-if="loaded">
   <h4>Books borrowed per Month Graph</h4>
   <Bar :data="chartData4" />
  </div>
  </div>
  </div>
   </body>
   <Footer />
  </template>
  
  <script>
  import UserNavBar from '@/components/UserNavBar.vue';
  import Footer from '@/components/Footer.vue';
  import { Bar } from 'vue-chartjs';
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';
  import axios from 'axios';
  
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);
  
  export default {
   components: {
     UserNavBar,
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
       chartData3: {
         labels: [],
         datasets: [
           {
             label: 'Total penalty in ksh',
             backgroundColor: '#f87979',
             data: []
           }
         ]
       },
       chartData4: {
         labels: [],
         datasets: [
           {
             label: 'Total borrowed books',
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
       let user = JSON.parse(localStorage.getItem('user'));
         let userId = user.id;
       const response4 = await axios.get(`http://127.0.0.1:8000/api/userborrowedbooks/${userId}`, {
         headers: {
           'Authorization': `Bearer ${token}`
         }
       });
       this.chartData4.labels = response4.data.months;
       this.chartData4.datasets[0].data = response4.data.counts;
      
  
       const response3 = await axios.get(`http://127.0.0.1:8000/api/usertotalpenalty/${userId}`, {
         headers: {
           'Authorization': `Bearer ${token}`
         }
       });
       this.chartData3.labels = response3.data.months;
       this.chartData3.datasets[0].data = response3.data.counts;
       console.log("Month: ", response3.data.months);
       
       const response = await axios.get(`http://127.0.0.1:8000/api/usertotals/${userId}`, {
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
  