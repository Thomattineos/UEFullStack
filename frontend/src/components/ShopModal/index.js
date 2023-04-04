import React, { useState, useEffect } from 'react';

function ShopModal() {
  const [shops, setShops] = useState([]);

  useEffect(() => {
    fetch('http://localhost:8000/api/shops')
      .then(response => response.json())
      .then(data => {
        const processedData = data.map(shop => ({
            ...shop,
            openingHours: new Date(shop.openingHours * 1000).toLocaleTimeString(),
            closingHours: new Date(shop.openingHours * 1000).toLocaleTimeString(),
        }));
        setShops(processedData);
    });
  }, []);

  return (
    <div>
      <h1>Liste des boutiques</h1>
      <ul>
        {shops.map(shop => (
          <li key={shop.id}>
            <h1>{shop.name}</h1>
            <h2>{shop.openingHours} - {shop.closingHours}</h2>
            <p>{shop.leave}</p>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default ShopModal;