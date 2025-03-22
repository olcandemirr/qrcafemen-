<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('customer.menu', table.id)">
                                <span class="font-semibold text-xl text-gray-800">QR Cafe</span>
                            </Link>
                        </div>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <Link 
                            :href="route('customer.cart', table.id)" 
                            class="relative p-2 text-gray-600 hover:text-gray-800 focus:outline-none"
                        >
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span v-if="cartCount > 0" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                {{ cartCount }}
                            </span>
                        </Link>
                    </div>

                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path 
                                    :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M4 6h16M4 12h16M4 18h16" 
                                />
                                <path 
                                    :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M6 18L18 6M6 6l12 12" 
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <Link 
                        :href="route('customer.menu', table.id)" 
                        class="block pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out"
                    >
                        Menü
                    </Link>
                    <Link 
                        :href="route('customer.cart', table.id)" 
                        class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                        Sepet <span v-if="cartCount > 0" class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full ml-2">{{ cartCount }}</span>
                    </Link>
                    <Link 
                        :href="route('customer.order.history', table.id)" 
                        class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                        Siparişlerim
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header v-if="$slots.header" class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Table Info -->
        <div class="bg-white shadow-sm mt-2">
            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <i class="fas fa-utensils mr-2 text-gray-700"></i>
                    <h2 class="font-semibold text-md text-gray-800">Masa: {{ table.name }}</h2>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow-inner mt-6 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center text-sm text-gray-500">
                    <p>&copy; {{ new Date().getFullYear() }} QR Cafe</p>
                    <p class="mt-1">Kolay ve hızlı sipariş için QR menü sistemi</p>
                </div>
            </div>
        </footer>

        <!-- Toast Notification -->
        <div v-if="$page.props.flash.success || $page.props.flash.error" 
            class="fixed bottom-4 right-4 z-50 max-w-sm"
        >
            <div v-if="$page.props.flash.success" 
                class="bg-green-500 text-white px-4 py-3 rounded shadow-lg flex items-center"
            >
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash.error" 
                class="bg-red-500 text-white px-4 py-3 rounded shadow-lg flex items-center"
            >
                <i class="fas fa-exclamation-circle mr-2"></i>
                <p>{{ $page.props.flash.error }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    table: Object
});

const page = usePage();
const showingNavigationDropdown = ref(false);

const cartCount = computed(() => {
    const cartItems = page.props.cartItems || [];
    return cartItems.reduce((total, item) => total + item.quantity, 0);
});
</script> 