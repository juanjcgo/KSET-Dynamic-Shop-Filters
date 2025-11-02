import axios from "axios";

// Axios Instance for WordPress API
const axiosInstance = axios.create({
  baseURL: appLocalizer.apiUrl,
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': appLocalizer.nonce
  }
});

// Routes
export default {
  //::Register
  Register: (data) => axiosInstance.post('/aut/v1/register', data),

} 