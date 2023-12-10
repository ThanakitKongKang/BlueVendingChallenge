// components/CoinInsertion.js

import React, { useState } from "react";
import ApiService from "../services/api"; // Import your API service

const CoinInsertion = () => {
  const [coinAmount, setCoinAmount] = useState(0);
  const [error, setError] = useState(null);

  const insertCoin = () => {
    ApiService.insertCoin(coinAmount)
      .then((response) => {
        console.log("Coin inserted successfully:", response.data);
        // todo:Handle UI updates or other actions upon successful insertion
      })
      .catch((error) => {
        setError("Error inserting coin. Please try again.");
        console.error("Error inserting coin:", error);
      });
  };

  return (
    <div>
      <h2>Coin/Banknote Insertion</h2>
      <div>
        <label htmlFor="coinAmount">Enter Coin/Banknote Amount:</label>
        <input
          type="number"
          id="coinAmount"
          value={coinAmount}
          onChange={(e) => setCoinAmount(e.target.value)}
        />
        <button onClick={insertCoin}>Insert Coin</button>
      </div>
      {error && <p>{error}</p>}
    </div>
  );
};

export default CoinInsertion;
