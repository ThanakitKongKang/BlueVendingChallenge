// PaymentArea.js

import React, { useState } from 'react';
import ApiService from '../services/api';

const PaymentArea = () => {
  const [coinAmount, setCoinAmount] = useState(0);
  const [totalAmount, setTotalAmount] = useState(0);
  const [error, setError] = useState(null);

  const insertCoin = () => {
    ApiService.insertCoin(coinAmount)
      .then(response => {
        const updatedTotalAmount = totalAmount + parseInt(coinAmount);
        setTotalAmount(updatedTotalAmount);
        setCoinAmount(0); // Reset coin amount input after insertion
      })
      .catch(error => {
        setError('Error inserting coin. Please try again.');
        console.error('Error inserting coin:', error);
      });
  };

  return (
    <div>
      <h2>Payment Area</h2>
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
      <div>
        <p>Total Amount Inserted: {totalAmount}</p>
      </div>
      {error && <p>{error}</p>}
    </div>
  );
};

export default PaymentArea;
