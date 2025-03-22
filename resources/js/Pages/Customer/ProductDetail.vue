<template>
    <CustomerLayout :table="table">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ürün Detayı
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Ürün Görseli -->
                            <div>
                                <div v-if="product.image" class="w-full h-80 overflow-hidden rounded-lg">
                                    <img 
                                        :src="product.image" 
                                        alt="Ürün Fotoğrafı" 
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                                <div v-else class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-utensils text-gray-400 text-6xl"></i>
                                </div>
                            </div>

                            <!-- Ürün Bilgileri -->
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ product.name }}</h1>
                                <p class="text-lg font-semibold text-indigo-600 mt-2">{{ formatPrice(product.price) }} TL</p>
                                
                                <div class="mt-4">
                                    <h2 class="text-lg font-medium text-gray-900">Açıklama</h2>
                                    <p class="mt-2 text-gray-700">{{ product.description }}</p>
                                </div>

                                <!-- Alerjenler -->
                                <div v-if="product.allergens && product.allergens.length > 0" class="mt-6">
                                    <h2 class="text-lg font-medium text-gray-900">Alerjenler</h2>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <span 
                                            v-for="allergen in product.allergens" 
                                            :key="allergen.id"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800"
                                        >
                                            <i v-if="allergen.icon" :class="allergen.icon + ' mr-1'"></i>
                                            {{ allergen.name }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Ürün Notları -->
                                <div class="mt-6">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Özel İstekler (İsteğe Bağlı)</label>
                                    <textarea
                                        id="notes"
                                        v-model="form.notes"
                                        rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Özel isteklerinizi belirtebilirsiniz..."
                                    ></textarea>
                                </div>

                                <!-- Miktar -->
                                <div class="mt-6">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Miktar</label>
                                    <div class="flex items-center mt-1">
                                        <button 
                                            @click="decrementQuantity" 
                                            class="px-3 py-1 border border-gray-300 bg-gray-100 text-gray-600 rounded-l"
                                        >
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input 
                                            id="quantity"
                                            v-model.number="form.quantity" 
                                            type="number" 
                                            min="1" 
                                            class="w-16 border-y border-gray-300 py-1 text-center focus:ring-0 focus:outline-none"
                                        >
                                        <button 
                                            @click="incrementQuantity" 
                                            class="px-3 py-1 border border-gray-300 bg-gray-100 text-gray-600 rounded-r"
                                        >
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Sepete Ekle -->
                                <div class="mt-8 flex space-x-3">
                                    <button 
                                        @click="addToCart" 
                                        class="flex-1 bg-indigo-600 py-3 px-8 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <i class="fas fa-shopping-cart mr-2"></i> Sepete Ekle
                                    </button>
                                    <Link 
                                        :href="route('customer.menu', table.id)" 
                                        class="py-3 px-8 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        <i class="fas fa-arrow-left mr-2"></i> Geri
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    table: Object,
    product: Object,
});

const form = ref({
    product_id: props.product.id,
    quantity: 1,
    notes: '',
});

const formatPrice = (price) => {
    return Number(price).toFixed(2);
};

const incrementQuantity = () => {
    form.value.quantity++;
};

const decrementQuantity = () => {
    if (form.value.quantity > 1) {
        form.value.quantity--;
    }
};

const addToCart = () => {
    router.post(route('customer.cart.add', props.table.id), form.value);
};
</script> 