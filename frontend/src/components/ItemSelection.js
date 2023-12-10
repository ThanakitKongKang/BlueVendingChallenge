// Sample structure of ItemSelection.js

import React from 'react';

const ItemSelection = ({ items, onItemSelect }) => {
  return (
    <div>
      <h2>Item Selection</h2>
      <ul>
        {items.map(item => (
          <li key={item.id}>
            <span>{item.name}</span>
            <button onClick={() => onItemSelect(item.id)}>Select</button>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default ItemSelection;
