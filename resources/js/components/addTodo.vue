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
        console.log(response.data)
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
    <form @submit.prevent="submitTodo" class="flex flex-col gap-1">
        <h2>Add new todo</h2>
        <div>
            <label>Title</label>
            <input v-model="title" required class="border border-gray-400"/>
        </div>
        <div >
            <label>Description</label>
            <input v-model="description" required class="border border-gray-400"/>
        </div>
        <div class="flex gap-2">
            <input v-model="isCompleted" type="checkbox" />
            <label>Completed?</label>
        </div>
        <button type="submit">Create Todo</button>
    </form>
</template>
