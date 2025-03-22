<template>
    <CustomerLayout :table="table">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ürün Değerlendirme
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div v-if="product.image" class="flex-shrink-0 mr-4">
                                <img 
                                    :src="product.image" 
                                    alt="Ürün Fotoğrafı" 
                                    class="w-20 h-20 object-cover rounded"
                                >
                            </div>
                            <div v-else class="flex-shrink-0 mr-4">
                                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ product.name }}</h3>
                                <p class="text-sm text-gray-500">{{ product.description }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitRating">
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Puanınız
                                </label>
                                <div class="flex space-x-2">
                                    <button 
                                        v-for="star in 5" 
                                        :key="star"
                                        type="button"
                                        @click="form.score = star"
                                        class="text-2xl focus:outline-none"
                                        :class="star <= form.score ? 'text-yellow-400' : 'text-gray-300'"
                                    >
                                        <i class="fas fa-star"></i>
                                    </button>
                                </div>
                                <div v-if="form.errors.score" class="text-red-500 text-xs mt-1">{{ form.errors.score }}</div>
                            </div>

                            <div class="mb-6">
                                <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">
                                    Yorumunuz (İsteğe Bağlı)
                                </label>
                                <textarea
                                    id="comment"
                                    v-model="form.comment"
                                    rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Ürün hakkında düşüncelerinizi paylaşın..."
                                ></textarea>
                                <div v-if="form.errors.comment" class="text-red-500 text-xs mt-1">{{ form.errors.comment }}</div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <Link 
                                    :href="route('customer.order.status', { table_id: table.id, order_id: order.id })" 
                                    class="py-2 px-4 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    İptal
                                </Link>
                                <button 
                                    type="submit"
                                    :disabled="form.processing"
                                    class="py-2 px-4 border border-transparent rounded-md font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                                >
                                    <span v-if="existingRating">Güncelle</span>
                                    <span v-else>Gönder</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    table: Object,
    order: Object,
    product: Object,
    existingRating: Object,
});

const form = useForm({
    score: props.existingRating ? props.existingRating.score : 0,
    comment: props.existingRating ? props.existingRating.comment : '',
});

const submitRating = () => {
    form.post(route('customer.product.rate.store', {
        table_id: props.table.id,
        order_id: props.order.id,
        product_id: props.product.id,
    }));
};
</script> 