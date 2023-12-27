import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import StudentView from '../views/Users/View.vue'
import RegisterView from '../views/Users/RegisterView.vue'
import LoginView from '../views/Users/LoginView.vue'
import BookView from '../views/Admin/BookView.vue'
import AddBook from '../views/Admin/AddBook.vue'
import Issuance from '../views/Admin/Issuance.vue'
import Return from '../views/Admin/Return.vue'
import EditBook from '../views/Admin/EditBook.vue';
import ViewBook from '../views/Admin/ViewBook.vue';
import ApproveBook from '../views/Admin/ApproveBook.vue';
import ViewUser from '../views/Users/ViewUser.vue';
import EditUser from '../views/Users/EditUser.vue';
import IssuedBooks from '../views/Users/IssuedBooks.vue';
import RequestBook from '../views/Users/RequestBook.vue';
import ExtendBook from '../views/Users/ExtendBook.vue';
import UserDashboard from '../views/Users/UserDashboard.vue';
import MyProfile from '../views/Users/MyProfile.vue';


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/home',
      name: 'home',
      component: HomeView
    },
    {
      path: '/userdashboard',
      name: 'userdashboard',
      component: UserDashboard
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/users',
      name: 'users',
      component: StudentView
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/book',
      name: 'book',
      component: BookView
    },
    {
      path: '/addbook',
      name: 'addbook',
      component: AddBook
    },
    {
      path: '/issuance',
      name: 'issuance',
      component: Issuance
    },
    {
      path: '/return',
      name: 'return',
      component: Return
    },
    {
      path: '/editbook/:id',
      name: 'editbook',
      component: EditBook
    },
    {
      path: '/viewbook/:id',
      name: 'viewbook',
      component: ViewBook
    },
    {
      path: '/viewuser/:id',
      name: 'viewuser',
      component: ViewUser
    },
    {
      path: '/myprofile/:id',
      name: 'myprofile',
      component: MyProfile
    },
    {
      path: '/edituser/:id',
      name: 'edituser',
      component: EditUser
    },
    {
      path: '/requestbook',
      name: 'requestbook',
      component: RequestBook
    },
    {
      path: '/issuedbooks',
      name: 'issuedbooks',
      component: IssuedBooks
    },
    {
      path: '/approvebook/:id',
      name: 'approvebook',
      component: ApproveBook
    },
    {
      path: '/extendbook/:id',
      name: 'extendbook',
      component: ExtendBook
    }
  ]
})

export default router
