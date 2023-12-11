import axios from "axios";

const isDocker = process.env.REACT_APP_IS_DOCKER === "true";
const baseURL = isDocker ? "http://backend" : "http://localhost:8000";

const axiosInstance = axios.create({
  baseURL,
});

const headers = {
  Accept: "application/json",
  "Content-Type": "application/json",
};

const ApiService = {
  insertCoin: async (coinType) => {
    try {
      const response = await axiosInstance.post(
        "/coins/insert",
        { type: coinType },
        { headers }
      );
      return response.data;
    } catch (error) {
      console.error("Error inserting coin:", error);
      throw new Error("Failed to insert coin. Please try again.");
    }
  },
  reset: () => axiosInstance.get("/reset"),
  getProducts: () => axiosInstance.get("/products"),
  getUserMoney: () => axiosInstance.get("/user/money"),
  cancelCoinInsert: () => axiosInstance.get("/cancel"),
  buyItem: async (itemId) => {
    try {
      const response = await axiosInstance.post(
        "/products/buy",
        { id: itemId },
        { headers }
      );
      return response.data;
    } catch (error) {
      if (
        error.response &&
        error.response.status === 400 &&
        error.response.data?.error
      ) {
        // Handle specific text response for 400 error
        throw new Error(error.response.data.error); // Pass the error response text
      } else {
        throw new Error("An error occurred while processing your request.");
      }
    }
  },
};

export default ApiService;
