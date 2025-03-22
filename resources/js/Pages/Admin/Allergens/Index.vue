<template>
    <AdminLayout title="Alerjenler">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Alerjenler</h2>
                <Link :href="route('admin.allergens.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Yeni Alerjen Ekle
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
                                        İkon
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alerjen Adı
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
                                <tr v-for="allergen in allergens" :key="allergen.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <i v-if="allergen.icon" :class="allergen.icon" class="text-xl"></i>
                                        <span v-else>-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ allergen.name }}</td>
                                    <td class="px-6 py-4">{{ allergen.description || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ allergen.products_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link :href="route('admin.allergens.edit', allergen.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Düzenle
                                        </Link>
                                        <button @click="deleteAllergen(allergen)" class="text-red-600 hover:text-red-900">
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
    allergens: Array
});

const deleteAllergen = (allergen) => {
    if (confirm('Bu alerjeni silmek istediğinizden emin misiniz?')) {
        useForm().delete(route('admin.allergens.destroy', allergen.id));
    }
};
</script> 