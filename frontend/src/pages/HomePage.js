import React from "react";
import VendingMachine from "../components/VendingMachine";

const HomePage = () => {
  return (
    <div className="bg-gray-100 min-h-screen flex flex-col items-center justify-center font-sans">
      <div className="w-full max-w-4xl px-4 md:px-0 mx-auto">
        <VendingMachine />
      </div>
    </div>
  );
};

export default HomePage;
