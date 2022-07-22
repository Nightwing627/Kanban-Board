<template>
  <div class="status-container p-2">
    <!-- Columns -->
    <div
      v-for="status in statuses"
      :key="status.slug"
      class="status-item"
    >
      <div class="status-item-container">
        <div class="header">
          <h4 class="font-medium text-white">
            {{ status.title }}
          </h4>
          <button
            @click="showTaskModal(status.id)"
            class="btn btn-add-task"
          >
            Add Task
          </button>
          <!-- <button
            @click="openModal"
            v-if="newTaskForStatus === status.id"
            :status-id="status.id"
            v-on:task-added="handleTaskAdded"
            v-on:task-canceled="closeAddTaskForm"
            class="btn btn-add-task"
          >
            Open Modal
          </button> -->
        </div>
        <div class="add-task-area p-2">
          <!-- Tasks area -->
          <draggable
            class="draggable-item"
            v-model="status.tasks"
            v-bind="taskDragOptions"
            @end="handleTaskMoved"
          >
            <transition-group
              class="transition-group"
              tag="div"
            >
              <div
                v-for="task in status.tasks"
                :key="task.id"
                class="task-item mb-3 p-4"
              >
                <span class="mb-2">
                  {{ task.title }}
                </span>
                <p class="text-gray-700">
                  {{ task.description }}
                </p>
              </div>
              <!-- ./Tasks area -->
            </transition-group>
          </draggable>

          <!-- No Tasks -->
          <div
            v-show="!status.tasks.length && newTaskForStatus !== status.id"
            class="no-task-area p-4"
          >
            <span class="text-gray-600">No tasks yet</span>
          </div>
          <!-- ./No Tasks -->
        </div>
      </div>
    </div>
    <!-- ./Columns -->
  </div>
</template>

<script>
import draggable from "vuedraggable";
import AddTaskForm from "./AddTaskForm";
import VModal from 'vue-js-modal'
import Vue from "vue";
import axios from 'axios';

Vue.use(VModal, {
    dynamic: true,
    dynamicDefaults: { clickToClose: false },
    injectModalsContainer: true
});

export default {
  components: { draggable, AddTaskForm },
  props: {
    initialData: Array
  },
  data() {
    return {
      statuses: [],
      newTaskForStatus: 0
    };
  },
  created() {
// this.$modal.on('eventTest', ()=>{console.log('333333333333333')})
  },
  computed: {
    taskDragOptions() {
      return {
        animation: 200,
        group: "task-list",
        dragClass: "status-drag"
      };
    }
  },
  mounted() {
    this.statuses = JSON.parse(JSON.stringify(this.initialData));

  },
  methods: {
    showTaskModal(statusId) {
        this.newTaskForStatus = statusId;
        this.$modal.show (
            AddTaskForm,
            {
                title: "Add New Task",
                statusId: this.newTaskForStatus,
                updated: this.handleTaskAdded
            },
            { height: 'auto' },
            {
                'closed': this.closeAddTaskForm,
            }
        );

    },
    closeAddTaskForm() {
      this.newTaskForStatus = 0;
    },
    handleTaskAdded(newTask) {
        if (newTask) {
            const statusIndex = this.statuses.findIndex(
                status => status.id === newTask.status_id
            );

            // Add newly created task to our column
            this.statuses[statusIndex].tasks.push(newTask);

            // Reset and close the AddTaskForm
            this.closeAddTaskForm();
        }

    },
    handleTaskMoved(evt) {
      axios.put("/tasks/sync", { columns: this.statuses }).catch(err => {
        console.log(err.response);
      });
    },
  }
};
</script>

<style scoped>
.status-drag {
  transition: transform 0.5s;
  transition-property: all;
}
</style>
