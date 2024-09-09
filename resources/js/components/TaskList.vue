<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Task List</h1>
        <ul class="list-group mb-4">
            <li v-for="task in tasks" :key="task.id" class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">{{ task.title }}</h5>
                    <p class="mb-1">{{ task.description }}</p>
                    <small class="text-muted">Assigned to: {{ task.user['email'] }}</small>
                </div>
                <div>
                    <button v-if="task.completed==0" class="btn btn-success btn-sm mr-2" @click="updateTask(task.id)">Complete</button>
                    <button class="btn btn-danger btn-sm" @click="deleteTask(task.id)">Delete</button>
                </div>
            </li>
        </ul>
        <form @submit.prevent="addTask" class="card card-body">
            <div class="form-group">
                <input v-model="newTask.title" class="form-control" placeholder="Task Title" required>
            </div>
            <div class="form-group">
                <input v-model="newTask.description" class="form-control" placeholder="Task Description" required>
            </div>
            <div class="form-group">
                <!-- <input v-model="newTask.user" class="form-control" placeholder="Assigned User (email)" required> -->
                <select v-model="newTask.user" class="form-control"  required>
                    <option disabled value="">Assigned User (email)</option> 
                    <option v-for="user in users" :key="user.id" :value="user.email">
                        {{ user.email }}
                    </option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Add Task</button>
        </form>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
    data() {
        return {
            newTask: {
                title: '',
                description: '',
                user: ''
            }
        };
    },
    computed: {
        ...mapState(['tasks','users'])
    },
    methods: {
        ...mapActions(['fetchTasks', 'fetchUsers', 'addTask', 'updateTask', 'deleteTask']),
        addTask() {
            if (!this.newTask.title || !this.newTask.description || !this.newTask.user) {
                alert('Both title and description are required');
                return;
            }

            this.$store.dispatch('addTask', this.newTask).then(() => {
                this.newTask.title = '';
                this.newTask.description = '';
                this.newTask.user = '';
                return this.fetchTasks();
            }).catch(error => {
                console.error('Error adding task:', error);
            });
        },
        updateTask(taskId) {
            this.$store.dispatch('updateTask', taskId)
            .then(()=>{
                return this.fetchTasks();
            })
            .catch(error => {
                console.error('Error completing task:', error);
            });
        },
        deleteTask(taskId) {
            this.$store.dispatch('deleteTask', taskId)
            .then(()=>{
                return this.fetchTasks();
            })
            .catch(error => {
                console.error('Error deleting task:', error);
            });
        }
    },
    created() {
        this.fetchTasks();
        this.fetchUsers();
    }
};
</script>