<template>
    <AdminLayout title="Ürün Düzenle">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ürün Düzenle</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="form.post(route('admin.products.update', product.id), { method: 'put' })" class="space-y-6">
                            <!-- Ürün Adı -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    Ürün Adı
                                </label>
                                <input
                                    v-model="form.name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="name"
                                    type="text"
                                    placeholder="Ürün adını giriniz"
                                >
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                            </div>

                            <!-- Açıklama -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                    Açıklama
                                </label>
                                <textarea
                                    v-model="form.description"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="description"
                                    placeholder="Ürün açıklamasını giriniz"
                                    rows="3"
                                ></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                            </div>

                            <!-- Fiyat -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                                    Fiyat (₺)
                                </label>
                                <input
                                    v-model="form.price"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                >
                                <div v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</div>
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                                    Kategori
                                </label>
                                <select
                                    v-model="form.category_id"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="category_id"
                                >
                                    <option value="">Kategori seçiniz</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">{{ form.errors.category_id }}</div>
                            </div>

                            <!-- Alerjenler -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Alerjenler
                                </label>
                                <div class="mt-2 space-y-2">
                                    <label v-for="allergen in allergens" :key="allergen.id" class="inline-flex items-center mr-4">
                                        <input
                                            type="checkbox"
                                            :value="allergen.id"
                                            v-model="form.allergen_ids"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        >
                                        <span class="ml-2 text-gray-700">{{ allergen.name }}</span>
                                    </label>
                                </div>
                                <div v-if="form.errors.allergen_ids" class="text-red-500 text-xs mt-1">{{ form.errors.allergen_ids }}</div>
                            </div>

                            <!-- Mevcut Resim -->
                            <div v-if="product.image" class="mt-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Mevcut Resim
                                </label>
                                <img :src="'/storage/' + product.image" class="h-32 w-32 object-cover rounded" :alt="product.name">
                            </div>

                            <!-- Yeni Resim -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                                    Yeni Resim
                                </label>
                                <input
                                    type="file"
                                    @input="form.image = $event.target.files[0]"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="image"
                                    accept="image/*"
                                >
                                <div v-if="form.errors.image" class="text-red-500 text-xs mt-1">{{ form.errors.image }}</div>
                            </div>

                            <!-- Butonlar -->
                            <div class="flex items-center justify-between">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    Güncelle
                                </button>
                                <Link
                                    :href="route('admin.products.index')"
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

const props = defineProps({
    product: Object,
    categories: Array,
    allergens: Array
});

const form = useForm({
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
    category_id: props.product.category_id,
    allergen_ids: props.product.allergens.map(a => a.id),
    image: null
});
</script> 