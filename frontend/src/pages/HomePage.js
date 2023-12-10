// HomePage.js

import React from "react";
import CoinInsertion from "../components/CoinInsertion";
import PaymentArea from "../components/PaymentArea";

const HomePage = () => {
  return (
    <div>
      <h1>Home Page</h1>
      <CoinInsertion />
      <PaymentArea />
    </div>
  );
};

export default HomePage;
