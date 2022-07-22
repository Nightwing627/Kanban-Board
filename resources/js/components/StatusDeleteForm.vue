<template>
<div class="del-status-modal-container">
    <form
        class="del-status-form"
        @submit.prevent="handleDeleteStatus">
        <div class="modal-header">
            <h2>Delete Status</h2>
        </div>
        <div class="modal-body">
            <p>If you delete this Status, all Tasks belonging to it will be deleted. </p>
            <p>Do you really agree?</p>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-save">
                Agree
            </button>
            <button @click="$emit('close')" type="reset" class="btn btn-primary">
                Cancle
            </button>
        </div>
    </form>
</div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        sId: Number,
        updated: Function
    },
    data() {
        return {
            status_id: null,
            errMsg: ""
        }
    },
    mounted() {
        this.status_id = this.sId;
    },
    methods: {
        handleDeleteStatus() {
            axios
              .delete('/statuses/' + this.status_id)
              .then(res => {
                console.log(res);
              })
              .catch(err => {
                this.errMsg = err.response.message;
              })
        }
    }
}
</script>
