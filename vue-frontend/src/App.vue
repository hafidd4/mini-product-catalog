


<script setup>
import { ref, reactive, onMounted } from 'vue'
// import ProductForm from './components/ProductForm.vue'
import ProductService from './services/ProductService.js'
import CategoryService from './services/CategoryService.js'
import ProductForm from './components/ProductForm.vue'

// Reactive state
const products = ref([])
const categories = ref([])
const loading = ref(false)
const error = ref(null)
const selectedCategory = ref('')
const searchTerm = ref('')
const sortDirection = ref('')
const showAddForm = ref(false)
const editingProduct = ref(null)
const searchTimeout = ref(null)

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 2,
  total: 0
})

// Load categories
const loadCategories = async () => {
  try {
    const response = await CategoryService.getAll()
    categories.value = response.data.data
  } catch (err) {
    error.value = 'Failed to load categories'
    console.error('Error loading categories:', err)
  }
}

// Load products
const loadProducts = async (page = 1) => {
  loading.value = true
  error.value = null

  try {
    const params = {
      page,
      per_page: pagination.per_page
    }

    if (selectedCategory.value) {
      params.category_id = selectedCategory.value
    }

    if (searchTerm.value) {
      params.search = searchTerm.value
    }

    if (sortDirection.value) {
      params.sort_by = 'price'
      params.sort_direction = sortDirection.value
    }

    const response = await ProductService.getAll(params)
    // console.log('Products response:', response.data)
    products.value = response.data.data.data

    Object.assign(pagination, {
      current_page: response.data.data.current_page,
      last_page: response.data.data.last_page,
      per_page: response.data.data.per_page,
      total: response.data.data.total
    })
  } catch (err) {
    error.value = 'Failed to load products'
    console.error('Error loading products:', err)
  } finally {
    loading.value = false
  }
}

// Debounce search
const debounceSearch = () => {
  clearTimeout(searchTimeout.value)
  searchTimeout.value = setTimeout(() => {
    loadProducts()
  }, 500)
}

// Change page
const changePage = (page) => {
  if (page >= 1 && page <= pagination.last_page) {
    loadProducts(page)
  }
}

// Edit product
const editProduct = (product) => {
  editingProduct.value = { ...product }
  showAddForm.value = false
}

// Delete product
const deleteProduct = async (productId) => {
  if (!confirm('Are you sure you want to delete this product?')) return

  try {
    await ProductService.delete(productId)
    loadProducts(pagination.current_page)
    loadCategories()
  } catch (err) {
    error.value = 'Failed to delete product'
    console.error('Error deleting product:', err)
  }
}

// Product saved callback
const onProductSaved = () => {
  showAddForm.value = false
  editingProduct.value = null
  
  loadProducts(pagination.current_page)
  loadCategories()
}

// Init
onMounted(() => {
  loadCategories()
  loadProducts()
})
</script>

<template>
  <div id="app">
    <div class="container">
      <div class="header">
        <h1>Product Catalog</h1>
        <p>Manage your products </p>
      </div>

      <div class="card">
        <div class="filters">
          <div class="form-group">
            <label>Filter by Category:</label>
            <select v-model="selectedCategory" @change="loadProducts" class="form-control">
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }} ({{ category.products_count }})
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>Search Products:</label>
            <input
              v-model="searchTerm"
              @input="debounceSearch"
              type="text"
              placeholder="Search by name..."
              class="form-control"
            >
          </div>

          <div class="form-group">
            <label>Sort by Price:</label>
            <select v-model="sortDirection" @change="loadProducts" class="form-control">
              <option value="">Default</option>
              <option value="asc">Price: Low to High</option>
              <option value="desc">Price: High to Low</option>
            </select>
          </div>

          <div class="form-group">
            <label>&nbsp;</label>
            <button @click="showAddForm = true" class="btn btn-primary">
              Add New Product
            </button>
          </div>
        </div>
      </div>

      <!-- Add Product Form -->
      <div v-if="showAddForm" class="card">
        <h3>Add New Product</h3>
        <ProductForm
          :product="null"
          :categories="categories"
          @product-saved="onProductSaved"
          @cancel="showAddForm = false"
        />
      </div>

      <!-- Edit Product Form -->
      <div v-if="editingProduct" class="card">
        <h3>Edit Product</h3>
        <ProductForm
          :product="editingProduct"
          :categories="categories"
          @product-saved="onProductSaved"
          @cancel="editingProduct = null"
        />
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="loading">
        Loading products...
      </div>

      <!-- Error State -->
      <div v-if="error" class="error">
        {{ error }}
      </div>

      <!-- Products Grid -->
      <div v-if="!loading && products.length > 0" class="product-grid">
        <div v-for="product in products" :key="product.id" class="product-card">
          <h4>{{ product.name }}</h4>
          <p><strong>Price:</strong> ${{ product.price }}</p>
          <p><strong>Category:</strong> {{ product.category?.name }}</p>
          <p v-if="product.description">{{ product.description }}</p>
          <div style="margin-top: 15px;">
            <button @click="editProduct(product)" class="btn btn-secondary" style="margin-right: 10px;">
              Edit
            </button>
            <button @click="deleteProduct(product.id)" class="btn btn-danger">
              Delete
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="!loading && products.length === 0" class="card">
        <p style="text-align: center; color: #666;">
          No products found. {{ searchTerm ? 'Try adjusting your search.' : 'Add your first product!' }}
        </p>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.total > pagination.per_page" class="pagination">
        <button
          @click="changePage(pagination.current_page - 1)"
          :disabled="pagination.current_page === 1"
          class="btn btn-secondary"
        >
          Previous
        </button>
        
        <span style="padding: 8px 15px;">
          Page {{ pagination.current_page }} of {{ pagination.last_page }}
        </span>
        
        <button
          @click="changePage(pagination.current_page + 1)"
          :disabled="pagination.current_page === pagination.last_page"
          class="btn btn-secondary"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>


