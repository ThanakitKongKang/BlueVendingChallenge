import axios from "axios";

// Determine baseURL based on whether running in Docker or localhost
const isDocker = process.env.REACT_APP_IS_DOCKER === "true";
const baseURL = isDocker ? "http://backend" : "http://localhost:8000";

// Create an instance of Axios with the determined baseURL
const axiosInstance = axios.create({ baseURL });

// Define common headers
const headers = {
  Accept: "application/json",
  "Content-Type": "application/json",
};

// ApiService object containing various API endpoints
const ApiService = {
  // Insert a coin
  insertCoin: async (coinType) => {
    try {
      const response = await axiosInstance.post(
        "/coins/insert",
        { type: coinType },
        { headers }
      );
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.error ??
          "Failed to insert coin. Please try again."
      );
    }
  },

  // Reset the system
  reset: () => axiosInstance.get("/reset"),

  // Get available products
  getProducts: () => axiosInstance.get("/products"),

  // Get user's money aggregated
  getUserMoney: () => axiosInstance.get("/user/money"),

  // Cancel coin insertion
  cancelCoinInsert: () => axiosInstance.get("/cancel"),

  // Buy an item by its ID
  buyItem: async (itemId) => {
    try {
      const response = await axiosInstance.post(
        "/products/buy",
        { id: itemId },
        { headers }
      );
      return response.data;
    } catch (error) {
      throw new Error(
        error.response?.data?.error ??
          "An error occurred while processing your request. Please try again."
      );
    }
  },

  restockProduct: () => axiosInstance.get("/admin/restock"),
  resetChanges: () => axiosInstance.get("/admin/reset-changes"),

    // Get available changes in the machine
    getMachineCoins: () => axiosInstance.get("/machine/coins"),
};

export default ApiService;
