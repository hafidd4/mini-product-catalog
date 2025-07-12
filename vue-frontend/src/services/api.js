import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost/api', // or full URL like 'http://localhost:8000/api'
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    // Optional custom header:
    // 'X-Header-Key': 'your-secret-key',
  },
});

export default api;