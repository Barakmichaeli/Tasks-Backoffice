<template>
    <div>
        <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <form @submit.prevent="submit">
                            <h2>{{edit_task ? "Edit Task" : "Add Task"}}</h2>
                            <div class="form-group">
                                <label for="subject-id">Subject
                                    <span style="color: red; vertical-align: top">*</span></label>
                                <select class="form-control" id="subject-id" required v-model="subject_id">
                                    <option value="">--Select</option>
                                    <option v-for="(subject, idx) in subjects" :value="subject.id" :key="idx">
                                        {{ subject.title }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control"
                                          id="description"
                                          rows="4"
                                          v-model="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="status-id">Status
                                    <span style="color: red; vertical-align: top">*</span></label>
                                <select class="form-control" id="status-id" required v-model="status_id">
                                    <option value="">--Select</option>
                                    <option v-for="(status, idx) in statuses" :value="status.id" :key="idx">
                                        {{ status.title }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="due-date">Due date</label>
                                <input type="date" id="due-date" v-model="ddate" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-bg-white" @click="$emit('close')">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        'edit_task': {
            type: Object
        },
        'subjects': {
            type: Array
        },
        'statuses': {
            type: Array
        }
    },
    data() {
        return {
            subject_id: this.edit_task ? this.edit_task.subject.id : '',
            status_id: this.edit_task ? this.edit_task.status.id : '',
            description: this.edit_task ? this.edit_task.description : null,
            ddate: this.edit_task ? this.edit_task.ddate : null,
        }
    },
    methods: {
        submit() {
            axios({
                method: this.edit_task ? 'patch' : 'post',
                url: '/api/tasks/' + (this.edit_task ? this.edit_task.id : ""),
                data: {
                    id: this.edit_task ? this.edit_task.id : null,
                    subject_id: this.subject_id,
                    status_id: this.status_id,
                    description: this.description,
                    ddate: this.ddate
                }
            }).then(function (response) {
                this.$alert(this.edit_task ? "Task Updated Successfully" : "Task Created Successfully", null, null, {
                    type: 'success'
                });
                this.$emit('close');
                this.$emit('load');
            }.bind(this));
        }
    }
}
</script>

<style>

.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 40%;
    margin: 0px auto;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-container form {
    padding: 1rem;
}


.modal-container h2 {
    text-align: center;
}

.modal-container form label {
    margin-top: 5px;
    margin-bottom: 1px;
}

.modal-container form .form-group:last-child {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
}
</style>
