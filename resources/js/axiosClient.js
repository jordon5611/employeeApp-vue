import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://localhost:8000', // Your backend URL
  withCredentials: true, // Ensures cookies are sent with the request
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// Add a session ID dynamically
const sendRequestWithSession = async (url, method = 'GET', data = {}, sessionId = null) => {
  const headers = {};
  if (sessionId) {
    headers['X-Session-ID'] = sessionId; // Add session ID as a custom header
  }

  return apiClient({
    url,
    method,
    data,
    headers,
  });
};

export default sendRequestWithSession;
