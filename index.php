<!DOCTYPE html>
<html>
<head>
    <title>Vue.js</title>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

</head>
<body>
    <div id="app" class="container">
        <h1>Todo App</h1>
        <input v-model="todo.text">
        <button v-on:click="addTodo()" class="btn btn-default">Add Todo</button>
        <ul>
            <li v-for="todo in todos" :key="todo.id"  class="card card-body" style="width: 18rem; margin-top: 1em">
                <input v-model="todo.text" class="card-header" v-show="todo.isEditing">
                <p class="card-header" v-show="!todo.isEditing">{{ todo.text }}</p>
                <p class="card-text" v-if="!todo.isEditing">{{ todo.priority }} priority</p>
                <div v-if="!todo.isEditing">
                    <button v-on:click="deleteTodo(todo)" class="btn btn-default" style="margin-top: 1em">Delete</button>
                    <button v-on:click="editTodo(todo)" class="btn btn-default" style="margin-top: 1em">Edit</button>
                    <button v-on:click="completeTodo(todo)" class="btn btn-default" style="margin-top: 1em" id="doneButton">Done</button>
                </div>
                <div class="btn-group" v-show="todo.isEditing">
                    <button v-for="status in priorities" v-on:click="changePriority(status, todo)" class="btn btn-default" style="margin-top: 1em">{{ status }}</button>
                </div>
                <div v-show="todo.isEditing">
                    <button v-on:click="updateTodo(todo)" class="btn btn-default" style="margin-top: 1em">Save</button>
                    <button v-on:click="cancel(todo)" class="btn btn-default" style="margin-top: 1em">Cancel</button>
                </div>
            </li>           
        </ul>
    </div>
    
    <script>
        
        var app = new Vue({
            el: '#app',
            data: {
                todos: [],
                message: '',
                todo: {
                    text: '',
                    priority: 'medium',
                    done: false
                },
                priorities: ['low', 'medium' , 'high']
            },
            methods: {
                addTodo: function () {
                    axios.post('/todo', this.todo).then((response) => {
                        this.todos.push(response.data);
                    });
                    this.todo = {};
                    // this.todos.push({ text: this.message, priority: 'medium', done: false });
                    // this.message = '';
                },
                deleteTodo: function (todo) {
                    axios.get('/delete/' + todo.id).then(({ data }) => {
                    this.todos.splice(this.todos.indexOf(data), 1);
                        // this.todos.splice(response.data, 1);
                    });
                },
                editTodo: function (todo) {
                    this.$set(todo, 'isEditing', true);
                },
                updateTodo: function (todo) {
                    axios.put('/edit/' + todo.id, todo)
                    .then(({data}) => {
                        let oldTodo = this.todos.indexOf(data)
                        oldTodo = data;
                    });
                    this.$set(todo, 'isEditing', false);
                    // this.todos[this.todos.indexOf(todo)] = todo;
                },
                changePriority: function(status, todo) {
                    this.todos[this.todos.indexOf(todo)].priority = status;
                },
                completeTodo(todo) {
                    todo.done = !todo.done;
                    this.updateTodo(todo);
                },
                cancel(todo) {
                    this.$set(todo, 'isEditing', false);
                },
            },
            mounted() {
                axios.get('/todo').then((response) => {
                    this.todos = response.data;
                })
            }
        })    
    </script>
    
    
</body>
</html>