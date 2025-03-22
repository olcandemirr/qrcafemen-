<template>
    <CustomerLayout :table="table">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ category.name }}
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Geri Butonu -->
                <div class="mb-4">
                    <Link 
                        :href="route('customer.menu', table.id)" 
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <i class="fas fa-arrow-left mr-2"></i> Tüm Kategoriler
                    </Link>
                </div>

                <!-- Ürünler -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">{{ category.name }} Ürünleri</h2>
                    </div>
                    <div class="p-6 divide-y">
                        <div v-if="products.length === 0" class="py-4 text-gray-500 text-center">
                            Bu kategoride henüz ürün bulunmuyor.
                        </div>
                        <div 
                            v-for="product in products" 
                            :key="product.id" 
                            class="flex py-4 space-x-4"
                        >
                            <div v-if="product.image" class="flex-shrink-0 w-20 h-20">
                                <img 
                                    :src="product.image" 
                                    alt="Ürün Fotoğrafı" 
                                    class="w-full h-full object-cover rounded"
                                >
                            </div>
                            <div v-else class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <Link 
                                    :href="route('customer.product.show', { table_id: table.id, product_id: product.id })"
                                    class="block"
                                >
                                    <h3 class="text-base font-medium text-gray-900">{{ product.name }}</h3>
                                    <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ product.description }}</p>
                                    <p class="mt-2 text-sm font-semibold text-gray-900">{{ formatPrice(product.price) }} TL</p>
                                </Link>
                            </div>
                            <div class="flex-shrink-0 flex flex-col justify-center">
                                <button 
                                    @click="addToCart(product)"
                                    class="px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <i class="fas fa-plus mr-1"></i> Sepete Ekle
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    table: Object,
    category: Object,
    products: Array,
});

const formatPrice = (price) => {
    return Number(price).toFixed(2);
};

const addToCart = (product) => {
    router.post(route('customer.cart.add', props.table.id), {
        product_id: product.id,
        quantity: 1,
    });
};
</script> 