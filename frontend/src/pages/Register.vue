<template>
  <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
      <h3 class="text-center mb-4">Register</h3>

      <form @submit.prevent="register">
        <div v-if="successMessage" class="alert alert-success text-center">
          {{ successMessage }}
        </div>

        <input
            v-model="form.name"
            class="form-control mb-3"
            placeholder="Name"
            required
        />
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
        <input
            v-model="form.password_confirmation"
            class="form-control mb-3"
            placeholder="Confirm Password"
            type="password"
            required
        />
        <button class="btn btn-success w-100" :disabled="loading">
          {{ loading ? 'Registering...' : 'Register' }}
        </button>
      </form>

      <p class="mt-3 text-center">
        Already have an account?
        <router-link to="/login">Login</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});
const successMessage = ref('');
const loading = ref(false);

async function register() {
  try {
    loading.value = true;
    await axios.post('/api/register', form.value);
    successMessage.value = 'Registration successful! Redirecting...';
    setTimeout(() => router.push('/login'), 2000);
  } catch (err) {
    alert('Registration failed');
  } finally {
    loading.value = false;
  }
}
</script>
