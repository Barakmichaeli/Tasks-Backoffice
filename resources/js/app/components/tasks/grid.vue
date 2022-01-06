<template>
    <div class="tasks-container">
        <Form v-if="pp_form"
              @close="pp_form = false; edit_task = null; $emit('close')"
              @load="load"
              :subjects="subjects"
              :statuses="statuses"
              :edit_task="edit_task">
        </Form>
        <div class="table-header">
            <div class="filters">
                <select id="subject-filter" class="form-select form-select-sm" v-model="filter_subject_id">
                    <option value="">--Subject</option>
                    <option v-for="(subject, idx) in subjects" :value="subject.id" :key="idx">{{
                            subject.title
                        }}
                    </option>
                </select>
                <select id="status-filter" class="form-select form-select-sm" v-model="filter_status_id">
                    <option value="">--Status</option>
                    <option v-for="(status, idx) in statuses" :value="status.id" :key="idx">{{
                            status.title
                        }}
                    </option>
                </select>
            </div>
            <div class="actions">
                <button type="button" class="btn btn-secondary btn-sm" @click="pp_form = true">
                    <i class="bi bi-journal-plus"></i>
                    <span>Add</span>
                </button>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th class="sortable-field"
                    @click="sorting_field = 'id'; sorting_dir = sorting_dir === 'DESC' ? 'ASC' : 'DESC'">
                    ID
                    <span>
                        <i v-if="sorting_field === 'id' && sorting_dir === 'ASC'" class="bi bi-sort-up"></i>
                        <i v-else-if="sorting_field === 'id' && sorting_dir === 'DESC'" class="bi bi-sort-down"></i>
                        <i v-else class="bi bi-filter-circle"></i>
                    </span>
                </th>
                <th>Subject</th>
                <th>Description</th>
                <th>Status</th>
                <th class="sortable-field"
                    @click="sorting_field = 'ddate'; sorting_dir = sorting_dir === 'DESC' ? 'ASC' : 'DESC'">
                    Due Date
                    <i v-if="sorting_field === 'ddate' && sorting_dir === 'ASC'" class="bi bi-sort-up"></i>
                    <i v-else-if="sorting_field === 'ddate' && sorting_dir === 'DESC'" class="bi bi-sort-down"></i>
                    <i v-else class="bi bi-filter-circle"></i>
                </th>
                <th>Created</th>
                <th class="sortable-field"
                    @click="sorting_field = 'last_modified'; sorting_dir = sorting_dir === 'DESC' ? 'ASC' : 'DESC'">
                    Modified
                    <span>
                        <i v-if="sorting_field === 'last_modified' && sorting_dir === 'ASC'" class="bi bi-sort-up"></i>
                        <i v-else-if="sorting_field === 'last_modified' && sorting_dir === 'DESC'"
                           class="bi bi-sort-down"></i>
                        <i v-else class="bi bi-filter-circle"></i>
                    </span>
                </th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task, idx) in tasks" :key="idx">
                <td>{{ task.id }}</td>
                <td>{{ subjects.find(subject => subject.id === task.subject.id).title }}</td>
                <td>{{ task.description }}</td>
                <td>{{ statuses.find(status => status.id === task.status.id).title }}</td>
                <td>{{ task.ddate }}</td>
                <td>{{ task.created }}</td>
                <td>{{ task.last_modified }}</td>
                <td class="options">
                    <a href="javascript:;" @click="pp_form = true; edit_task = task">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="javascript:;" @click="remove(task.id)">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
        <span style="padding: 10px;">
            <input type="text" style="text-align: center" v-model="current_limit" maxlength="2" size="2"> / {{ total }} Items
        </span>
    </div>
</template>

<script>

import Form from "./form";

export default {
    data() {
        return {
            subjects: [ //Should be manageable in BED...
                {
                    id: 1,
                    title: 'Contact US'
                },
                {
                    id: 2,
                    title: 'Unknown error'
                },
                {
                    id: 3,
                    title: 'More subjects...'
                },
            ],
            statuses: [ //Should be manageable in BED...
                {
                    id: 1,
                    title: 'Open'
                },
                {
                    id: 2,
                    title: 'In progress'
                },
                {
                    id: 3,
                    title: 'Completed'
                },
                {
                    id: 3,
                    title: 'Cancelled'
                },
            ],
            tasks: [],
            filter_subject_id: '',
            filter_status_id: '',
            sorting_field: 'id',
            sorting_dir: 'DESC',
            current_page: 1,
            current_limit: 10,
            total: 0,
            pp_form: false,
            edit_task: null
        }
    },
    components: {
        Form
    },
    mounted() {
        this.load();
    },
    watch: {
        current_limit(new_val, old_val) {
            if (new_val >= 0 && new_val <= 50 && new_val !== old_val) {
                this.load()
            } else {
                this.current_limit = old_val;
            }
        },
        filter_subject_id() {
            this.load()
        },
        filter_status_id() {
            this.load()
        },
        sorting_field() {
            this.load()
        },
        sorting_dir() {
            this.load()
        }
    },
    methods: {
        load() {
            const params = new URLSearchParams({
                filters: JSON.stringify({
                    subject_id: this.filter_subject_id,
                    status_id: this.filter_status_id,
                }),
                sorting: JSON.stringify({
                    field: this.sorting_field,
                    dir: this.sorting_dir
                }),
                current_page: this.current_page,
                current_limit: this.current_limit
            });
            axios({
                method: 'get',
                url: '/api/tasks?' + params.toString(),
            }).then(function (response) {
                this.tasks = response.data.items
                this.total = response.data.total
            }.bind(this));
        },
        remove(id) {
            axios({
                method: 'delete',
                url: '/api/tasks/' + id,
            }).then(function (response) {
                this.load();
            }.bind(this))
                .catch(function (error) {
                    if (error.response) {
                        this.$alert(error.response.data.description, "Delete failed", null, {
                            type: 'error'
                        });
                    } else {
                        this.$alert("Unknown error occurred, please try again or contact support");
                    }
                }.bind(this));
        }
    }
}
</script>

<style>

.tasks-container .table-header {
    display: flex;
    justify-content: space-between;
    padding: 10px;
}

.tasks-container .table-header .filters {
    height: 20%;
    display: flex;
}

.tasks-container .filters > *:not(:first-child) {
    margin-left: 5px;
}

.sortable-field {
    cursor: pointer;
}

.actions i {
    vertical-align: middle;
}

.tasks-container table {
    text-align: center;
    min-width: 768px;
}
</style>
