import React, { useState } from 'react';
import ApiService from '../services/api';

const AdminPanel = () => {
  const [error, setError] = useState(null);

  // Function to handle reset to default
  const resetToDefault = () => {
    ApiService.reset()
      .then(response => {
        console.log('Reset successful:', response.data);
      })
      .catch(error => {
        setError('Error resetting to default. Please try again.');
        console.error('Error resetting to default:', error);
      });
  };

  return (
    <div>
      <h2>Admin Panel</h2>
      <div>
        <h3>Reset to Default</h3>
        <button onClick={resetToDefault}>Reset</button>
      </div>
      {error && <p>{error}</p>}
    </div>
  );
};

export default AdminPanel;
