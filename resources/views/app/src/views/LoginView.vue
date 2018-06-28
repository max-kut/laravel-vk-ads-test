<template>
    <v-jumbotron color="grey lighten-2">
        <v-container fill-height>
            <v-layout align-center>
                <v-flex>
                    <h3 class="display-3">Добро пожаловать</h3>
                    <span class="subheading">Для использования приложения необходимо авторизоваться во Вконтакте</span>
                    <v-divider class="my-3"></v-divider>
                    <a href="/vk-auth">
                        <v-btn large color="primary" class="mx-0">
                            авторизоваться
                        </v-btn>
                    </a>
                </v-flex>
            </v-layout>
        </v-container>
    </v-jumbotron>
</template>

<script>
    export default {
        name: 'LoginView',
    }
</script>