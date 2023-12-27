<template>
  <NavBar />
  <div class="container">
  <div class="card" align="left">
    <div class="card-header">
      <h4>Edit Book Details</h4>
      <RouterLink class="btn btn-danger float-end" to="/book">Back</RouterLink>
    </div>
    <div class="card-body">
      <form @submit.prevent="updateBook" enctype="multipart/form-data">
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
            <input type="text" v-model="book.sub_category" name="sub_category" id="sub_category" class="form-control" />
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
            <input type="submit" value="Update" class="btn btn-lg btn-primary float-end">
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
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

let user = JSON.parse(localStorage.getItem('user'));
let added_by = user.id;
let book = ref({});
let bookId = ref(null);
const imageFile = ref(null);

const route = useRoute();
const router = useRouter();

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
};

const handleFileUpload = (event) => {
  imageFile.value = event.target.files[0];
};

const updateBook = async () => {
  try {
    const token = localStorage.getItem('token');
    let formData = new FormData();
    formData.append('name', book.value.name);
    formData.append('publisher', book.value.publisher);
    formData.append('isbn', book.value.isbn);
    formData.append('category', book.value.category);
    formData.append('sub_category', book.value.sub_category);
    formData.append('description', book.value.description);
    formData.append('pages', book.value.pages);
    formData.append('added_by', added_by);

    if (imageFile.value) {
      formData.append('image', imageFile.value);
    }

    const response = await axios.post(`http://127.0.0.1:8000/api/book/${bookId.value}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${token}`
      }
    });

    console.log(response.data.book);
    alert("Book updated successfully");
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
