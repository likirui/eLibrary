<template>
    <NavBar />
    <div class="container">
     <div class="card">
         <div class="card-header">
           <h4>Library Books</h4>
           <RouterLink class="btn btn-primary float-end" to="/addbook">Add Book</RouterLink>
         </div>
         <div class="card-body">
            <!-- Search Input -->
            <div class="form-group">
             <input type="text" class="form-control" placeholder="Search..." v-model="searchTerm">
            </div>
            <!-- Table -->
            <table class="table table-bordered">
              <thead>
                 <tr>
                 <th>ID</th>
                 <th>Name</th>
                 <th>Category</th>
                 <th>Publisher</th>
                 <th>ISBN</th>
                 <th>Date Created</th>
                 <th>Action</th>
             </tr>
              </thead>
              <tbody v-if="filteredBooks.length > 0">
                 <tr v-for="(book, index) in filteredBooks" :key="index">
                <td>{{ book.id }}</td>
                <td>{{ book.name }}</td>
                <td>{{ book.category }}</td>
                <td>{{ book.publisher }}</td>
                <td>{{ book.isbn }}</td>
                <td>{{ formatDate(book.created_at) }}</td>
                <td> 
                    <RouterLink class="btn btn-success me-2" :to="{ name: 'viewbook', params: { id: book.id } }">View</RouterLink>
                     <RouterLink class="btn btn-danger" :to="{ name: 'editbook', params: { id: book.id } }">Edit</RouterLink>
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
    <Footer />
   </template>
 
 <script setup>
 import 'bootstrap/dist/css/bootstrap.min.css';
 import 'bootstrap-icons/font/bootstrap-icons.css';
 import 'font-awesome/css/font-awesome.min.css';
 import 'bootstrap';
 </script>
   <script>
   import NavBar from '@/components/NavBar.vue';
   import Footer from '@/components/Footer.vue';
  import axios from 'axios'
  
  export default {
    name: 'book',
    data(){
        return {
            books : [],
            searchTerm: ''
        }
    },
    computed: {
     filteredBooks() {
       return this.books.filter(book => 
         book.name.includes(this.searchTerm) || 
         book.category.includes(this.searchTerm) || 
         book.publisher.includes(this.searchTerm) || 
         book.isbn.includes(this.searchTerm)
       );
     }
    },
    mounted (){
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
            console.log(token);
            axios.get('http://127.0.0.1:8000/api/books', {
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
 