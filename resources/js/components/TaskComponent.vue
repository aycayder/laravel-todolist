<template>
    <div class="taskComponent">
        <div class="row mt-5">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">Table</h6>
                                <h2 class="mb-0">My Tasks</h2>
                            </div>
                            <div class="col text-right">
                                <button @click="loadCreateModal" class="btn btn-primary"><span
                                        class="btn-inner--icon"><i class="ni ni-time-alarm"></i></span> Add Task
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="!loading">
                        <img class="rounded mx-auto d-block" :src="image" alt="loader">
                    </div>

                    <div v-else>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Completed?</th>
                                </tr>
                                </thead>
                                <tbody v-if="tasks">
                                <tr v-for="(task, index) in tasks">
                                    <th scope="row">{{index + 1}}</th>
                                    <td style="white-space: normal; width: 20%;">{{task.name}}</td>
                                    <td style="white-space: normal; width: 25%;">{{task.body}}</td>
                                    <td>
                                        <button @click="loadUpdateModal(index)"
                                                class="btn btn-sm btn-info d-none d-lg-inline"><span
                                                class="btn-inner--icon"><i class="ni ni-settings"></i></span> Edit
                                        </button>
                                        <button @click="deleteTask(index)"
                                                class="btn btn-sm btn-danger d-none d-lg-inline"><span
                                                class="btn-inner--icon"><i class="ni ni-fat-remove"></i></span> Delete
                                        </button>

                                        <button @click="loadUpdateModal(index)"
                                                class="btn btn-sm btn-info d-lg-none d-block"
                                                style="width: 100%; margin-bottom: 5px;"><span
                                                class="btn-inner--icon"><i class="ni ni-settings"></i></span> Edit
                                        </button>
                                        <button @click="deleteTask(index)"
                                                class="btn btn-sm btn-danger d-lg-none d-block"><span
                                                class="btn-inner--icon"><i class="ni ni-fat-remove"></i></span> Delete
                                        </button>
                                    </td>
                                    <td>
                                        <div class="input-group custom-control custom-control-alternative custom-checkbox mb-3">
                                            <input class="custom-control-input" type="checkbox" :id="task.id"
                                                   :value="task.isCompleted" :checked="task.isCompleted"
                                                   disabled="disabled">
                                            <label v-if="!task.isCompleted" class="badge badge-pill badge-warning custom-control-label"
                                                   :for="task.id">Waiting</label>
                                            <label v-else-if="task.isCompleted" class="badge badge-pill badge-success custom-control-label"
                                                   :for="task.id">Completed</label>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Create Modal -->
                        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add a New Task</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="alert alert-danger" v-if="errors.length > 0">
                                            <ul>
                                                <li v-for="error in errors">{{error}}</li>
                                            </ul>
                                        </div>

                                        <div class="form-group">
                                            <label for="taskName">Name</label>
                                            <input v-model="task.name" type="text" id="taskName" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="taskDescription">Description</label>
                                            <textarea class="form-control" rows="3" v-model="task.body"
                                                      id="taskDescription"></textarea>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                        <button @click="createTask" type="button" class="btn btn-primary">Add task
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Update Modal -->
                        <div class="modal fade" id="update-modal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Task</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="alert alert-danger" v-if="errors.length > 0">
                                            <ul>
                                                <li v-for="error in errors">{{error}}</li>
                                            </ul>
                                        </div>

                                        <div class="form-group">
                                            <label for="updatedTaskName">Name</label>
                                            <input v-model="updatedTask.name" type="text" id="updatedTaskName"
                                                   class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="updatedTaskDescription">Description</label>
                                            <textarea class="form-control" rows="3" v-model="updatedTask.body"
                                                      id="updatedTaskDescription"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group custom-control custom-control-alternative custom-checkbox mb-3">
                                                <input class="custom-control-input" type="checkbox" id="isCompleted"
                                                       :value="updatedTask.isCompleted"
                                                       :checked="updatedTask.isCompleted"
                                                       v-model="updatedTask.isCompleted">
                                                <label v-if="!updatedTask.isCompleted" class="badge badge-pill badge-warning custom-control-label"
                                                       for="isCompleted">Waiting</label>
                                                <label v-else-if="updatedTask.isCompleted" class="badge badge-pill badge-success custom-control-label"
                                                       for="isCompleted">Completed</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                        <button @click="updateTask" type="button" class="btn btn-primary">Update task
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                task: {
                    name: '',
                    body: '',
                    isCompleted: 0
                },
                tasks: [],
                uri: 'http://127.0.0.1:8000/tasks/',
                errors: [],
                updatedTask: [],
                image: '/images/preloader.svg',
                loading: false,
                //toastr: toastr.options = {"positionClass": "toast-top-full-width"}
            }
        },

        methods: {

            loadCreateModal() {
                $("#create-modal").modal("show");
            },

            loadUpdateModal(index) {
                this.errors = [];
                $("#update-modal").modal("show");
                this.updatedTask = this.tasks[index];
            },

            createTask() {

                axios.post(this.uri, {name: this.task.name, body: this.task.body, isCompleted: this.task.isCompleted})
                    .then(response => {
                        this.tasks.push(response.data.task);
                        this.resetData(); // After clicking on the save button, it resets previously created data in modal popup while creating a task
                        $("#create-modal").modal("hide");
                        //toastr.success(response.data.message);
                    }).catch(error => {
                    this.errors = []; // By this way, the same errors don't repeat themselves

                    if (error.response.data.errors.name) {
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                    if (error.response.data.errors.body) {
                        this.errors.push(error.response.data.errors.body[0]);
                    }
                });
            },


            updateTask() {
                axios.patch(this.uri + this.updatedTask.id, {
                    name: this.updatedTask.name,
                    body: this.updatedTask.body,
                    isCompleted: this.updatedTask.isCompleted
                }).then(response => {
                    $("#update-modal").modal("hide");
                    //toastr.success(response.data.message);
                }).catch(error => {
                    if (error.response.data.errors.name) {
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                    if (error.response.data.errors.body) {
                        this.errors.push(error.response.data.errors.body[0]);
                    }
                });
            },

            deleteTask(index) {
                let confirmBox = confirm("Do you really want to delete this?");

                if (confirmBox == true) {
                    axios.delete(this.uri + this.tasks[index].id).then(response => {
                        this.$delete(this.tasks, index);
                        //toastr.success(response.data.message);
                    }).catch(error => {
                        console.log("Could not delete for some reason")
                    });
                }
            },

            resetData() { // After clicking on the save button, it resets previously created data in modal popup while creating a task
                this.task.name = '';
                this.task.body = '';
            },

            loadTasks() {
                axios.get(this.uri).then(response => {
                    this.tasks = response.data.tasks;
                    this.loading = true; // Shows loading image
                });
            }
        },

        mounted() {
            this.loadTasks();
        }
    }
</script>
