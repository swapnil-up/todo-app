<template>
    <li v-for="todo in todos" :key="todo.id" class="list-none">
        <div
            class="my-1 rounded p-1"
            :class="{
                'bg-green-400': todo.isCompleted,
                'bg-red-400': !todo.isCompleted,
            }"
        >
            <strong>{{ todo.title }}</strong>
            <p>{{ todo.description }}</p>
        </div>
    </li>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const todos = ref([])

onMounted(async () => {
    try {
        const response = await axios.get('/api/todos')
        todos.value = response.data
    } catch (error) {
        console.log('error is ', error)
    }
})
</script>
