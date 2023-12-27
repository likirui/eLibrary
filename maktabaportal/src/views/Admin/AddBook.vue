<template>
  <NavBar />
  <div class="container">
  <div class="card" align="left">
    <div class="card-header">
      <h4>Add Book</h4>
      <RouterLink class="btn btn-secondary float-end" to="/book">Back</RouterLink>
    </div>
    <div class="card-body">
      <form @submit.prevent="saveData">
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
            <input type="text" v-model="book.subcategory" name="subcategory" id="subcategory" class="form-control" />
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
            <label>Upload Cover Image</label>
            <input type="file" @change="handleFileUpload" name="image" id="image" accept="image/*" class="form-control" />
          </div>
        </div><br>
        <div class="row">
          <div class="col-sm-8">
            <input type="submit" value="Save" class="btn btn-lg btn-primary float-end">
          </div>
        </div>
      </form>
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
import axios from 'axios';
import { useRouter } from 'vue-router';
let user = JSON.parse(localStorage.getItem('user'));
let added_by = user.id;
let book = {
 name: '',
 publisher: '',
 isbn: '',
 category: '',
 subcategory:'',
 description: '',
 image: null, // Add this line
 pages: ''
};

const handleFileUpload = (event) => {
 book.image = event.target.files[0];
};

const router = useRouter();
const saveData = async () => {
 try {
  const token = localStorage.getItem('token');
  let formData = new FormData();
  formData.append('name', book.name);
  formData.append('publisher', book.publisher);
  formData.append('isbn', book.isbn);
  formData.append('category', book.category);
  formData.append('subcategory', book.subcategory);
  formData.append('description', book.description);
  formData.append('pages', book.pages);
  formData.append('image', book.image);
  formData.append('added_by', added_by);

  const response = await axios.post("http://127.0.0.1:8000/api/addbook", formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
      'Authorization': `Bearer ${token}`
    }
  });
  //console.log(response.data);
  //alert("Book saved successfully");
  router.push({ name: 'book' });
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
 <script>
  import NavBar from '@/components/NavBar.vue';
   import Footer from '@/components/Footer.vue';
</script>