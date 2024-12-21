<script setup>
import { ref } from 'vue'
import axios from 'axios'

const title = ref('')
const description = ref('')
const isCompleted = ref(false)

const emit = defineEmits(['todo-created'])

const submitTodo = async () => {
    try {
        const response = await axios.post('api/todos', {
            title: title.value,
            description: description.value,
            isCompleted: isCompleted.value,
        })
        emit('todo-created', response.data)

        title.value = ''
        description.value = ''
        isCompleted.value = false
    } catch (error) {
        console.error('failed to create a todo ', error)
    }
}
</script>

<template>
    <form @submit.prevent="submitTodo">
        <h2>Add new todo</h2>
        <label>Title</label>
        <input v-model="title" required />
        <label>Description</label>
        <input v-model="description" />
        <div class="flex gap-2">
            <input v-model="isCompleted" type="checkbox" />
            <label>Completed?</label>
        </div>
        <button type="submit">Create Todo</button>
    </form>
</template>
