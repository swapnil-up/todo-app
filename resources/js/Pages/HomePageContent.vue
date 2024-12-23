<template>
    <addTodo @todoCreated="addNewTodo" />

    <li v-for="todo in todos" :key="todo.id" class="list-none hover:cursor-pointer">
        <div v-if="editTodo && editTodo.id === todo.id">
            <input v-model="editTodo.title" placeholder="title" />
            <input v-model="editTodo.description" placeholder="description" />
            <label>
                <input type="checkbox" v-model="editTodo.isCompleted" />
            </label>
            <button @click="updateTodo">Save</button>
            <button @click="cancelEditing">Cancel</button>
        </div>

        <div
            v-else
            class="my-1 rounded p-1"
            :class="{
                'bg-green-400': todo.isCompleted,
                'bg-red-400': !todo.isCompleted,
            }"
            @click="toggleComplete(todo)"
        >
            <strong>{{ todo.title }}</strong>
            <p>{{ todo.description }}</p>
            <button @click.stop="startEditing(todo)">Edit</button>
            <button @click.stop="deleteTodo(todo.id)">Delete</button>
        </div>
    </li>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import addTodo from '../components/addTodo.vue'
import Pusher from 'pusher-js'
import Echo from 'laravel-echo'

const todos = ref([])
const editTodo = ref(null)

const fetchTodos = async () => {
    try {
        const response = await axios.get('/api/todos')
        todos.value = response.data
    } catch (error) {
        console.log('error is ', error)
    }
}

Pusher.logToConsole = true;

    // const pusher = new Pusher('29efa0557c4d14bd2ecb', {
    //     wsHost: '127.0.0.1',
    //     wsPort: 8080,
    //     forceTLS: false,
    //     enabledTransports: ['ws', 'wss'],
    //     cluster: 'ap2',
    // })

onMounted(() => {
    fetchTodos()

    window.Echo.channel('todos')
        .listen('.todo.completed', (data) => {
            console.log('Real-Time Update:', data.todo);
            alert(`Todo "${data.todo.title}" was completed!`);
        });

    // const channel = pusher.subscribe('todos')
    // channel.bind('todo.completed', (data) => {
    //     console.log("triggered")
    //     alert(`Todo "${data.todo.title}" was completed!`)
    //     console.log('Real-Time Update:', data.todo)
    // })
})

function toggleComplete(todo) {
    todo.isCompleted = !todo.isCompleted
    axios
        .put(`/api/todos/${todo.id}`, {
            isCompleted: todo.isCompleted,
        })
        .then(() => {
            fetchTodos()
        })
        .catch((error) => {
            console.error('Failed to toggle completion:', error)
            todo.isCompleted = !todo.isCompleted
        })
}

const addNewTodo = (newTodo) => {
    todos.value.unshift(newTodo)
}

const startEditing = (todo) => {
    editTodo.value = { ...todo }
}

const cancelEditing = () => {
    editTodo.value = null
}

const updateTodo = async () => {
    try {
        await axios.put(`/api/todos/${editTodo.value.id}`, {
            title: editTodo.value.title,
            description: editTodo.value.description,
            isCompleted: editTodo.value.isCompleted,
        })
        fetchTodos()
        editTodo.value = null
    } catch (error) {
        console.log('update failed: ', error)
    }
}

const deleteTodo = async (id) => {
    try {
        await axios.delete(`api/todos/${id}`)
        todos.value = todos.value.filter((todo) => todo.id !== id)
    } catch (error) {
        console.log("Couldnt' delete", error)
    }
}
</script>
