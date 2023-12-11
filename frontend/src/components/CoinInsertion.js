import React, { useEffect, useState } from "react";
import ApiService from "../services/api"; // Import your API service

const coinsClasses = [
  {
    text: "1฿",
    amount: 1,
    classes: "bg-stone-50 text-black",
  },
  {
    text: "5฿",
    amount: 5,
    classes: "bg-stone-100 text-black",
  },
  {
    text: "10฿",
    amount: 10,
    classes: "bg-yellow-500 text-white",
  },
  {
    text: "20฿",
    amount: 20,
    classes: "bg-green-500 text-white",
  },
  {
    text: "100฿",
    amount: 100,
    classes: "bg-red-500 text-white",
  },
  {
    text: "500฿",
    amount: 500,
    classes: "bg-purple-500 text-white",
  },
  {
    text: "1000฿",
    amount: 1000,
    classes: "bg-stone-500 text-white",
  },
];
const CoinInsertion = ({ keyProp }) => {
  const [totalAmount, setTotalAmount] = useState(0);
  const [isLoading, setIsLoading] = useState(false);

  const insertCoin = async (coinType) => {
    setIsLoading(true);
    const response = await ApiService.insertCoin(coinType).catch((error) => {
      console.error("Error inserting coin:", error);
    });
    if (response) {
      console.log("Coin inserted successfully:", response);
      // Update total amount by adding the coinAmount
      setTotalAmount((prevAmount) => prevAmount + coinType * 1);
      setIsLoading(false);
    }
  };

  const cancelCoinInsert = async () => {
    await ApiService.cancelCoinInsert();
    setTotalAmount(0);
  };

  const fetchUserMoney = async () => {
    const response = await ApiService.getUserMoney().catch((error) => {
      console.error("Error fetch money:", error);
    });
    if (response?.data) {
      setTotalAmount(response.data * 1);
    }
  };

  useEffect(() => {
    fetchUserMoney();
  }, [keyProp]);

  return (
    <div>
      <div className="flex flex-col md:flex-col justify-between items-center border-b pb-2 space-y-2">
        <div className="w-full">
          Insert Amount:
          <div className="text-center bg-black rounded ">
            <p className="font-mono text-1xl sm:text-2xl md:text-3xl text-white p-2">
              {totalAmount}฿
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:mt-0 bg-white p-2 rounded-lg w-full">
          {coinsClasses.map((c) => (
            <button
              key={c.amount}
              className={`${c.classes} px-4 py-2 rounded-md shadow-lg`}
              onClick={() => insertCoin(c.amount)}
            >
              {c.text}
            </button>
          ))}
        </div>
      </div>
      <div>
        <button
          className={`w-full text-white px-4 py-2 rounded-md inline-flex items-center bg-gray-500`}
          onClick={cancelCoinInsert}
          disabled={totalAmount === 0 || isLoading}
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth={1.5}
            stroke="currentColor"
            className="w-6 h-6 mr-2"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <span>Cancel</span>
        </button>
      </div>
    </div>
  );
};

export default CoinInsertion;
