<template>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>📝 Tasks</h2>
      <button class="btn btn-outline-danger" @click="logout">Logout</button>
    </div>

    <!-- Alert Message -->
    <div v-if="message.text" :class="['alert', {
      'alert-success': message.type === 'success',
      'alert-danger': message.type === 'error'
    }]" role="alert">
      {{ message.text }}
    </div>

    <!-- Task Form -->
    <form @submit.prevent="submitTask" class="row g-2 mb-4">
      <div class="col-md-4">
        <input v-model="form.title" class="form-control" placeholder="Title" required />
      </div>
      <div class="col-md-4">
        <input v-model="form.body" class="form-control" placeholder="Body" required />
      </div>
      <div class="col-md-4">
        <button class="btn btn-primary w-100" type="submit">
          {{ editing ? 'Update Task' : 'Add Task' }}
        </button>
      </div>
    </form>

    <!-- Tasks Table -->
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Completed</th>
          <th>Title</th>
          <th>Body</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(task, index) in tasks" :key="task.id">
          <td>{{ index + 1 }}</td>
          <td class="text-center">
            <input type="checkbox" v-model="task.is_completed" @change="toggleComplete(task)" />
          </td>
          <td :class="{ 'text-decoration-line-through': task.is_completed }">
            {{ task.title }}
          </td>
          <td>{{ task.body }}</td>
          <td>
            <button class="btn btn-sm btn-warning me-2" @click="editTask(task)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="deleteTask(task.id)">Delete</button>
          </td>
        </tr>
        <tr v-if="tasks.length === 0">
          <td colspan="5" class="text-center text-muted">No tasks found.</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const tasks = ref([]);
const editing = ref(false);
const form = ref({ id: null, title: '', body: '', is_completed: false });
const message = ref({ type: '', text: '' });

function setMessage(type, text) {
  message.value = { type, text };
  setTimeout(() => (message.value = { type: '', text: '' }), 3000);
}

async function fetchTasks() {
  try {
    const res = await axios.get('/api/tasks');
    tasks.value = res.data.data;
  } catch (err) {
    console.error(err);
    if (err.response && err.response.status === 401) {
      router.push('/login');
    }
  }
}

async function submitTask() {
  try {
    if (editing.value) {
      const res = await axios.put(`/api/tasks/${form.value.id}`, form.value);
      const index = tasks.value.findIndex(t => t.id === form.value.id);
      tasks.value.splice(index, 1, res.data.data);
      editing.value = false;
      setMessage('success', 'Task updated successfully');
    } else {
      const res = await axios.post('/api/tasks', form.value);
      tasks.value.unshift(res.data.data);
      setMessage('success', 'Task created successfully');
    }
    resetForm();
  } catch (err) {
    console.error(err);
    setMessage('error', 'Failed to save task');
  }
}

function editTask(task) {
  form.value = { ...task };
  editing.value = true;
}

async function toggleComplete(task) {
  try {
    await axios.put(`/api/tasks/${task.id}`, task);
    setMessage('success', `Task marked as ${task.is_completed ? 'completed' : 'incomplete'}`);
  } catch (err) {
    console.error(err);
    setMessage('error', 'Failed to update completion status');
  }
}

async function deleteTask(id) {
  try {
    await axios.delete(`/api/tasks/${id}`);
    tasks.value = tasks.value.filter(t => t.id !== id);
    setMessage('success', 'Task deleted successfully');
  } catch (err) {
    console.error(err);
    setMessage('error', 'Failed to delete task');
  }
}

function resetForm() {
  form.value = { id: null, title: '', body: '', is_completed: false };
}

async function logout() {
  try {
    await axios.post('/api/logout');
  } catch (error) {
    console.error('Logout failed', error);
  } finally {
    localStorage.removeItem('token');
    router.push('/login');
  }
}

onMounted(() => fetchTasks());
</script>

<style scoped>
.text-decoration-line-through {
  text-decoration: line-through;
}
</style>
