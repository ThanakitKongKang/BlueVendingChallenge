import React, { useEffect, useState } from "react";
import ApiService from "../services/api"; // Import your API service
import { toast } from "react-toastify";

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
  const [machineCoins, setMachineCoins] = useState([]);

  const insertCoin = async (coinType) => {
    setIsLoading(true);
    const response = await ApiService.insertCoin(coinType).catch((error) => {
      toast.error(error.message);
    });
    if (response) {
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
    const response = await ApiService.getUserMoney().catch(() => {
      toast.error("Error fetch money");
    });
    if (response?.data) {
      setTotalAmount(response.data * 1);
    }
  };

  const fetchMachineCoins = async () => {
    const response = await ApiService.getMachineCoins().catch(() => {
      toast.error("Error fetch machine coins");
    });
    if (response?.data) {
      setMachineCoins(response.data);
    }
  };

  useEffect(() => {
    fetchUserMoney();
    fetchMachineCoins();
  }, [keyProp]);

  return (
    <div className="flex flex-col  space-y-2 h-full vending-coin-insertion">
      <div>
        <div className="flex flex-col justify-between items-center border-b pb-2 space-y-2">
          <div className="w-full">
            <span className="title">Insert Amount:</span>
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
      <div>
        <span>Available Changes:</span>
        <div className="grid grid-cols-1 md:grid-cols-2 md:grid-cols-2 gap-2 md:mt-0 p-2 rounded-lg w-full">
          {machineCoins.map((mc) => {
            if (mc.amount === "0") return null;
            return (
              <span key={mc.type} className={`px-2 rounded-md shadow-md`}>
                <div className="grid grid-cols-2"><span>{mc.type}฿</span><span className="text-right">x{mc.amount}</span></div>
              </span>
            );
          })}
        </div>
      </div>
    </div>
  );
};

export default CoinInsertion;
