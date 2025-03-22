<template>
    <AdminLayout title="Kategoriler">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kategoriler</h2>
                <Link :href="route('admin.categories.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Yeni Kategori Ekle
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kategori Adı
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Açıklama
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ürün Sayısı
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        İşlemler
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="category in categories" :key="category.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ category.name }}</td>
                                    <td class="px-6 py-4">{{ category.description || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ category.products_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link :href="route('admin.categories.edit', category.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Düzenle
                                        </Link>
                                        <button @click="deleteCategory(category)" class="text-red-600 hover:text-red-900">
                                            Sil
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array
});

const deleteCategory = (category) => {
    if (confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')) {
        useForm().delete(route('admin.categories.destroy', category.id));
    }
};
</script> 