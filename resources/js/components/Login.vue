<template>
    <form @submit.prevent="login">
        <input type="email" v-model="email" placeholder="Email">
        <input type="password" v-model="password" placeholder="Password">
        <button type="submit">Login</button>
        <div v-if="errors.length">
            <ul>
                <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
        </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            email: '',
            password: '',
            errors: []
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/api/auth/login', {
                    email: this.email,
                    password: this.password
                });

                // Assuming your Laravel backend returns a token upon successful login
                const token = response.data.token;

                // Store token in localStorage or Vuex store for future requests

                // Redirect to the tasks page
                this.$router.push({ name: 'tasks' });
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = Object.values(error.response.data.errors).flat();
                } else {
                    this.errors = ['An unexpected error occurred. Please try again.'];
                }
            }
        }
    }
}
</script>
