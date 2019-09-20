<template>
    <div class="avatar avatar-outline">
        <div class="avatar-holder" >
            <img :src="url" alt="" v-show="canBeCancelled">
        </div>
        <label class="avatar-upload">
            <i class="fas fa-pen"></i>
            <input ref="fileInput" type="file" name="avatar" @change="readUrl">
        </label>
        <span class="avatar-cancel" v-show="this.canBeCancelled" @click="cancelUpload">
            <i class="fa fa-times"></i>
        </span>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                url: '',
            }
        },
        methods: {
            readUrl() {
                if (this.$refs.fileInput.files && this.$refs.fileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                            this.url = e.target.result
                    };
                    reader.readAsDataURL(this.$refs.fileInput.files[0]);
                    return;
                }
            },
            cancelUpload() {
                this.$refs.fileInput.value = "";
                this.url = "";
            }
        },
        computed: {
            canBeCancelled () {
                return this.url !== '';
            }
        }
    }
</script>

<style>
    .avatar.avatar-outline {
        position: relative;
    }

    .avatar-holder{
        width: 120px;
        height: 120px;
        padding: 5px;
        border-radius: 4px;
        box-shadow: 0px 0px 13px 0px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload>input[type="file"] {
        visibility: hidden;
    }

    .avatar-upload {
        cursor: pointer;
        position: absolute;
        display: block;
        top: -15px;
        left: 105px;
        height: 30px;
        width: 30px;
        background-color: #5867dd;
        border-radius: 50%;
        box-shadow: 0px 0px 13px 0px rgba(0, 0, 0, 0.1);
    }

    .avatar-cancel {
        position: absolute;
        display: block;
        bottom: -5px;
        left: 110px;
        height: 20px;
        width: 20px;
        background-color: #fff;
        border-radius: 50%;
        box-shadow: 0px 0px 13px 0px rgba(0, 0, 0, 0.1);
        transform: scale(1.0);
        transition: 0.3s all;

    }

    .avatar-cancel i {
        position: absolute;
        top: 4px;
        left: 4px;
        color: #74788d;
    }

    .avatar-holder>img {
        width: 100%;
        height: 100%;
    }

    .avatar-cancel:hover {
        transform: scale(1.2);
    }

</style>
