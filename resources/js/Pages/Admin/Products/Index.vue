<template>
    <AdminLayout title="Ürünler">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ürünler</h2>
                <Link :href="route('admin.products.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Yeni Ürün Ekle
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Resim
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ürün Adı
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kategori
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fiyat
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Alerjenler
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            İşlemler
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="product in products" :key="product.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img v-if="product.image" :src="'/storage/' + product.image" class="h-12 w-12 object-cover rounded" :alt="product.name">
                                            <div v-else class="h-12 w-12 bg-gray-100 rounded flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                                            <div class="text-sm text-gray-500">{{ product.description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ product.category.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ formatPrice(product.price) }} ₺
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                <span 
                                                    v-for="allergen in product.allergens" 
                                                    :key="allergen.id"
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800"
                                                >
                                                    {{ allergen.name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <Link :href="route('admin.products.edit', product.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Düzenle
                                            </Link>
                                            <button @click="deleteProduct(product)" class="text-red-600 hover:text-red-900">
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
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    products: Array
});

const formatPrice = (price) => {
    return Number(price).toLocaleString('tr-TR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

const deleteProduct = (product) => {
    if (confirm('Bu ürünü silmek istediğinizden emin misiniz?')) {
        useForm().delete(route('admin.products.destroy', product.id));
    }
};
</script> 