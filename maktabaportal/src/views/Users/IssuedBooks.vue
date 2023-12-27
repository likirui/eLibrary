<template>
  <UserNavBar />
  <body>
  <div class="container">
   <div class="card">
       <div class="card-header">
         <h4>Issued Books</h4>
       </div>
       <div class="card-body">
         <input type="text" v-model="searchQuery" placeholder="Search..." class="form-control mb-3" />
           <table class="table table-bordered">
            <thead>
               <tr>
               <th>ID</th>
               <th>Name</th>
               <th>Category</th>
               <th>ISBN</th>
               <th>Requested By</th>
               <th>Date Requested</th>
               <th>Return Date</th>
               <th>Action</th>
           </tr>
            </thead>
            <tbody v-if="filteredBooks.length >0">
               <tr v-for="(book, index) in filteredBooks" :key="index">
              <td>{{ book.id }}</td>
              <td>{{ book.book.name }}</td>
              <td>{{ book.book.category }}</td>
              <td>{{ book.book.isbn  }}</td>
              <td>{{ book.user.name }}</td>
              <td>{{ formatDate(book.created_at) }}</td>
              <td>{{ formatDate(book.due_date) }}</td>
              <td>  
                  <RouterLink class="btn btn-success me-2" :to="{ name: 'extendbook', params: { id: book.id } }">Extend Book</RouterLink>
           </td>  
               </tr>
            </tbody>
            <tbody v-else>
               <tr>
              <td colspan="7">Loading...</td>
            </tr>
            </tbody>
           </table>
       </div>
   </div>
  </div>
  </body>
  <Footer />
  </template>
  
  <script setup>
  import 'bootstrap/dist/css/bootstrap.min.css';
  import 'bootstrap-icons/font/bootstrap-icons.css';
  import 'font-awesome/css/font-awesome.min.css';
  import 'bootstrap';
  </script>
  
  <script>
   import UserNavBar from '@/components/UserNavBar.vue';
   import Footer from '@/components/Footer.vue';
   import axios from 'axios';
  
   export default {
     name: 'book',
     data(){
       return {
         books: [],
         searchQuery: ''
       }
     },
     computed: {
       filteredBooks() {
         if(this.searchQuery) {
           return this.books.filter(book => book.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
         } else {
           return this.books;
         }
       }
     },
     mounted() {
       this.getBooks();
     },
     methods: {
       formatDate(dateString) {
         const date = new Date(dateString);
         const day = date.getDate();
         const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
         const month = monthNames[date.getMonth()];
         const year = date.getFullYear();
         return `${day} ${month} ${year}`;
       },
       getBooks () {
         const token = localStorage.getItem('token');
         //console.log(token);
         let user = JSON.parse(localStorage.getItem('user'));
         let userId = user.id;
         axios.get(`http://127.0.0.1:8000/api/myloans/${userId}`, {
           headers: {
             'Authorization': `Bearer ${token}`
           }
         }).then(res => {
           this.books = res.data.books;
           console.log(this.books);
         });
       }
     },
   }
  </script>
  