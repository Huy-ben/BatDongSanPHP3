<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

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
    <div class="container">
        <h1>Category</h1>
        <p>This is the category page.</p>
        <ul>
            <li v-for="category in categories" :key="category.id">
                {{ category.category_name }} - {{ category.description }}
            </li>
        </ul>
    </div>
</template>