import React from "react";

const ItemSelection = ({ items, onItemSelect }) => {
  return (
    <div className="vending-item-selection">
      <ul className="pb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4 max-h-full overflow-y-auto">
        {items?.data?.length > 0 &&
          items.data.map((item) => (
            <li key={item.id} className="bg-white rounded-lg shadow-md p-4">
              <div className="grid grid-rows-1 md:grid-rows-2 h-full">
                <div className="flex items-center justify-center mb-4">
                  <img
                    src={`images/${item.id}.png`}
                    alt=""
                    className="w-24 h-24 object-contain"
                  />
                </div>
                <div className="text-center flex flex-col h-full">
                  <span className="font-bold text-xl mb-2 line-clamp-1 w-full hover:line-clamp-4">
                    {item.name}
                  </span>
                  <div className="flex flex-col sm:flex-row justify-around mb-2">
                    <div>
                      <span className="block font-semibold text-gray-600">
                        Price:
                      </span>
                      <span className="block text-lg">{item.price}฿</span>
                    </div>
                    <div>
                      <span className="block font-semibold text-gray-600">
                        Stock Remaining:
                      </span>
                      <span className="block text-lg">{item.amount}</span>
                    </div>
                  </div>
                  <button
                    onClick={() => onItemSelect(item.id)}
                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2"
                  >
                    Select
                  </button>
                </div>
              </div>
            </li>
          ))}
      </ul>
    </div>
  );
};

export default ItemSelection;
