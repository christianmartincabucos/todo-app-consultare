<template>
  <form @submit.prevent="submitTask" class="mb-8">
    <div class="mb-4">
      <input
        v-model="title"
        type="text"
        placeholder="Enter task title"
        class="w-full px-3 py-2 border rounded-md"
        required
      />
    </div>
    <div class="mb-4">
      <textarea
        v-model="description"
        placeholder="Enter task description (optional)"
        class="w-full px-3 py-2 border rounded-md"
      ></textarea>
    </div>
    <div class="flex justify-between">
      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
      >
        {{ taskToEdit ? 'Update Task' : 'Add Task' }}
      </button>
      <button
        v-if="taskToEdit"
        type="button"
        @click="cancelEdit"
        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600"
      >
        Cancel
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  taskToEdit: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['task-added', 'task-updated', 'edit-cancelled']);

const title = ref('');
const description = ref('');

watch(() => props.taskToEdit, (newTask) => {
  if (newTask) {
    title.value = newTask.title;
    description.value = newTask.description || '';
  } else {
    title.value = '';
    description.value = '';
  }
}, { immediate: true });

  /**
   * Submits the task to the server via API. If the task has an ID, it is
   * updated, otherwise it is created. Emits a "task-added" or "task-updated"
   * event with the response data and resets the form fields.
   *
   * @returns {void}
   */
const submitTask = async () => {
  try {
    const taskData = {
      title: title.value,
      description: description.value,
    };

    let response;
    if (props.taskToEdit) {
      response = await axios.put(`/api/tasks/${props.taskToEdit.id}`, taskData);
      emit('task-updated', response.data);
    } else {
      response = await axios.post('/api/tasks', taskData);
      emit('task-added', response.data);
    }

    title.value = '';
    description.value = '';
  } catch (error) {
    console.error('Error submitting task:', error);
    if (error.response && error.response.data.errors) {
      alert('Validation error: ' + Object.values(error.response.data.errors).join('\n'));
    } else {
      alert('Failed to submit task. Please try again.');
    }
  }
};

/**
 * Cancels the task editing process.
 * 
 * Emits an "edit-cancelled" event to notify that the editing has been cancelled.
 */
const cancelEdit = () => {
  emit('edit-cancelled');
};
</script>