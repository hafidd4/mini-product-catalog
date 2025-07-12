<template>
    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label>Product Name *</label>
        <input
          v-model="form.name"
          type="text"
          
          placeholder="Enter product name"
          class="form-control"
          :class="{ 'error': errors.name }"
        >
        <div v-if="errors.name" class="error">{{ errors.name[0] }}</div>
      </div>
  
      <div class="form-group">
        <label>Price *</label>
        <input
          v-model="form.price"
          type="number"
          step="0.01"
          min="0"
          
          placeholder="0.00"
          class="form-control"
          :class="{ 'error': errors.price }"
        >
        <div v-if="errors.price" class="error">{{ errors.price[0] }}</div>
      </div>
  
      <div class="form-group">
        <label>Category *</label>
        <select
          v-model="form.category_id"
          
          class="form-control"
          :class="{ 'error': errors.category_id }"
        >
          <option value="">Select a category</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
        <div v-if="errors.category_id" class="error">{{ errors.category_id[0] }}</div>
      </div>
  
      <div class="form-group">
        <label>Description</label>
        <textarea
          v-model="form.description"
          placeholder="Enter product description"
          class="form-control"
          rows="3"
          :class="{ 'error': errors.description }"
        ></textarea>
        <div v-if="errors.description" class="error">{{ errors.description[0] }}</div>
      </div>
  
      <div v-if="submitError" class="error">
        {{ submitError }}
      </div>
  
      <div v-if="submitSuccess" class="success">
        {{ submitSuccess }}
      </div>
  
      <div style="margin-top: 20px;">
        <button type="submit" :disabled="submitting" class="btn btn-primary">
          {{ submitting ? 'Saving...' : (isEditing ? 'Update Product' : 'Add Product') }}
        </button>
        <button type="button" @click="$emit('cancel')" class="btn btn-secondary" style="margin-left: 10px;">
          Cancel
        </button>
      </div>
    </form>
  </template>
  

<script setup>

import { ref, reactive, computed, onMounted, watch } from 'vue'
import ProductService from './../services/ProductService.js'


// Props
const props = defineProps({
  product: {
    type: Object,
    default: null
  },
  categories: {
    type: Array,
    required: true
  }
})

// Emits
const emit = defineEmits(['product-saved', 'cancel'])

// Reactive state
const form = reactive({
  name: '',
  price: '',
  category_id: '',
  description: '',
})

const errors = ref({})
const submitting = ref(false)
const submitError = ref(null)
const submitSuccess = ref(null)

// Computed
const isEditing = computed(() => !!props.product)

// Set initial form data if editing
onMounted(() => {
  if (props.product) {
    Object.assign(form, {
      name: props.product.name || '',
      price: props.product.price || '',
      category_id: props.product.category.id || '',
      description: props.product.description || ''
    })
  }
})

// Submit form function
const submitForm = async () => {
  submitting.value = true
  errors.value = {}
  submitError.value = null
  submitSuccess.value = null

  try {
    if (isEditing.value) {
      await ProductService.update(props.product.id, form)
      submitSuccess.value = 'Product updated successfully!'
    } else {
      await ProductService.create(form)
      submitSuccess.value = 'Product created successfully!'
    }

    setTimeout(() => {
      emit('product-saved')
    }, 1000)

  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors || {}
    } else {
      submitError.value = 'Failed to save product. Please try again.'
    }
    console.error('Error saving product:', error)
  } finally {
    submitting.value = false
  }
}
</script>
  