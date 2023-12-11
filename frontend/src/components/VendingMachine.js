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
      console.error("Error fetch Items:", error);
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
          .map(([denomination, quantity]) => `${denomination}฿ ${quantity}x`)
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
      console.error("Error purchasing the product:", error);
      toast.error(error.message);
    }
  };

  useEffect(() => {
    fetchItems();
  }, []);

  return (
    <div className="flex justify-center md:items-center h-screen overflow-hidden">
      <div className="bg-gray-200 p-4 rounded-lg shadow-lg">
        <div className="flex flex-col items-center h-full">
          <h2 className="text-3xl mb-4 text-indigo-600">Blue Vending</h2>
          <div className="flex space-x-4 h-full ">
            <ItemSelection items={items} onItemSelect={onItemSelect} />
            <div className="flex flex-col rounded">
              <CoinInsertion key={coinInsertionKey} />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default VendingMachine;
