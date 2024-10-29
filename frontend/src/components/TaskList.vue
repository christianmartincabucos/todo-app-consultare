<template>
  <div>
    <div class="mb-4">
      <select v-model="filter" class="px-3 py-2 border rounded-md">
        <option value="all">All</option>
        <option value="completed">Completed</option>
        <option value="pending">Pending</option>
      </select>
    </div>
    <ul>
      <li
        v-for="task in filteredTasks"
        :key="task.id"
        class="mb-4 p-4 border rounded-md"
      >
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-xl font-semibold" :class="{ 'line-through': task.completed }">
              {{ task.title }}
            </h3>
            <p class="text-gray-600">{{ task.description }}</p>
          </div>
          <div class="text-white lg:grid space-y-1">
            <button
              @click="toggleTaskStatus(task)"
              class="m-0.5 p-1 rounded-md"
              :class="{ 'bg-green-500': task.completed, 'bg-yellow-500': !task.completed }"
            >
              <CircleCheckBig v-if="task.completed"/>
              <Loader v-else/>
            </button>
            <button
              @click="editTask(task)"
              class="m-0.5 p-1 bg-blue-500 rounded-md"
            >
              <Pencil/>
            </button>
            <button
              @click="deleteTask(task.id)"
              class="m-0.5 p-1 bg-red-500 rounded-md"
            >
              <Trash/>
            </button>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Pencil, Trash, CircleCheckBig, Loader } from 'lucide-vue-next'
import axios from 'axios';

const props = defineProps(['tasks']);
const emit = defineEmits(['task-updated', 'task-deleted', 'edit-task']);

const filter = ref('all');

const filteredTasks = computed(() => {
  if (filter.value === 'all') return props.tasks;
  return props.tasks.filter(task => 
    filter.value === 'completed' ? task.completed : !task.completed
  );
});

  /**
   * Toggles the completed status of a task.
   * 
   * Emits a "task-updated" event with the updated task data.
   * 
   * @param {Object} task The task to update.
   */
const toggleTaskStatus = async (task) => {
  try {
    const response = await axios.put(`/api/tasks/${task.id}`, {
      ...task,
      completed: !task.completed,
    });
    emit('task-updated', response.data);
  } catch (error) {
    console.error('Error updating task:', error);
    alert('Failed to update task status. Please try again.');
  }
};

/**
 * Emits an "edit-task" event with the specified task data.
 * 
 * @param {Object} task The task to be edited.
 */
const editTask = (task) => {
  emit('edit-task', task);
};

  /**
   * Deletes a task.
   * 
   * Emits a "task-deleted" event with the id of the deleted task.
   * 
   * @param {number} taskId The id of the task to delete.
   */
const deleteTask = async (taskId) => {
  if (confirm('Are you sure you want to delete this task?')) {
    try {
      await axios.delete(`/api/tasks/${taskId}`);
      emit('task-deleted', taskId);
      emit('edit-cancelled');
    } catch (error) {
      console.error('Error deleting task:', error);
      alert('Failed to delete task. Please try again.');
    }
  }
};
</script>