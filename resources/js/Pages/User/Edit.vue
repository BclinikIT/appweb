<template>
    <AppLayout title="Editar Usuario">
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">
                <h2 class="text-2xl leading-tight">Editar Usuario</h2>
                <form @submit.prevent="updateUser">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre:</label>
                        <input type="text" id="name" v-model="form.name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Correo:</label>
                        <input type="email" id="email" v-model="form.email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Guardar
                        </button>
                        <button @click="cancelEdit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps(['user']);
const form = useForm({
    name: props.user.name,
    email: props.user.email
});

const updateUser = () => {
    form.put(route('users.update', props.user.id));
};

const cancelEdit = () => {
    window.history.back();
};
</script>
