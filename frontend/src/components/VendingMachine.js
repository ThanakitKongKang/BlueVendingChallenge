import React, { useEffect, useState } from "react";
import ApiService from "../services/api";
import CoinInsertion from "./CoinInsertion";
import ItemSelection from "./ItemSelection";
import { toast } from "react-toastify";

const VendingMachine = () => {
  const [items, setItems] = useState([]);
  const [coinInsertionKey, setCoinInsertionKey] = useState(0); // State variable to force re-render

  const fetchItems = async () => {
    const response = await ApiService.getProducts().catch((error) => {
      toast.error("Error fetch Items");
    });
    setItems(response?.data);
  };
  const calculateChanges = (changes) => {
    let totalSum = 0;

    // Loop through the object keys
    for (const key in changes) {
      if (Object.hasOwnProperty.call(changes, key)) {
        const value = changes[key];
        totalSum += parseInt(key) * value; // Multiply key by value and add to totalSum
      }
    }

    return totalSum;
  };

  const onItemSelect = async (item) => {
    try {
      const response = await toast.promise(ApiService.buyItem(item), {
        pending: "Purchasing product",
        success: "Purchase successful! 👌",
      });

      if (response) {
        setCoinInsertionKey((prevKey) => prevKey + 1);
        fetchItems();
        const changes = calculateChanges(response.data);
        let changesDetail = Object.entries(response.data)
          .map(([denomination, quantity]) => `${denomination}฿ x${quantity}`)
          .join(", ");
        if (changes === 0) return;
        toast.info(
          <div>
            <div>Received changes: {changes}฿</div>
            <div>{changesDetail}</div>
          </div>
        );
      }
    } catch (error) {
      toast.error(error.message);
    }
  };

  const restockProduct = async () => {
    const response = await ApiService.restockProduct().catch(() => {
      toast.error("Error restocking product");
    });
    if (response) {
      fetchItems();
      toast.success("Restocked products.");
    }
  };

  const resetChanges = async () => {
    const response = await ApiService.resetChanges().catch(() => {
      toast.error("Error restocking changes");
    });
    if (response) {
      setCoinInsertionKey((prevKey) => prevKey + 1);
      toast.success("Restocked changes.");
    }
  };

  const initialFetch = async () => {
    await toast.promise(fetchItems(), {
      pending: "Loading products",
      success: "Loading successful! 👌",
    });
  };
  useEffect(() => {
    initialFetch();
  }, []);

  return (
    <div className="flex justify-center md:items-center h-screen overflow-hidden w-full">
      <div className="bg-gray-200 p-4 rounded-lg shadow-lg w-full">
        <div className="flex flex-col items-center h-full">
          <h2 className="text-3xl mb-4 text-indigo-600">Blue Vending</h2>
          <div className="flex space-x-4 h-full w-full ">
            <div className="w-3/5 pb-16 sm:pb-4 overflow-y-auto">
              <ItemSelection items={items} onItemSelect={onItemSelect} />
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 text-xs">
                <button
                  className={`text-white px-2 py-1 rounded-md inline-flex items-center bg-gray-500`}
                  onClick={restockProduct}
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    strokeWidth={1.5}
                    stroke="currentColor"
                    className="w-3 h-3 mr-2"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                    />
                  </svg>
                  <span>Restock Products</span>
                </button>
                <button
                  className={`text-white px-4 py-2 rounded-md inline-flex items-center bg-gray-500`}
                  onClick={resetChanges}
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    strokeWidth={1.5}
                    stroke="currentColor"
                    className="w-3 h-3 mr-2"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                    />
                  </svg>
                  <span>Restock Available Changes</span>
                </button>
              </div>
            </div>
            <div className="flex flex-col rounded w-2/5">
              <CoinInsertion key={coinInsertionKey} />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default VendingMachine;
