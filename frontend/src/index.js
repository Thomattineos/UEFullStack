import React from 'react';
import { createRoot} from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import ShopForm from './components/ShopForm';
import ShopModal from './components/ShopModal';
import 'bootstrap/dist/css/bootstrap.css';

function App() {
  return (
    <>
      <Navbar />
      <Routes>
        <Route path="/shops" element={<ShopModal />} />
        <Route path="/add"   element={<ShopForm />} />
      </Routes>
    </>
  );
}

createRoot(document.getElementById('root')).render(
  <BrowserRouter>
    <App />
  </BrowserRouter>
);