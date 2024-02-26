<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        {{ message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    updated() {

    },
    data() {
        return {
            message: 'hello'
        }
    },
    mounted() {

        Echo.private(`task.2`)
            .listen('task', (e) => {
                console.log(e.data);
                this.message = 'ahmad'
                alert(e);
            });
        console.log('Component mounted.')
    }
}
</script>
