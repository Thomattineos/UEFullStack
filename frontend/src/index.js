import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route, Navigate  } from 'react-router-dom';
import Navbar from './components/Navbar';
import CreateShopForm from './components/CreateShopForm';
import UpdateShopForm from './components/UpdateShopForm';
import ShopModal from './components/ShopModal';
import ProductModal from './components/ProductModal';
import CreateProductForm from './components/CreateProductForm';
import UpdateProductForm from './components/UpdateProductForm';
import CategoryModal from './components/CategoryModal';
import CreateCategoryForm from './components/CreateCategoryForm';
import UpdateCategoryForm from './components/UpdateCategoryForm';
import ShopProductModal from './components/ShopProductModal';
import 'bootstrap/dist/css/bootstrap.css';

function App() {
  return (
    <>
      <Navbar />
      <Routes>
        <Route path="/" element={<Navigate to="/shops" />}></Route>
        <Route path="/shops" element={<ShopModal />} />
        <Route path="/createShop" element={<CreateShopForm />} />
        <Route path="/:id/updateShop" element={<UpdateShopForm />} />

        <Route path="/products" element={<ProductModal />} />
        <Route path="/createProduct" element={<CreateProductForm />} />
        <Route path="/:id/updateProduct" element={<UpdateProductForm />} />

        <Route path="/categories" element={<CategoryModal />} />
        <Route path="/createCategory" element={<CreateCategoryForm />} />
        <Route path="/:id/updateCategory" element={<UpdateCategoryForm />} />

        <Route path="/shop/:id/products" element={<ShopProductModal />} />
      </Routes>
    </>
  );
}

createRoot(document.getElementById('root')).render(
  <BrowserRouter>
    <App />
  </BrowserRouter>
);