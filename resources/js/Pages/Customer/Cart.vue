<template>
    <CustomerLayout :table="table">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sepetim
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="cartItems.length === 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center">
                        <i class="fas fa-shopping-cart text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-700">Sepetiniz boş</h3>
                        <p class="mt-2 text-gray-500">Menüden ürün ekleyerek siparişinizi oluşturabilirsiniz.</p>
                        <Link 
                            :href="route('customer.menu', table.id)" 
                            class="mt-6 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <i class="fas fa-arrow-left mr-2"></i> Menüye Dön
                        </Link>
                    </div>
                </div>

                <div v-else>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-4 border-b">
                            <h3 class="text-lg font-medium text-gray-800">Sepet Öğeleri</h3>
                        </div>
                        <div class="divide-y">
                            <div v-for="(item, index) in cartItems" :key="index" class="p-4 flex items-center">
                                <div class="flex-1">
                                    <h4 class="text-base font-medium text-gray-900">{{ item.name }}</h4>
                                    <p v-if="item.notes" class="text-sm text-gray-600 mt-1">
                                        <span class="font-semibold">Not:</span> {{ item.notes }}
                                    </p>
                                    <div class="mt-2 flex space-x-4 items-center">
                                        <div class="flex items-center space-x-2">
                                            <button 
                                                @click="decrementItemQuantity(index)" 
                                                class="text-gray-500 hover:text-gray-700 focus:outline-none"
                                            >
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                            <span class="text-gray-700">{{ item.quantity }}</span>
                                            <button 
                                                @click="incrementItemQuantity(index)" 
                                                class="text-gray-500 hover:text-gray-700 focus:outline-none"
                                            >
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        </div>
                                        <span class="text-gray-500">x</span>
                                        <span class="text-gray-700">{{ formatPrice(item.price) }} TL</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-base font-semibold text-gray-900">{{ formatPrice(item.price * item.quantity) }} TL</p>
                                    <button 
                                        @click="removeItem(index)"
                                        class="text-red-500 hover:text-red-700 text-sm mt-2 focus:outline-none"
                                    >
                                        <i class="fas fa-trash mr-1"></i> Kaldır
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-base text-gray-700">Ara Toplam:</span>
                                <span class="text-base font-medium text-gray-900">{{ formatPrice(subtotal) }} TL</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-900">Toplam:</span>
                                    <span class="text-lg font-semibold text-gray-900">{{ formatPrice(subtotal) }} TL</span>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="orderNotes" class="block text-sm font-medium text-gray-700">Sipariş Notu (İsteğe Bağlı)</label>
                                <textarea
                                    id="orderNotes"
                                    v-model="orderNotes"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Siparişiniz için genel notlar ekleyebilirsiniz..."
                                ></textarea>
                            </div>

                            <div class="mt-6 flex space-x-3">
                                <button 
                                    @click="placeOrder" 
                                    class="flex-1 bg-green-600 py-3 px-8 border border-transparent rounded-md font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                >
                                    <i class="fas fa-check-circle mr-2"></i> Siparişi Tamamla
                                </button>
                                <Link 
                                    :href="route('customer.menu', table.id)" 
                                    class="py-3 px-8 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <i class="fas fa-plus mr-2"></i> Ürün Ekle
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';

const props = defineProps({
    table: Object,
    cartItems: Array,
});

const orderNotes = ref('');

const subtotal = computed(() => {
    return props.cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
});

const formatPrice = (price) => {
    return Number(price).toFixed(2);
};

const incrementItemQuantity = (index) => {
    router.patch(route('customer.cart.update', props.table.id), {
        index: index,
        quantity: props.cartItems[index].quantity + 1,
    }, { preserveScroll: true });
};

const decrementItemQuantity = (index) => {
    if (props.cartItems[index].quantity > 1) {
        router.patch(route('customer.cart.update', props.table.id), {
            index: index,
            quantity: props.cartItems[index].quantity - 1,
        }, { preserveScroll: true });
    }
};

const removeItem = (index) => {
    router.delete(route('customer.cart.remove', { table_id: props.table.id, index: index }), {
        preserveScroll: true,
    });
};

const placeOrder = () => {
    router.post(route('customer.order.store', props.table.id), {
        notes: orderNotes.value,
    });
};
</script> 