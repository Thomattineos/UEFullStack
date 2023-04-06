import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import CreateShopForm from './components/CreateShopForm';
import UpdateShopForm from './components/UpdateShopForm';
import ShopModal from './components/ShopModal';
import ProductModal from './components/ProductModal';
import CreateProductForm from './components/CreateProductForm';
import UpdateProductForm from './components/UpdateProductForm';
import 'bootstrap/dist/css/bootstrap.css';

function App() {
  return (
    <>
      <Navbar />
      <Routes>
        <Route path="/shops" element={<ShopModal />} />
        <Route path="/createShop" element={<CreateShopForm />} />
        <Route path="/:id/updateShop" element={<UpdateShopForm />} />

        <Route path="/products" element={<ProductModal />} />
        <Route path="/createProduct" element={<CreateProductForm />} />
        <Route path="/:id/updateProduct" element={<UpdateProductForm />} />
      </Routes>
    </>
  );
}

createRoot(document.getElementById('root')).render(
  <BrowserRouter>
    <App />
  </BrowserRouter>
);