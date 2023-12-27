<template>
  <NavBar />
  <div class="container">
   <div class="card" align="left">
     <div class="card-header">
       <h4>Book Details</h4>
       <RouterLink class="btn btn-secondary float-end" to="/book">Back</RouterLink>
     </div>
     <div class="card-body" v-if="Object.keys(book).length !== 0">
       <form>
         <div class="row">
    
           <div class="col-sm-6">
             <label>Name</label>
             <input type="text" v-model="book.name" name="name" id="name" class="form-control" />
           </div>
           <div class="col-sm-6">
             <label>Publisher</label>
             <input type="text" v-model="book.publisher" name="publisher" id="publisher" class="form-control" />
           </div>
         </div>
         <div class="row">
           <div class="col-sm-6">
             <label>ISBN</label>
             <input type="text" v-model="book.isbn" name="isbn" id="isbn" class="form-control" />
           </div>
           <div class="col-sm-6">
             <label>Category</label>
             <input type="text" v-model="book.category" name="category" id="category" class="form-control" />
           </div>
         </div>
         <div class="row">
           <div class="col-sm-6">
             <label>Sub Category</label>
             <input type="text" v-model="book.sub_category" name="subcategory" id="subcategory" class="form-control" />
           </div>
           <div class="col-sm-6">
             <label>Pages</label>
             <input type="text" v-model="book.pages" name="pages" id="pages" class="form-control" />
           </div>
         </div>
         <div class="row">
           <div class="col-sm-6">
             <label>Description</label>
             <textarea v-model="book.description" name="description" id="description" class="form-control"></textarea>
           </div>
           <div class="col-sm-6">
            <img :src="bookImage" alt="Book Image" style="width: 220px; height: 190px; object-fit: cover; object-position: center;" />
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
 </template>
  
  <script setup>
  import NavBar from '@/components/NavBar.vue';
  import Footer from '@/components/Footer.vue';
  import { ref, onMounted, watchEffect } from 'vue';
  import axios from 'axios';
  import { useRoute } from 'vue-router';
  
  let book = ref({});
  let bookImage = ref('');
  let bookId = ref(null);
  
  const route = useRoute();
  
  onMounted(() => {
   bookId.value = route.params.id;
   console.log('Book ID:', bookId.value);
   getBook();
  });
  
  const getBook = async () => {
   const token = localStorage.getItem('token');
   const response = await axios.get(`http://127.0.0.1:8000/api/book/${bookId.value}`, {
     headers: {
       'Authorization': `Bearer ${token}`
     }
   });
   console.log('API Response:', response.data);
   book.value = response.data.book;
   if (book.value && book.value.image) {
     bookImage.value = 'http://127.0.0.1:8000/' + book.value.image;
   }
  };
  
  watchEffect(() => {
   if (book.value && book.value.image) {
     bookImage.value = 'http://127.0.0.1:8000/' + book.value.image;
   }
  });
  </script>
  <style scoped>
  .body{
    padding-bottom: 70px;
  }
</style>
