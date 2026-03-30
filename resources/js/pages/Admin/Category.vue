<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Header from '@/components/btbcomponents/Header.vue';
import Footer from '@/components/btbcomponents/Footer.vue';

const categories = ref([]);
console.log(categories);
onMounted(() => {
    fetchCategories();
});

const fetchCategories = () => {
    axios.get('/api/category')
        .then(response => {
            categories.value = response.data;
            console.log('Fetched categories:', categories.value);
        })
        .catch(error => {
            console.error('Error fetching categories:', error);
        });
};
</script>
<template>
    <Header />
    <div class="container">
        <h1>Category</h1>
        <p>This is the category page.</p>
        <ul>
            <li v-for="category in categories" :key="category.id">
                {{ category.category_name }} - {{ category.description }}
            </li>
        </ul>
    </div>
    <Footer />
</template>