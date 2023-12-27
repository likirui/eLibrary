<template>
    <UserNavBar />
    <div class="container">
    <div class="card" align="left">
      <div class="card-header">
        <h4>Extend Book</h4>
        <RouterLink class="btn btn-secondary float-end" to="/issuedbooks">Back</RouterLink>
      </div>
      <div class="card-body">
        <form @submit.prevent="saveData">
          <div class="row">
            <div class="col-sm-6">
              <label>Return Date</label>
              <input type="date" v-model="book.due_date" name="name" id="name" class="form-control" />
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
              <input type="submit" value="Submit" class="btn btn-lg btn-primary float-end">
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
   due_date: ''
  };
    
  const router = useRouter();
  const loanId = router.currentRoute.value.params.id;
  console.log("BookID:", loanId);
  const saveData = async () => {
   try {
    const token = localStorage.getItem('token');
    let formData = new FormData();
    formData.append('due_date', book.due_date);
    formData.append('added_by', added_by);
    
    const response = await axios.post(`http://127.0.0.1:8000/api/extendbook/${loanId}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${token}`
      }
    });
    console.log(response.data);
    alert("Book Loan extended successfully");
    router.push({ name: 'issuedbooks' });
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
    import UserNavBar from '@/components/UserNavBar.vue';
     import Footer from '@/components/Footer.vue';
  </script>