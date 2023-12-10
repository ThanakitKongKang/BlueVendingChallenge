// src/services/api.js

import axios from "axios";

const baseURL = "http://backend";

const axiosInstance = axios.create({
  baseURL,
});

const ApiService = {
  insertCoin: (coinAmount) =>
    axiosInstance.post("/insert-coin", { amount: coinAmount }),
  reset: () =>
    axiosInstance.get("/reset"),
};

export default ApiService;
