<template>
    <addTodo @todoCreated="addNewTodo" />

    <li v-for="todo in todos" :key="todo.id" class="list-none hover:cursor-pointer">
        <div
            class="my-1 rounded p-1"
            :class="{
                'bg-green-400': todo.isCompleted,
                'bg-red-400': !todo.isCompleted,
            }"
            @click="toggleComplete(todo)"
        >
            <strong>{{ todo.title }}</strong>
            <p>{{ todo.description }}</p>
        </div>
    </li>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import addTodo from '../components/addTodo.vue'

const todos = ref([])

onMounted(async () => {
    try {
        const response = await axios.get('/api/todos')
        todos.value = response.data
    } catch (error) {
        console.log('error is ', error)
    }
})

function toggleComplete(todo) {
    todo.isCompleted = !todo.isCompleted
}

const addNewTodo = (newTodo) => {
    todos.value.unshift(newTodo)
}
</script>
