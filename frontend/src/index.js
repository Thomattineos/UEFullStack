import React from 'react';
import { createRoot} from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import ShopForm from './components/CreateShopForm';
import UpdateShopForm from './components/UpdateShopForm';
import ShopModal from './components/ShopModal';
import ProductModal from './components/ProductModal';
import 'bootstrap/dist/css/bootstrap.css';

function App() {
  return (
    <>
      <Navbar />
      <Routes>
        <Route path="/shops" element={<ShopModal />} />
        <Route path="/add"   element={<ShopForm />} />
        <Route path="/:id/update" element={<UpdateShopForm />} />

        <Route path="/products" element={<ProductModal />} />
      </Routes>
    </>
  );
}

createRoot(document.getElementById('root')).render(
  <BrowserRouter>
    <App />
  </BrowserRouter>
);