// src/services/ProductService.js
import api from './api';

export default {
  getAll() {
    return api.get('/categories');
  },

  get(id) {
    return api.get(`/categories/${id}`);
  },

  // create(data) {
  //   return api.post('/categories', data);
  // },

  // update(id, data) {
  //   return api.put(`/categories/${id}`, data);
  // },

  // delete(id) {
  //   return api.delete(`/categories/${id}`);
  // },
};