<template>
<div class="add-task-modal-container">
    <form
        class="add-task-form"
        @submit.prevent="handleAddNewTask">

        <div class="modal-header">
            <h2>{{ title }}</h2>
        </div>

        <div class="modal-body">
            <div class="form-group mb-3">
                <label for="email" class="form-label">Title</label>

                <div class="form-input-group">
                    <input
                        name="title"
                        class="form-control"
                        type="text"
                        v-model.trim="newTask.title"
                    />
                    <div class="error-msg" v-show="errMsgTitle">
                        <span class="text-xs text-red-500">{{ errMsgTitle }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label">Description</label>

                <div class="form-input-group">
                    <textarea
                        name="description"
                        class="form-control"
                        rows="2"
                        v-model.trim="newTask.description"
                    ></textarea>
                    <div class="error-msg" v-show="errMsgDesc">
                        <span class="text-xs text-red-500">{{ errMsgDesc }}</span>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-save">
                Save
            </button>
            <button @click="closeModal" type="reset" class="btn btn-primary">
                Discard
            </button>
        </div>

    </form>
</div>

</template>

<script>
import axios from 'axios';

export default {
  props: {
    title: String,
    statusId: Number,
    updated: Function
  },
  data() {
    return {
      newTask: {
        title: "",
        description: "",
        status_id: null
      },
      errMsgTitle: "",
      errMsgDesc: ""
    };
  },
  mounted() {
    this.newTask.status_id = this.statusId;
  },
  methods: {
    handleAddNewTask() {

      if (!this.newTask.title) {
        this.errMsgTitle = "The title field is required";
        return;
      }

      if (!this.newTask.description) {
        this.errMsgDesc = "The description field is required";
        return;
      }
      // Send new task to server
      axios
        .post("/tasks", this.newTask)
        .then(res => {
            this.updated(res.data);
            // this.$emit("task-added", );
            this.$emit('close');
        })
        .catch(err => {
          this.handleErrors(err);
        });
    },
    handleErrors(err) {
      if (err.response && err.response.status === 422) {
        const errorBag = err.response.data.errors;
        if (errorBag.title) {
          this.errorMessage = errorBag.title[0];
        } else if (errorBag.description) {
          this.errorMessage = errorBag.description[0];
        } else {
          this.errorMessage = err.response.message;
        }
      } else {
        console.log(err.response);
      }
    },
    closeModal() {
        console.log('modal close event emit');
        // this.$emit('event-test');

        this.$emit('close');
    }
  }
};
</script>
