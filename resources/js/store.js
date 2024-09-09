import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios'; // Asegúrate de tener axios instalado

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        tasks: [] // Estado inicial para las tareas
    },
    mutations: {
        SET_TASKS(state, tasks){
            
            state.tasks = tasks;
        },
        ADD_TASK(state, task) {
        },
        UPDATE_TASK(state, updatedTask) {
            const index = state.tasks.findIndex(t => t.id === updatedTask.id);
            if (index !== -1) {
                Vue.set(state.tasks, index, updatedTask);
            }
        },
        DELETE_TASK(state, taskId) {
            state.tasks = state.tasks.filter(t => t.id !== taskId);
        }
    },
    actions: {
        fetchTasks({ commit }) {
            return axios.get('/task')
                .then(response => {
                    commit('SET_TASKS', response.data);
                })
                .catch(error => {
                    console.error("Error fetching tasks:", error);
                });
        },
        addTask({ commit }, task) {
            axios.post('/tasks', task)
                .then(response => {
                    commit('ADD_TASK', response.data);
                })
                .catch(error => {
                    alert("El email asignado no exite. ");
                    console.error("Error adding task:", error);
                });
        },
        updateTask({ commit }, taskId) {
            axios.put(`/tasks/${taskId}`)
                .then(response => {
                    commit('UPDATE_TASK', response.data);
                })
                .catch(error => {
                    console.error("Error updating task:", error);
                });
        },
        deleteTask({ commit }, taskId) {
            axios.delete(`/tasks/${taskId}`)
                .then(() => {
                    commit('DELETE_TASK', taskId);
                })
                .catch(error => {
                    console.error("Error deleting task:", error);
                });
        }
    },
    getters: {
        tasks: state => state.tasks
    }
});
