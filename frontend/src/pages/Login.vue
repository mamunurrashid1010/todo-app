<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
      <h3 class="text-center mb-4">Login</h3>

      <form @submit.prevent="login">
        <div v-if="successMessage" class="alert alert-success text-center">
          {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="alert alert-danger text-center">
          {{ errorMessage }}
        </div>

        <input
            v-model="form.email"
            class="form-control mb-3"
            placeholder="Email"
            type="email"
            required
        />
        <input
            v-model="form.password"
            class="form-control mb-3"
            placeholder="Password"
            type="password"
            required
        />

        <button class="btn btn-primary w-100" :disabled="loading">
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>

      <p class="mt-3 text-center">
        Don't have an account?
        <router-link to="/register">Register</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({ email: '', password: '' });
const loading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

async function login() {
  successMessage.value = '';
  errorMessage.value = '';
  loading.value = true;

  try {
    const response = await axios.post('/api/login', form.value);
    const token = response.data.access_token;

    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    localStorage.setItem('token', token);

    successMessage.value = 'Login successful! Redirecting...';
    setTimeout(() => {
      router.push('/tasks');
    }, 2000);
  } catch (err) {
    console.error(err);
    errorMessage.value = err.response?.data?.message || 'Login failed. Please try again.';
  } finally {
    loading.value = false;
  }
}
</script>
