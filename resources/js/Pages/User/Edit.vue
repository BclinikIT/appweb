<template>
    <AppLayout :title="isEdit ? 'Editar Usuario' : 'Crear Usuario'">
        <div class="container mx-auto px-4 sm:px-8 w-1/4 bg-white m-8">
            <div class="py-8">
                <h2 class="text-2xl leading-tight">{{ isEdit ? 'Editar Usuario' : 'Crear Usuario' }}</h2>
                <br>
                <form @submit.prevent="handleSubmit">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre de usuario:</label>
                        <input type="text" id="name" v-model="form.name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Correo:</label>
                        <input type="email" id="email" v-model="form.email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Contraseña:</label>
                        <input type="password" id="password" v-model="form.password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <a :href="route('users.index')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancelar
                        </a>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            {{ isEdit ? 'Guardar' : 'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    user: Object,
    isEdit: {
        type: Boolean,
        default: false
    }
});

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    password: ''
});

const errors = ref({});

const validate = () => {
    errors.value = {};

    if (!form.name) {
        errors.value.name = 'El nombre es obligatorio.';
    }
    if (!form.email) {
        errors.value.email = 'El correo es obligatorio.';
    } else if (!/\S+@\S+\.\S+/.test(form.email)) {
        errors.value.email = 'El correo no es válido.';
    }
    if (!props.isEdit && !form.password) {
        errors.value.password = 'La contraseña es obligatoria.';
    } else if (!props.isEdit && form.password.length < 8) {
        errors.value.password = 'La contraseña debe tener al menos 8 caracteres.';
    }

    return Object.keys(errors.value).length === 0;
};

const handleSubmit = () => {
    if (validate()) {
        if (props.isEdit) {
            form.put(route('users.update', props.user.id));
        } else {
            form.post(route('users.store'));
        }
    }
};


</script>
