<template>
    <AdminLayout title="Yeni Kategori">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Yeni Kategori</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="form.post(route('admin.categories.store'))">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    Kategori Adı
                                </label>
                                <input
                                    v-model="form.name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="name"
                                    type="text"
                                    placeholder="Kategori adını giriniz"
                                >
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                    Açıklama
                                </label>
                                <textarea
                                    v-model="form.description"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="description"
                                    placeholder="Kategori açıklamasını giriniz"
                                    rows="3"
                                ></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                            </div>

                            <div class="flex items-center justify-between">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    Kaydet
                                </button>
                                <Link
                                    :href="route('admin.categories.index')"
                                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
                                >
                                    İptal
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: ''
});
</script> 