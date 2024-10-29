<template>
  <div class="container max-w-[46rem] mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 text-center">Todo App</h1>
    <TaskForm
      :task-to-edit="taskToEdit"
      @task-added="addTask"
      @task-updated="updateTask"
      @edit-cancelled="cancelEdit"
    />
    <TaskList
      :tasks="tasks"
      @task-updated="updateTask"
      @task-deleted="deleteTask"
      @edit-task="editTask"
      @edit-cancelled="cancelEdit"

    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import TaskForm from './components/TaskForm.vue';
import TaskList from './components/TaskList.vue';

const tasks = ref([]);
const taskToEdit = ref(null);

/**
 * Fetches the list of tasks from the server.
 * 
 * Sends a GET request to the '/api/tasks' endpoint and updates the tasks
 * reference with the retrieved data. If the request fails, logs the error
 * and displays an alert message.
 */
const fetchTasks = async () => {
  try {
    const response = await axios.get('/api/tasks');
    tasks.value = response.data;
  } catch (error) {
    console.error('Error fetching tasks:', error);
    alert('Failed to fetch tasks. Please try again later.');
  }
};

/**
 * Adds a new task to the task list.
 * 
 * @param {Object} newTask The new task to add.
 */
const addTask = (newTask) => {
  tasks.value.unshift(newTask);
};

/**
 * Updates an existing task in the task list.
 * 
 * Finds the task with the same ID as the updatedTask and replaces it.
 * Resets the taskToEdit reference to null after updating.
 * 
 * @param {Object} updatedTask The task with updated information.
 */
const updateTask = (updatedTask) => {
  const index = tasks.value.findIndex(task => task.id === updatedTask.id);
  if (index !== -1) {
    tasks.value[index] = updatedTask;
  }
  taskToEdit.value = null;
};

/**
 * Deletes a task from the task list.
 * 
 * Finds the task with the specified ID and removes it from the task list.
 * 
 * @param {number} taskId The ID of the task to delete.
 */
const deleteTask = (taskId) => {
  tasks.value = tasks.value.filter(task => task.id !== taskId);
};

/**
 * Sets the task to be edited.
 * 
 * Copies the specified task to the taskToEdit reference,
 * allowing it to be edited in the TaskForm component.
 * 
 * @param {Object} task The task to be edited.
 */
const editTask = (task) => {
  taskToEdit.value = { ...task };
};

/**
 * Cancels the editing process by resetting the taskToEdit reference to null.
 */
const cancelEdit = () => {
  taskToEdit.value = null;
};

onMounted(fetchTasks);
</script>